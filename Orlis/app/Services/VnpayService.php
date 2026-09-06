<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class VnpayService
{
    protected string $vnp_TmnCode;
    protected string $vnp_HashSecret;
    protected string $vnp_Url;
    protected string $vnp_Returnurl;

    public function __construct()
    {
        $this->vnp_TmnCode = config('services.vnpay.tmn_code', 'PV1C4YYP');
        $this->vnp_HashSecret = config('services.vnpay.hash_secret', 'QNZBTBNTNSCYLDTCJXWAJHNBEMXWSJCZ');
        $this->vnp_Url = config('services.vnpay.url', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $this->vnp_Returnurl = config('services.vnpay.return_url', env('APP_URL') . '/vnpay/return');
    }

    public function createPaymentUrl(Order $order, float $payAmount, string $transactionType = 'payment'): string
    {
        $vnp_TxnRef = $order->order_code . '_' . time();
        $vnp_Amount = $payAmount * 100; // VNPay nhân 100

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toan don hang " . $order->order_code,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $this->vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return $vnp_Url;
    }

    public function handleIPN(array $inputData): array
    {
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            return ['RspCode' => '97', 'Message' => 'Invalid Checksum'];
        }

        $orderCode = explode('_', $inputData['vnp_TxnRef'])[0];
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return ['RspCode' => '01', 'Message' => 'Order not found'];
        }

        // Idempotency Check: dùng DB lock (lockForUpdate) để chặn race condition
        // khi VNPay gọi IPN đồng thời nhiều lần (chỉ 1 request được xử lý).
        $transactionNo = $inputData['vnp_TransactionNo'];

        $existingTxn = Transaction::where('transaction_code', $transactionNo)->first();
        if ($existingTxn) {
            return ['RspCode' => '02', 'Message' => 'Order already confirmed'];
        }

        // Kiểm tra số tiền khớp DB (Tampering Attack Check)
        $vnpAmount = $inputData['vnp_Amount'] / 100;
        if ($vnpAmount != $order->grand_total && $vnpAmount != $order->deposit_amount) {
            return ['RspCode' => '04', 'Message' => 'Invalid amount'];
        }

        if ($inputData['vnp_ResponseCode'] == '00') {
            try {
                DB::transaction(function () use ($order, $inputData, $vnpAmount, $transactionNo) {
                    // Pessimistic Lock: giữ row order trong suốt transaction
                    // Chặn 2 request IPN chạy song song ghi trùng transaction
                    $lockedOrder = Order::lockForUpdate()->find($order->id);

                    if (!$lockedOrder || $lockedOrder->order_status !== 'pending') {
                        // Đã được xử lý bởi request song song khác — bỏ qua
                        return;
                    }

                    // Kiểm tra lại idempotency bên trong lock (double-check pattern)
                    $alreadyDone = Transaction::where('transaction_code', $transactionNo)->exists();
                    if ($alreadyDone) {
                        return;
                    }

                    Transaction::create([
                        'order_id'         => $lockedOrder->id,
                        'type'             => $vnpAmount < $lockedOrder->grand_total ? 'deposit' : 'payment',
                        'amount'           => $vnpAmount,
                        'currency'         => 'VND',
                        'exchange_rate'    => 1.0,
                        'payment_method'   => 'vnpay',
                        'transaction_code' => $transactionNo,
                        'gateway_response' => json_encode($inputData),
                        'status'           => 'success',
                    ]);
                });
            } catch (\Exception $e) {
                return ['RspCode' => '99', 'Message' => 'Internal error: ' . $e->getMessage()];
            }

            return ['RspCode' => '00', 'Message' => 'Confirm Success'];
        }

        return ['RspCode' => '02', 'Message' => 'Payment Failed'];
    }
}
