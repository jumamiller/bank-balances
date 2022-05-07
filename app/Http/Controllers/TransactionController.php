<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{
            $transaction=Transaction::create([
                'user_id'  =>$request->input('user_id'),
                'balance'  =>$request->input('balance'),
            ]);
            return response()->json([
                'success'   => true,
                'message'   => 'Transaction added successfully',
                'data'      =>$transaction
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success'   => false,
                'message'   =>$e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try{
            $account_balance=Transaction::where('user_id',$id)->pluck('balance')[0];
            $amount=$request->input('balance');// amount submitted
            $transaction_type=$request->input('transaction_type');
            $receiver=$request->input('receiver');// receiver ID

            if ($transaction_type=='deposit') {
                $new_balance=$amount + $account_balance;
                Transaction::where('user_id',$id)
                    ->update([
                        'balance'   => $new_balance
                    ]);
                return response()->json([
                    'success'   => true,
                    'message'   => 'You have successfully deposited '.$request->input('balance')." your new account balance is $new_balance",
                ]);
            }
            else if ($transaction_type=='withdrawal') {
                if ($account_balance >= $amount ) {
                    $new_balance=$account_balance-$amount;
                    Transaction::where('user_id',$id)
                        ->update([
                            'balance'   => $new_balance
                        ]);
                    return response()->json([
                        'success'   => true,
                        'message'   => "You have successfully withdrawn $amount, your new account balance is $new_balance",
                    ]);
                }
                else {
                    return response()->json([
                        'success'   => true,
                        'message'   => "You have insufficient funds in your account to withdrawn $amount",
                    ]);
                }
            }
            else if ($transaction_type=='transfer') {
                if ($account_balance >= $amount ) {
                    $new_balance=$account_balance-$amount ;
                    Transaction::where('user_id',$id)
                        ->update([
                            'balance'   => $new_balance
                        ]);
                    // update receivers account
                    $receiver_account_balance=Transaction::where('user_id',$receiver)->pluck('balance')[0];
                    $receiver_new_balance=$receiver_account_balance+$amount;

                    Transaction::where('user_id',$receiver)
                        ->update(['balance'=>$receiver_new_balance]);

                    return response()->json([
                        'success'   => true,
                        'message'   => "You have successfully transferred $amount to $receiver , your new account balance is $new_balance",
                    ]);
                }
                else {
                    return response()->json([
                        'success'   => true,
                        'message'   => "You have insufficient funds in your account to transfer $amount",
                    ]);
                }
            }
            else {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Unknown Transaction',
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'success'   => false,
                'message'   =>$e->getMessage()
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
