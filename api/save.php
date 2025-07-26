<?php
header('Content-Type: application/json; charset=utf-8');
$key = $_ENV['ENC_KEY'] ?? 'defaultkey123456';   // 16-char AES key
$dir = __DIR__ . '/../data';
if (!is_dir($dir)) mkdir($dir, 0777, true);

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) { http_response_code(400); exit(json_encode(['error'=>'Bad JSON'])); }

$hash = substr(md5(uniqid()), 0, 8);
$plain = json_encode($input, JSON_PRETTY_PRINT);
$cipher = openssl_encrypt($plain, 'AES-128-CBC', $key, 0, substr($key,0,16));
file_put_contents("$dir/{$hash}.json", $cipher);

echo json_encode(['id'=>$hash, 'msg'=>'saved']);