<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['ANTHROPIC_API_KEY'] ?? 'tidak ada';
echo "API Key: " . substr($apiKey, 0, 20) . "...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.anthropic.com/v1/messages');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-api-key: ' . $apiKey,
    'anthropic-version: 2023-06-01',
    'content-type: application/json',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'claude-sonnet-4-5',
    'max_tokens' => 100,
    'messages'   => [['role' => 'user', 'content' => 'Halo, balas dengan OK saja']],
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error    = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Curl Error: $error\n";
echo "Response: $response\n";
