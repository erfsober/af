<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Dorsa
{
    // Your API credentials
    protected $username;
    protected $password;
    protected $token;

    public function __construct()
    {
        $this->username = config('services.dorsa.username');
        $this->password = config('services.dorsa.password'); // Set this in .env
        $this->token = $this->getToken(); // Get token on service initialization
    }

    // Get authentication token
    public function getToken()
    {
        $response = Http::post('https://pep.shaparak.ir/dorsa1/token/getToken', [
            'username' => $this->username,
            'password' => $this->password
        ]);

        $data = $response->json();

        if ($response->successful() && $data['resultCode'] === 0) {
            return $data['token'];
        }

        throw new \Exception('Unable to get token: ' . $data['resultMsg']);
    }

    // Make a purchase transaction
    public function makePurchaseTransaction($amount, $local_id, $mobileNumber = null)
    {
        $response = Http::withHeaders([
                                          'Authorization' => 'Bearer ' . $this->token,
                                      ])->post('https://pep.shaparak.ir/dorsa1/api/payment/purchase', [
            'amount' => (int)$amount,
            'invoice' => (string)$local_id,
            'invoiceDate' => verta()->format("Y-m-d"),
            'serviceCode' => 8,
            'serviceType' => 'PURCHASE',
            'callbackApi' => route('web.verify'),
            //'callbackApi' => "http://aftabfars.com",
            'payerMail' => "**************",
            'mobileNumber' => $mobileNumber ?? "**************",
            'terminalNumber' => config('services.dorsa.terminal_id'),
        ]);

        $data = $response->json();

        if ($response->successful() && $data['resultCode'] === 0) {
            return $data['data'];
        }

        throw new \Exception(json_encode($data));
    }

    // Get transaction status
    public function getTransactionStatus($invoiceId)
    {
        $response = Http::withHeaders([
                                          'Authorization' => 'Bearer ' . $this->token,
                                      ])->post('https://pep.shaparak.ir/dorsa1/api/payment/payment-inquiry', [
            'invoiceId' => $invoiceId,
        ]);

        $data = $response->json();

        if ($response->successful() && $data['resultCode'] === 0) {
            return $data['data'];
        }

        throw new \Exception('Transaction status inquiry failed: ' . $data['resultMsg']);
    }

    // Confirm a transaction
    public function confirmTransaction($local_id, $urlId)
    {
        $response = Http::withHeaders([
                                          'Authorization' => 'Bearer ' . $this->token,
                                      ])->post('https://pep.shaparak.ir/dorsa1/api/payment/confirm-transactions', [
            'invoice' => $local_id,
            'urlId' => $urlId,
        ]);

        $data = $response->json();

        if ($response->successful() && $data['resultCode'] === 0) {
            return $data['data'];
        }

        throw new \Exception('Transaction confirmation failed: ' . $data['resultMsg']);
    }

}
