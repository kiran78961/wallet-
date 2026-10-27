<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletController extends Controller

{
    public function balance(Request $request)
    {
        $wallet = Wallet::where(
        'user_id',
        $request->user()->id
    )->first();
       
        return response()->json([
            'status' => true,
            'message' => 'Wallet balance fetched successfully',
            'data' => [
                'balance' => $wallet->balance
            ]
        ], 200);
    }
    public function addMoney(Request $request)

    {

     $request->validate([
        'amount' => 'required|numeric|min:0.01|max:5000'
    ]);

     $wallet = Wallet::where(
        'user_id',
        $request->user()->id
    )->first();
       
            if ($request->amount > 5000) {
         return response()->json([
        'status' => false,
        'message' => 'Maximum add money limit is ₹5000',
        'amount' => $request->amount
         ], 422);

            }
        

        if (!$wallet) {
            return response()->json([
                'status' => false,
                'message' => 'Wallet not found'
            ], 404);
        }


        

        $transaction = DB::transaction(function () use ($wallet, $request) {

            $balanceBefore = $wallet->balance;

            $wallet->balance = $wallet->balance + $request->amount;

            $wallet->save();

            $transaction = Transaction::create([
                
                'wallet_id' => $wallet->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                'type' => 'credit',
                'amount' => $request->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
            ]);

            return $transaction;
        });

        return response()->json([
            'status' => true,
            'message' => 'Money added successfully',
            'transaction' => $transaction,
            'balance' => $wallet->balance
        ], 200);
    }



     public function debitMoney(Request $request){
       $request->validate([
        'amount' => 'required|numeric|min:0.00|max:5000'
    ]);


     $wallet = Wallet::where(
        'user_id',
        $request->user()->id
     )->first();


     if (!$wallet) {
        return response()->json([
            'status' => false,
            'message' => 'Wallet not found'
        ], 404);

     } 


     if ($wallet->balance < $request->amount) {
        return response()->json([
            'status' => false,
            'message' => 'Insufficient balance',
            'balance' => $wallet->balance
        ], 400);
    } 


       $transaction = DB::transaction(function () use ($wallet, $request) {

       

        $balanceBefore = $wallet->balance;

        $amount = $request->amount;

        $wallet->balance = $wallet->balance - $amount;

        $wallet->save();

        $transactionId = 'TXN-' . strtoupper(Str::random(15));

        $transaction = transaction::create([
            'wallet_id' => $wallet->id,
            'transaction_id' => $transactionId,
            'type' => 'debit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $wallet->balance,
        ]);

        return $transaction;
    });

    
    return response()->json([
        'status' => true,
        'message' => 'Money debited successfully',
        'data' => [
            'transaction_id' => $transaction->transaction_id,
            'type' => $transaction->type,
            'amount' => $transaction->amount,
            'balance_before' => $transaction->balance_before,
            'balance_after' => $transaction->balance_after,
        ]
    ], 200);

     }


     

}