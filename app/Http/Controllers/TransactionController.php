<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Transaction;
use App\TransactionType;
use App\User;
use Auth;
use Illuminate\Database\QueryException;

class TransactionController extends Controller
{
    public function withdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->respondInvalidParams($validator->messages());
        }

        $user = Auth::guard('api')->user();

        $transaction_type = TransactionType::where('name', 'withdraw')->first();

        try {
            $transaction = new Transaction;
            $transaction->type_id = $transaction_type->id;
            $transaction->sender_id = $user->id;
            $transaction->amount = $request->amount;

            DB::select(
                sprintf('call ProcessUserTransaction(%u, %u, %u, %u)', $transaction->type_id, $transaction->amount, $transaction->sender_id, $transaction->recipient_id)
            );

            $transaction->save();
        } catch (QueryException $ex) {
            return $this->respondInvalidParams('Insufficient balance');
        }

        return $this->respondSuccess('withdrawn');
    }

    public function deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->respondInvalidParams($validator->messages());
        }

        $user = Auth::guard('api')->user();

        $transaction_type = TransactionType::where('name', 'deposit')->first();

        try {
            $transaction = new Transaction;
            $transaction->type_id = $transaction_type->id;
            $transaction->recipient_id = $user->id;
            $transaction->amount = $request->amount;

            DB::select(
                sprintf('call ProcessUserTransaction(%u, %u, %u, %u)', $transaction->type_id, $transaction->amount, $transaction->sender_id, $transaction->recipient_id)
            );

            $transaction->save();
        } catch (QueryException $ex) {
            return $this->respondInvalidParams('Insufficient balance');
        }

        return $this->respondSuccess('stored');
    }

    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|integer',
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->respondInvalidParams($validator->messages());
        }

        $user = Auth::guard('api')->user();
        $recipient = User::find($request->recipient_id);
        if ($user->id == $recipient->id) {
            return $this->respondInvalidParams('cannot transfer to the same account');
        }

        $transaction_type = TransactionType::where('name', 'transfer')->first();

        try {
            $transaction = new Transaction;
            $transaction->type_id = $transaction_type->id;
            $transaction->recipient_id = $recipient->id;
            $transaction->sender_id = $user->id;
            $transaction->amount = $request->amount;

            DB::select(
                sprintf('call ProcessUserTransaction(%u, %u, %u, %u)', $transaction->type_id, $transaction->amount, $transaction->sender_id, $transaction->recipient_id)
            );

            $transaction->save();
        } catch (QueryException $ex) {
            return $this->respondInvalidParams('Insufficient balance');
        }

        return $this->respondSuccess('transferred');
    }
}
