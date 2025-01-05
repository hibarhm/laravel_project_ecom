<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index(){
        $items = Cart::instance('cart')->content();
        return view('cart',compact('items'));
    }
   public function add_to_cart(Request $request)
   {
        Cart::instance('cart')->add($request->id,$request->name,$request->quantity,$request->price)->associate('App\Models\Product') ;
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();

    }
    public function decrease_cart_quantity($rowId){
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }

    public function checkout()
    {
        if(!Auth::check())
        {
            return redirect()->route('login');
        }

        $address = Address::where('user_id',Auth::user()->id)->where('isdefault',1)->first();
        return view('checkout',compact('address'));
    }

    public function place_an_order(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id',$user_id)->where('isdefault',true)->first();
        
        if(!$address){
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:10',
                'zip' => 'required|numeric|digits:6',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required',
          
            ]);

            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->country ='SriLanka';
            $address->user_id = $user_id;
            $address->isdefault = true;
            $address->save();
            
        }
        $this->setAmountforCheckout();

        $order = new Order();
        $order->user_id = $user_id;
         $order->subtotal = Session::get('checkout')['subtotal'];
         $order->discount = Session::get('checkout')['discount'];
         $order->tax = Session::get('checkout')['tax'];
         $order->total = Session::get('checkout')['total'];
         $order->name = $address->name;
         $order->phone = $address->phone;
         $order->locality = $address->locality;
         $order->address = $address->address;
         $order->city = $address->city;
         $order->state = $address->state;
         $order->country = $address->country;
         $order->landmark = $address->landmark;
         $order->zip = $address->zip;
         $order->save();

         foreach(Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
         }

         if($request->mode == "cod"){
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = 'pending';
            $transaction->quantity = 1;
            $transaction->save();

         }

         Cart::instance('cart')->destroy();
         Session::forget('checkout');
         Session::put('order_id',$order->id);
         return redirect()->route('cart.order.confirmation');


    }

    public function setAmountforCheckout()
    {
        if(!Cart::instance('cart')->content()->count() > 0)
        {
            Session::forget('checkout');
            return;
        }
        else{
            Session::put('checkout',[
                'discount' => 0,
                'subtotal' => str_replace(',', '', Cart::instance('cart')->subtotal(2, '.', '')), 
                'tax' => str_replace(',', '', Cart::instance('cart')->tax(2, '.', '')), 
                'total' => str_replace(',', '', Cart::instance('cart')->total(2, '.', '')),
            ]);
        
        }
    }

    public function order_confirmation(){
        if(Session::has('order_id'))
        {
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation',compact('order'));
        }
        return redirect()->route('cart.index');
    }
}