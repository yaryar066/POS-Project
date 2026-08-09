<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $customers = Customer::all();

        return view('pos.index', compact('categories', 'products', 'customers'));
    }

    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'subtotal'       => 'required|numeric',
            'tax'            => 'required|numeric',
            'discount'       => 'required|numeric',
            'total'          => 'required|numeric',
            'payment_method' => 'required|string',
            'paid_amount'    => 'required|numeric',
            'change_return'  => 'required|numeric',
            'customer_id'    => 'nullable|exists:customers,id',
            'redeemed_points'=> 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));

            $order = Order::create([
                'order_number'   => $orderNumber,
                'user_id'        => Auth::id(),
                'customer_id'    => $request->customer_id,
                'subtotal'       => $request->subtotal,
                'tax'            => $request->tax,
                'discount'       => $request->discount,
                'total'          => $request->total,
                'payment_method' => $request->payment_method,
                'paid_amount'    => $request->paid_amount,
                'change_return'  => $request->change_return,
                'change_amount'  => $request->change_return,
            ]);

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                if (!$product || $product->stock < $item['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: " . ($product ? $product->name : 'Unknown')
                    ], 422);
                }

                $product->decrement('stock', $item['qty']);

                // Create Order Item (Using unit_price matching database schema)
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'unit_price'   => $item['price'],
                    'quantity'     => $item['qty'],
                    'subtotal'     => $item['price'] * $item['qty'],
                ]);
            }

            // Customer Loyalty Points Calculation
            if ($request->customer_id) {
                $customer = Customer::find($request->customer_id);
                if ($customer) {
                    if ($request->redeemed_points && $request->redeemed_points > 0) {
                        $customer->decrement('points', $request->redeemed_points);
                    }

                    $earnedPoints = floor($request->total / 10);
                    if ($earnedPoints > 0) {
                        $customer->increment('points', $earnedPoints);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'order_number' => $orderNumber,
                'redirect_url' => route('admin.sales.show', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Checkout error: ' . $e->getMessage()
            ], 500);
        }
    }
}