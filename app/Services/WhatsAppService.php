<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $provider;
    protected array $config;

    public function __construct()
    {
        $this->provider = config('whatsapp.provider', 'log');
        $this->config = config("whatsapp.{$this->provider}", []);
    }

    /**
     * Send a WhatsApp message.
     *
     * @param string $to Phone number of the recipient (with country code)
     * @param string $message The content of the message
     * @return bool
     */
    public function sendMessage(string $to, string $message): bool
    {
        $toClean = preg_replace('/[^0-9]/', '', $to);

        // Si tiene 10 dígitos (formato local de México), anteponemos el código de móvil '521'
        if (strlen($toClean) === 10) {
            $toClean = '521' . $toClean;
        } elseif (strlen($toClean) === 12 && str_starts_with($toClean, '52') && !str_starts_with($toClean, '521')) {
            // Si tiene 12 dígitos y empieza con '52' pero no '521', insertamos el '1'
            $toClean = '521' . substr($toClean, 2);
        }

        if (empty($toClean)) {
            Log::warning("WhatsAppService: Attempted to send to empty phone number.");
            return false;
        }

        Log::info("WhatsAppService: Sending message via '{$this->provider}' to {$toClean}");

        switch ($this->provider) {
            case 'ultramsg':
                return $this->sendViaUltraMsg($toClean, $message);
            case 'twilio':
                return $this->sendViaTwilio($toClean, $message);
            case 'log':
            default:
                Log::channel('single')->info("WhatsApp Simulation:\nTo: {$toClean}\nMessage:\n{$message}");
                return true;
        }
    }

    protected function sendViaUltraMsg(string $to, string $message): bool
    {
        $instanceId = $this->config['instance_id'] ?? '';
        $baseUrl = rtrim($this->config['url'] ?? 'https://api.ultramsg.com', '/');
        
        // Si la URL provista ya contiene el ID de instancia, no lo volvemos a concatenar
        if (!empty($instanceId) && !str_contains($baseUrl, $instanceId)) {
            $url = $baseUrl . '/' . $instanceId . '/messages/chat';
        } else {
            $url = $baseUrl . '/messages/chat';
        }
        
        try {
            $response = Http::asForm()->post($url, [
                'token' => $this->config['token'] ?? '',
                'to' => $to,
                'body' => $message,
                'priority' => 10,
            ]);

            if ($response->successful()) {
                Log::info("WhatsAppService: UltraMsg response: " . $response->body());
                return true;
            }

            Log::error("WhatsAppService: UltraMsg failed. Status: {$response->status()}, Body: {$response->body()}");
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsAppService: UltraMsg exception: {$e->getMessage()}");
            return false;
        }
    }

    protected function sendViaTwilio(string $to, string $message): bool
    {
        $sid = $this->config['sid'] ?? '';
        $token = $this->config['token'] ?? '';
        $from = $this->config['from'] ?? '';

        if (empty($sid) || empty($token) || empty($from)) {
            Log::error("WhatsAppService: Twilio configuration is incomplete.");
            return false;
        }

        $fromFormatted = str_starts_with($from, 'whatsapp:') ? $from : "whatsapp:{$from}";
        $toFormatted = str_starts_with($to, '+') ? $to : "+{$to}";
        $toFormatted = "whatsapp:{$toFormatted}";

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";

        try {
            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post($url, [
                    'To' => $toFormatted,
                    'From' => $fromFormatted,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                Log::info("WhatsAppService: Twilio response: " . $response->body());
                return true;
            }

            Log::error("WhatsAppService: Twilio failed. Status: {$response->status()}, Body: {$response->body()}");
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsAppService: Twilio exception: {$e->getMessage()}");
            return false;
        }
    }
}
