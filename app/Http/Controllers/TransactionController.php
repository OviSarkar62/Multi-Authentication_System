<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function transactionIndex()
    {
        if (is_null($this->user) || !$this->user->can('transaction.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $transactions = Transaction::all(); // Fetch the orders
    
        return view('transactions.index', compact('transactions'));
    }

    public function createTransaction()
    {
        if (is_null($this->user) || !$this->user->can('create.transaction')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        return view('transactions.add-transaction');
    }

    // Employee Register Post and Store
    public function storeTransaction(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('store.transaction')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $status = $request->input('status');

        $transaction = Transaction::create([
            'restaurant_name' => $request->input('restaurant_name'),
            'price' => $request->input('price'),
            'status' => $status,
        ]);

        return redirect()->route('transaction.index')->with('successMessage', 'Transaction Added successfully!');
    }

    public function editTransaction($id)
    {
        if (is_null($this->user) || !$this->user->can('edit.transaction')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $transaction = Transaction::find($id);
        return view('transactions.edit-transaction', compact('transaction'));
    }

    public function updateTransaction(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('edit.transaction')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $transaction = Transaction::find($id);

        $status = $request->input('status');

        $transaction->update([
            'restaurant_name' => $request->input('restaurant_name'),
            'price' => $request->input('price'),
            'status' => $status,
        ]);

        return redirect()->route('transaction.index')->with('successMessage', 'Transaction updated successfully!');
    }

    public function destroyTransaction($id)
    {
        if (is_null($this->user) || !$this->user->can('delete.transaction')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $transaction = Transaction::find($id);

        $transaction->delete();

        return redirect()->route('transaction.index')->with('successMessage', 'Transaction deleted successfully!');
    }

}
