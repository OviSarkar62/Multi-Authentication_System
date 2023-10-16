<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function orderIndex()
    {
        if (is_null($this->user) || !$this->user->can('order.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $orders = Order::all(); // Fetch the orders
    
        return view('orders.index', compact('orders'));
    }
    

    public function createOrder()
    {
        if (is_null($this->user) || !$this->user->can('create.order')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        return view('orders.add-order');
    }

    // Store Order
    public function storeOrder(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('store.order')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        $status = $request->input('status');

        $order = Order::create([
            'name' => $request->input('name'),
            'restaurant_name' => $request->input('restaurant_name'),
            'price' => $request->input('price'),
            'status' => $status,
        ]);

        return redirect()->route('order.index')->with('successMessage', 'Order Added successfully!');
    }

    // Order Edit
    public function editOrder($id)
    {
        if (is_null($this->user) || !$this->user->can('edit.order')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $order = Order::findOrFail($id);
        return view('orders.edit-order', compact('order'));
    }

    // Order Update
    public function updateOrder(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('edit.order')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $order = Order::findOrFail($id);

        $order->update([
            'name' => $request->input('name'),
            'restaurant_name' => $request->input('restaurant_name'),
            'price' => $request->input('price'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('order.index')->with('successMessage', 'Order updated successfully!');
    }

    // Order Destroy
    public function destroyOrder($id)
    {
        if (is_null($this->user) || !$this->user->can('delete.order')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('order.index')->with('successMessage', 'Order deleted successfully!');
    }
}
