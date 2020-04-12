<?php
header("Access-Control-Allow-Origin: *");
require '../vendor/autoload.php';

use App\Helper\DatabaseManager;
const PHP_EOL_BR = ',<br>';
$manager = DatabaseManager::getManager();
$repo = new \App\Repository\RecordRepository();
$res = $manager->query($repo->getLastRecordQuery())->fetchAll();

$response = [
    'temperature' => $res[0]['temperature'],
    'humidity' => $res[0]['humidity'],
    'pressure' => $res[0]['pressure'],
    'co2' => $res[0]['co2'],
    'created' => $res[0]['created'],
];

if ($_SERVER['HTTP_HOST'] === 'siri.izeebot.top') {
    echo 'temperature '.$res[0]['temperature'].' C'.PHP_EOL_BR.
    'humidity '.$res[0]['humidity'].'% '.PHP_EOL_BR.
//    'pressure '.$res[0]['pressure'].''.PHP_EOL_BR.
    'co2 '.$res[0]['co2'].' ppm'.PHP_EOL_BR.
    'created '.$res[0]['created'].PHP_EOL_BR;
} else {
    echo json_encode($response);
}

