<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Services\FileService;
use App\Http\Services\OrderService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Partner;
use Illuminate\Http\Request;


class OrderController extends Controller {
    private OrderService $service;

    public function __construct(OrderService $service) {
        $this->service = $service;
    }

    public function index(Request $request, Partner $partner) {
        return $this->success([
            'orders' => $partner->orders()
                ->orderByDesc('id')
                ->byDistrict($request->get('district_id'))
                ->get()
        ]);
    }

    public function getOrdersByStatus(Request $request, $customer = null) {
        if ($customer != null) {
            return $this->success([
                'orders' => Order::query()->with('client', 'driver')
                    ->where('customer_id', $customer)
                    ->where('status', $request->status)
                    ->orderByDesc('id')
                    ->get()
            ]);
        }

        return $this->success([
            'orders' => Order::with('customer')
                ->where('status', $request->status)
                ->orderByDesc('id')->get()
        ]);
    }

    public function getOrdersHistory(Request $request, $customer = null) {
        if ($customer != null) return $this->success([
            'orders' => Order::query()->with(['customer', 'partner'])
                ->where('customer_id', $customer)
                ->whereIn('status', [-1, 4])
                ->orderByDesc('id')->get()
        ]);

        return $this->success([
            'orders' => Order::with('customer')
                ->where('status', $request->status)
                ->orderByDesc('id')->get()
        ]);
    }

    public function getDeliveryOrders($customer) {
        return $this->success([
            'orders' => Order::with('partner')
                ->where('customer_id', $customer)
                ->whereIn('status', [0, 1, 2, 3])->get()
        ]);
    }

    public function getOrderItems($order) {
        return $this->success([
            'items' => OrderItem::query()
                ->whereHas('product')
                ->with('product')
                ->where('order_id', $order)->get()
        ]);
    }

    public function store(OrderRequest $request) {
        $data = $request->validated();
        $order = Order::query()->create($data);
        foreach ($data['order_items'] as $item) {
            OrderItem::query()->create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        return $this->success(['order' => $order]);
    }

    public function show(Order $order) {
        return $this->success(['order' => $order]);
    }

    public function changeOrderStatus(Request $request, Order $order) {
        return $this->service->changeStatus($request, $order);
    }

    public function changeItemStatus(Request $request, OrderItem $item) {
        if ($request->status) {
            $item->update(['status' => $request->status]);
            return $this->success($item);
        }
        return $this->fail([], 'Invalid status');
    }

    /* Payment v2 */
    public function verify(Request $request, Order $order) {
        $order->update([
            'paid' => Order::PAID,
            'pay_check' => FileService::upload($request->file('cheque'), 'checks')
        ]);

        return $this->success($order);
    }
}
