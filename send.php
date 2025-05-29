<?php
$rawInput = fopen('php://input', 'r');
$tempStream = fopen('php://temp', 'r+');
stream_copy_to_stream($rawInput, $tempStream);
rewind($tempStream);
$result = json_decode(stream_get_contents($tempStream), true);
$dsn = "mysql:host=localhost;dbname=gdlib_chart;charset=UTF8";
$pdo = new PDO($dsn, "root", "");
$set_value = $pdo->prepare("UPDATE data SET value = :temperature WHERE id = :id");
$set_value->execute([':temperature' => $result["value"], ':id' => $result["id"]]);
$pdo = null;
?>