<?php
$width = isset($_GET['w']) ? $_GET['w'] : 600;
$height = isset($_GET['h']) ? $_GET['h'] : 400;
$padding = isset($_GET['p']) ? $_GET['p'] : 80;
$data = [];
$coords = [];

$dsn = "mysql:host=localhost;dbname=gdlib_chart;charset=UTF8";
$pdo = new PDO($dsn, "root", "");
$get_values = $pdo->prepare("SELECT value FROM data");
$get_values->execute();
$values = $get_values->fetchAll();

if (count($values) > 0) {
    foreach ($values as $value) {
        array_push($data, $value[0]);
    }
}
?>