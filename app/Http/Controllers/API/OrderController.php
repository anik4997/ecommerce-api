<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'items'=>'required|array|min:1',
            'items.*.product_id'=>'required|exists:products,id',
            'items.*.quantity'=>'required|integer|min:1',
            'shipping_address'=>'nullable|array',
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            $total = 0;
            $orderItems = [];

            foreach ($data['items'] as $row) {
                $product = Product::lockForUpdate()->findOrFail($row['product_id']);
                $qty = (int) $row['quantity'];

                if ($product->stock < $qty) {
                    DB::rollBack();
                    return response()->json(['error'=>"Product {$product->title} has insufficient stock."], 400);
                }

                $unitPrice = $product->price;
                $lineTotal = $unitPrice * $qty;
                $total += $lineTotal;

                $product->stock -= $qty;
                $product->save();

                $orderItems[] = [
                    'product_id'=>$product->id,
                    'quantity'=>$qty,
                    'unit_price'=>$unitPrice,
                    'line_total'=>$lineTotal,
                ];
            }

            $order = Order::create([
                'user_id'=>$user->id,
                'total_amount'=>$total,
                'shipping_address'=>$data['shipping_address'] ?? null,
                'status'=>'pending',
            ]);

            foreach ($orderItems as $it) {
                $it['order_id'] = $order->id;
                OrderItem::create($it);
            }

            DB::commit();
            return response()->json(['order'=>$order->load('items.product')], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error'=>'Unable to create order','details'=>$e->getMessage()], 500);
        }
    }
}
