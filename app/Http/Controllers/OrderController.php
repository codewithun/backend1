<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Tambahkan ini

class OrderController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Received order request:', ['data' => $request->all()]);

        try {
            $data = $request->validate([
                'user_id' => 'required|exists:users,id',
                'customer' => 'nullable|string',
                'time' => 'required|date',
                'payment_method' => 'required|string',
                'total' => 'required|numeric',
                'items' => 'required|array',
                'items.*.name' => 'required|string',
                'items.*.quantity' => 'required|integer',
                'items.*.price' => 'required|numeric',
                'items.*.total_item_price' => 'required|numeric',
                'items.*.type' => 'required|string|in:product,ticket',
                'items.*.id' => 'required|integer'
            ]);

            $formattedTime = \Carbon\Carbon::parse($data['time'])->format('Y-m-d H:i:s');

            // Create order with user_id
            $order = Order::create([
                'user_id' => $data['user_id'],
                'customer' => $data['customer'],
                'time' => $formattedTime,
                'payment_method' => $data['payment_method'],
                'total' => $data['total'],
            ]);

            // Create order items with proper relationships
            foreach ($data['items'] as $item) {
                $itemData = [
                    'order_id' => $order->id,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_item_price' => $item['total_item_price'],
                    'type' => $item['type']
                ];

                // Set proper ID based on item type
                if ($item['type'] === 'product') {
                    $itemData['product_id'] = $item['id'];
                } else {
                    $itemData['ticket_id'] = $item['id'];
                }

                OrderItem::create($itemData);
            }

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('items')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating order:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        $order = Order::with('items')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }


    // Get all orders
    public function index()
    {
        $orders = Order::with('items')->get();
        return response()->json($orders, 200);
    }

    // Update an order
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'customer' => 'nullable|string',
            'time' => 'required|date',
            'payment_method' => 'required|string',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric',
            'items.*.total_item_price' => 'required|numeric',
        ]);

        $order->update([
            'customer' => $data['customer'],
            'time' => $data['time'],
            'payment_method' => $data['payment_method'],
            'total' => $data['total'],
        ]);

        // Delete old items and add new items
        $order->items()->delete();
        foreach ($data['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_item_price' => $item['total_item_price'],
            ]);
        }

        return response()->json(['message' => 'Order updated successfully', 'order' => $order], 200);
    }

    // Delete an order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
