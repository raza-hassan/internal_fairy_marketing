<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller {

    public function index() {
        $orders = Order::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.order.index', compact('orders'));
    }

    public function show($id) {
        $order = Order::find($id);
        return view('admin.order.edit', compact('order'));
    }

    public function update(Request $request, $id) {
        $order = Order::find($id);
        $order->status = $request->input('status');
        $order->save();
        if($request->get('status') == '2' && !is_null($order->user)){
            Mail::to($order->user->email)->send(new OrderShipped($order));
        }
        return redirect('admin/orders')->withStatus(__('Order Status Changed successfully.'));
    }

    public function destroy(Order $order) {
        $order->address()->delete();
        $order->ordermetas()->delete();
        $order->delete();
        return back()->withStatus(__('Order successfully deleted.'));
    }

}
