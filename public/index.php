<?php

require '../vendor/autoload.php';

use App\Helper\DatabaseManager;

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

echo json_encode($response);
