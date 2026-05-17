<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GROQ_API_KEY'] ?? 'tidak ada';
echo "API Key: " . substr($apiKey, 0, 15) . "...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.groq.com/openai/v1/chat/completions');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model'      => 'llama-3.3-70b-versatile',
    'max_tokens' => 50,
    'messages'   => [
        ['role' => 'user', 'content' => 'Halo, balas OK saja'],
    ],
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";
