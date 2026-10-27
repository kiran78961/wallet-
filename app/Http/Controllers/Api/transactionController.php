<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\transaction;
use App\Models\Wallet;




class transactionController extends Controller
{
   public function transactionHistory(Request $request){
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

    $transactions = transaction::where(
        
        'wallet_id',
        $wallet->id
    )
    ->latest()
    ->get();


     return response()->json([
        'status' => true,
        'message' => 'Transaction history fetched successfully',
        'data' => $transactions
    ], 200);
   
   }
   

    


        
}
