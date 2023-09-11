<?php

namespace App\Http\Controllers;

use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\pProduct as Product;
// use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use App\Models\Stock;
use App\Models\Voucher;

class CartController extends Controller
{
    public function index()
    {
        $apply_voucher=0;
        $vcheck='* plese input voucher';

        $vdis_percent=0;

        list($products, $cartItems) = Cart::getProductsAndCartItems();
        $total = 0;
        $rtStock=array();
        foreach ($products as $product) {
            $total += $product->price * $cartItems[$product->id]['quantity'];

            $rtStock[$product['item_code']]=(int)Product::realtimeStock($product['item_code']);

            // $rtStock[]= Stock::query()
            //     ->where('item_code', '=',$product->item_code)
            //     ->first(); ;
            // $stocky=$product->stock->stock;
        }

        // print_r($rtStock);
        // dd($rtStock);

        return view('cart.index', compact('cartItems', 'products', 'total','rtStock','vdis_percent','apply_voucher','vcheck'));
    }

    public function voucher(Request $request){
        $voucher=0;
        $apply_voucher=$request->voucher;
        $voucher_discount = 0;
        $vdis_percent=0;
        $vcheck='* plese input voucher';

        $voucher = Voucher::query()
        ->where(['code'=>$apply_voucher])
        ->first();

        if(!empty($voucher)){
            $vdis_percent=$voucher->discount_percent/100;
            $vcheck='✔️ voucher is valid';
        } else{
            $vcheck='❌ voucher not valid';
        }
        
        // dd($voucher->qty);

        // $voucher=$voucher->discount_percent;

        list($products, $cartItems) = Cart::getProductsAndCartItems();
        $total = 0;
        $rtStock=array();
        foreach ($products as $product) {
            $total += $product->price * $cartItems[$product->id]['quantity'];

            $rtStock[$product['item_code']]=(int)Product::realtimeStock($product['item_code']);}

        return view('cart.index', compact('cartItems', 'products', 'total','rtStock','voucher','vdis_percent','vcheck','apply_voucher'));
    }

    public function add(Request $request, Product $product)
    {
        // $quantity=$_POST['quantity'];
        // dd($qty);
        $quantity = $request->post('quantity',10);
        // dd($quantity);
        $user = $request->user();

        $type = $product->pre_order;
        
        if ($user) {

            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->update();
            } else {
                $data = [
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
                CartItem::create($data);
            }

            return response([
                'count' => Cart::getCartItemsCount()
            ]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            $productFound = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $productFound = true;
                    break;
                }
            }
            if (!$productFound) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    // 'price' => $product->price
                    'price' => $product->retail_price
                ];
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    public function remove(Request $request, Product $product)
    {
        $user = $request->user();
        if ($user) {
            $cartItem = CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->delete();
            }

            return response([
                'count' => Cart::getCartItemsCount(),
            ]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    public function updateQuantity(Request $request, Product $product)
    {
        $quantity = (int)$request->post('quantity');
        $user = $request->user();
        if ($user) {
            CartItem::where(['user_id' => $request->user()->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);

            return response([
                'count' => Cart::getCartItemsCount(),
            ]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }
}
