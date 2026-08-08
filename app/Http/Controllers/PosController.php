<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    /**
     * Display POS Cashier Terminal Screen
     */
    public function index(): View
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)
                           ->where('stock', '>', 0)
                           ->with('category')
                           ->get();

        return view('pos.index', compact('categories', 'products'));
    }

    /**
     * Process POS Order Checkout
     */
    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'subtotal'       => 'required|numeric|min:0',
            'tax'            => 'required|numeric|min:0',
            'discount'       => 'required|numeric|min:0',
            'total'          => 'required|numeric|min:0',
            'paid_amount'    => 'required|numeric|min:0',
            'change_amount'  => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Order Record
            $order = Order::create([
                'order_number'   => 'ORD-' . strtoupper(uniqid()),
                'user_id'        => Auth::id(),
                'subtotal'       => $validated['subtotal'],
                'tax'            => $validated['tax'],
                'discount'       => $validated['discount'],
                'total'          => $validated['total'],
                'paid_amount'    => $validated['paid_amount'],
                'change_amount'  => $validated['change_amount'],
                'payment_method' => $validated['payment_method'],
            ]);

            // 2. Process Order Items & Deduct Product Stock
            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['id']);

                if ($product->stock < $itemData['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: {$product->name}"
                    ], 422);
                }

                // Deduct stock
                $product->decrement('stock', $itemData['qty']);

                // Create Item Order
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'unit_price'   => $product->price,
                    'quantity'     => $itemData['qty'],
                    'subtotal'     => $product->price * $itemData['qty'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Order completed successfully!',
                'order_number' => $order->order_number,
                'order_id'     => $order->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }
}