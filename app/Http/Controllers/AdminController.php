<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AdminController extends Controller
{
  public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();

        $totalWalletBalance = Wallet::sum('balance');

        $totalCredit = Transaction::where('type', 'credit')
            ->sum('amount');

        $totalDebit = Transaction::where('type', 'debit')
            ->sum('amount');

        $totalTransactions = Transaction::count();

        return response()->json([
            'status' => true,
            'message' => 'Admin dashboard data',

            'data' => [
                'total_users' => $totalUsers,
                'total_wallet_balance' => $totalWalletBalance,
                'total_credit' => $totalCredit,
                'total_debit' => $totalDebit,
                'total_transactions' => $totalTransactions,
            ]
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | ALL USERS
    |--------------------------------------------------------------------------
    */

    public function users()
    {
        $users = User::where('role', 'user')
            ->with('wallet')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Users fetched successfully',

            'data' => $users
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | SINGLE USER WALLET
    |--------------------------------------------------------------------------
    */

    public function wallet($userId)
    {
        $user = User::where('role', 'user')
            ->with('wallet')
            ->find($userId);

        if (!$user) {

            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Wallet details',

            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'balance' => $user->wallet->balance ?? 0
            ]
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN ADD MONEY
    |--------------------------------------------------------------------------
    */

    public function addMoney(Request $request, $userId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:5000'
        ]);

        $user = User::where('role', 'user')
            ->find($userId);

        if (!$user) {

            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $transaction = DB::transaction(function () use (
            $request,
            $userId
        ) {

            $wallet = Wallet::where(
                'user_id',
                $userId
            )
                ->lockForUpdate()
                ->first();

            if (!$wallet) {

                $wallet = Wallet::create([
                    'user_id' => $userId,
                    'balance' => 0
                ]);
            }

            $amount = $request->amount;

            $balanceBefore = $wallet->balance;

            $wallet->balance =
                $wallet->balance + $amount;

            $wallet->save();

            $balanceAfter = $wallet->balance;

            return Transaction::create([

                'wallet_id' => $wallet->id,

                'transaction_id' =>
                    'TXN-' .
                    strtoupper(Str::random(15)),

                'type' => 'credit',

                'amount' => $amount,

                'balance_before' =>
                    $balanceBefore,

                'balance_after' =>
                    $balanceAfter
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Money added successfully',

            'data' => [
                'transaction_id' =>
                    $transaction->transaction_id,

                'type' =>
                    $transaction->type,

                'amount' =>
                    $transaction->amount,

                'balance_before' =>
                    $transaction->balance_before,

                'balance_after' =>
                    $transaction->balance_after
            ]

        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN DEBIT MONEY
    |--------------------------------------------------------------------------
    */

    public function debitMoney(Request $request, $userId)
    {
        $request->validate([
            'amount' =>
                'required|numeric|min:0.01|max:5000'
        ]);

        $user = User::where('role', 'user')
            ->find($userId);

        if (!$user) {

            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        try {

            $transaction = DB::transaction(
                function () use (
                    $request,
                    $userId
                ) {

                    $wallet = Wallet::where(
                        'user_id',
                        $userId
                    )
                        ->lockForUpdate()
                        ->first();

                    if (!$wallet) {

                        throw new \Exception(
                            'Wallet not found'
                        );
                    }

                    $amount = $request->amount;

                    if ($wallet->balance < $amount) {

                        throw new \Exception(
                            'Insufficient balance'
                        );
                    }

                    $balanceBefore =
                        $wallet->balance;

                    $wallet->balance =
                        $wallet->balance - $amount;

                    $wallet->save();

                    $balanceAfter =
                        $wallet->balance;

                    return Transaction::create([

                        'wallet_id' =>
                            $wallet->id,

                        'transaction_id' =>
                            'TXN-' .
                            strtoupper(
                                Str::random(15)
                            ),

                        'type' => 'debit',

                        'amount' => $amount,

                        'balance_before' =>
                            $balanceBefore,

                        'balance_after' =>
                            $balanceAfter
                    ]);
                }
            );

            return response()->json([

                'status' => true,

                'message' =>
                    'Money debited successfully',

                'data' => [

                    'transaction_id' =>
                        $transaction->transaction_id,

                    'type' =>
                        $transaction->type,

                    'amount' =>
                        $transaction->amount,

                    'balance_before' =>
                        $transaction->balance_before,

                    'balance_after' =>
                        $transaction->balance_after
                ]

            ], 200);

        } catch (\Exception $e) {

            return response()->json([

                'status' => false,

                'message' =>
                    $e->getMessage()

            ], 400);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ALL TRANSACTIONS
    |--------------------------------------------------------------------------
    */

    public function transactions()
    {
        $transactions = Transaction::with(
            'wallet.user'
        )
            ->latest()
            ->paginate(20);

        return response()->json([

            'status' => true,

            'message' =>
                'Transactions fetched successfully',

            'data' =>
                $transactions

        ], 200);
    }
}