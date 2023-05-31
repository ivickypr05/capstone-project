<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user_id = Auth::user()->id;
        $products = Product::with('category')->get();
        $carts = Cart::with('product')->where('user_id', $user_id)->get();
        $total = Cart::join('products', 'carts.product_id', '=', 'products.id')
            ->where('user_id', $user_id)
            ->sum(DB::raw('carts.amount * products.sell_price'));
        return view('cashier.cart', compact('user', 'products', 'carts', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function addtocart(Request $request, $id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);
        if ($request->amount > $product->stock) {
            return redirect()->back()->with('error', 'maaf stok tidak mencukupi');
        }

        $cart = Cart::where('user_id', $user->id)->where('product_id', $product->id)->first();
        // dd($cart);
        if ($cart) {
            Cart::where('id', $cart->id)->update(['amount' => $cart->amount + $request->amount]);
        } else {
            $cart = new Cart;
            $cart->user_id = $user->id;
            $cart->product_id = $product->id;
            $cart->cashier_name = $user->name;
            $cart->amount = $request->amount;
            $cart->save();
        }
        $amount = $product->stock - $request->amount;
        $product->update([
            'stock' => $amount
        ]);
        return redirect('/cashier')->with('success', 'Product berhasil ditambah ke keranjang');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $product = Product::findOrFail($cart->product_id);

        if ($request->amount > $product->stock) {
            return redirect()->back()->with('error', 'Maaf, stok tidak mencukupi');
        }

        $data = $request->validate(['amount' => 'required|min:1|max:' . $product->stock]);
        $oldAmount = $cart->amount;
        $cart->update($data);

        $difference = $request->amount - $oldAmount;

        if ($difference > 0) {
            $product->decrement('stock', $difference);
        } elseif ($difference < 0) {
            $product->increment('stock', abs($difference));
        }

        return redirect('cart')->with('toast_success', 'Jumlah berhasil diubah');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $product = Product::findOrFail($cart->product_id);

        $product->increment('stock', $cart->amount);

        $cart->delete();

        return redirect('cart')->with('toast_success', 'Produk berhasil dihapus');
    }
}
