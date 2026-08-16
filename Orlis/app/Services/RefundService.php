<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RefundService
{
    protected string $vnp_TmnCode;
    protected string $vnp_HashSecret;
    protected string $vnp_ApiUrl;

    public function __construct()
    {
        $this->vnp_TmnCode = config('services.vnpay.tmn_code', 'YOUR_TMN_CODE');
        $this->vnp_HashSecret = config('services.vnpay.hash_secret', 'YOUR_HASH_SECRET');
        $this->vnp_ApiUrl = config('services.vnpay.api_url', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction');
    }

    public function refund(Order $order, Transaction $paymentTransaction, string $userIp, \App\Services\InventoryService $inventoryService): bool
    {
        $vnp_RequestId = time() . '_' . rand(1000, 9999);
        $vnp_Command = "refund";
        $vnp_TransactionType = "02"; // 02: Hoàn tiền toàn phần
        $vnp_Amount = $paymentTransaction->amount * 100;
        $vnp_CreateBy = "Admin";
        $vnp_CreateDate = date('YmdHis');
        $vnp_TxnRef = $order->order_code; 
        $vnp_TransactionDate = date('YmdHis', strtotime($paymentTransaction->created_at));

        $hashData = "{$vnp_RequestId}|2.1.0|{$vnp_Command}|{$this->vnp_TmnCode}|{$vnp_TransactionType}|{$vnp_TxnRef}|{$vnp_Amount}||{$vnp_TransactionDate}|{$vnp_CreateBy}|{$vnp_CreateDate}|{$userIp}|Refund Order {$order->order_code}";
        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        $data = [
            "vnp_RequestId" => $vnp_RequestId,
            "vnp_Version" => "2.1.0",
            "vnp_Command" => $vnp_Command,
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_TransactionType" => $vnp_TransactionType,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_Amount" => $vnp_Amount,
            "vnp_TransactionNo" => "",
            "vnp_TransactionDate" => $vnp_TransactionDate,
            "vnp_CreateBy" => $vnp_CreateBy,
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_IpAddr" => $userIp,
            "vnp_OrderInfo" => "Refund Order " . $order->order_code,
            "vnp_SecureHash" => $secureHash
        ];

        // Call API
        $response = Http::post($this->vnp_ApiUrl, $data);
        $result = $response->json();

        if (isset($result['vnp_ResponseCode']) && $result['vnp_ResponseCode'] == '00') {
            DB::transaction(function () use ($order, $paymentTransaction, $result, $inventoryService) {
                // Đổi trạng thái đơn hàng
                $order->update(['order_status' => 'refunded']);

                // Ghi nhận dòng tiền hoàn trả
                Transaction::create([
                    'order_id' => $order->id,
                    'type' => 'refund',
                    'amount' => $paymentTransaction->amount,
                    'currency' => 'VND',
                    'exchange_rate' => 1.0,
                    'payment_method' => 'vnpay',
                    'transaction_code' => $result['vnp_TransactionNo'] ?? null,
                    'gateway_response' => json_encode($result),
                    'status' => 'success',
                ]);

                // Nhả kho nếu hàng chưa đi
                $orderItems = DB::table('order_items')->where('order_id', $order->id)->get();
                foreach ($orderItems as $item) {
                    // $inventoryService->releaseStock($item->variant_id, ...);
                }
            });

            return true;
        }

        return false;
    }
}
