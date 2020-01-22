<?php

namespace App;

require 'vendor/autoload.php';

use App\Entity\Measurement;
use App\Helper\DatabaseManager;
use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\Connection;
use BinSoul\Net\Mqtt\DefaultSubscription;
use BinSoul\Net\Mqtt\Message;
use BinSoul\Net\Mqtt\Subscription;
use React\Socket\DnsConnector;
use React\Socket\TcpConnector;

$server = '178.62.215.91';

$query = function (Measurement $measurement) {
    $manager = DatabaseManager::getManager();
    $repo = new Repository\RecordRepository();
    $manager->query($repo->getInsertQuery(), [$measurement->temperature, $measurement->humidity, $measurement->pressure, $measurement->co2, $measurement->getCreated(), $measurement->topic]);
};

// Setup client
$loop = \React\EventLoop\Factory::create();
$dnsResolverFactory = new \React\Dns\Resolver\Factory();
$connector = new DnsConnector(new TcpConnector($loop), $dnsResolverFactory->createCached('8.8.8.8', $loop));
$client = new ReactMqttClient($connector, $loop);

// Bind to events
$client->on('open', function () use ($client) {
    // Network connection established
    echo sprintf("Open: %s:%d\n", $client->getHost(), $client->getPort());
});

$client->on('close', function () use ($client, $loop) {
    // Network connection closed
    echo sprintf("Close: %s:%d\n", $client->getHost(), $client->getPort());
    $loop->stop();
});

$client->on('connect', function (Connection $connection) {
    // Broker connected
    echo sprintf("Connect: client=%s\n", $connection->getClientID());
});

$client->on('disconnect', function (Connection $connection) {
    // Broker disconnected
    echo sprintf("Disconnect: client=%s\n", $connection->getClientID());
});

$client->on('message', function (Message $message) use ($query) {
    // Incoming message
    echo 'Message';

    if ($message->isDuplicate()) {
        echo ' (duplicate)';
    }

    if ($message->isRetained()) {
        echo ' (retained)';
    }


    $measurement = new Measurement(array_merge(json_decode($message->getPayload(), true), ['topic' => $message->getTopic()]));
//    $measurement['created'] = (new DateTime())->format('Y-m-d h:i:s');
//    var_dump($measurement);exit;
    $query($measurement);

//    echo ': '.$message->getTopic().' => '.mb_strimwidth($message->getPayload(), 0, 50, '...');
    echo " saved \n";

//    $size = memory_get_usage(true);
//    $unit=array('b','kb','mb','gb','tb','pb');
//    $size = round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
//    echo $size.PHP_EOL;
});

$client->on('warning', function (\Exception $e) {
    echo sprintf("Warning: %s\n", $e->getMessage());
});

$client->on('error', function (\Exception $e) use ($loop) {
    echo sprintf("Error: %s\n", $e->getMessage());
    $loop->stop();
});

// Connect to broker
$client->connect($server)->then(
    function () use ($client) {
        // Subscribe to all topics
        $client->subscribe(new DefaultSubscription('#'))
            ->then(function (Subscription $subscription) {
                echo sprintf("Subscribe: %s\n", $subscription->getFilter());
            })
            ->otherwise(function (\Exception $e) {
                echo sprintf("Error: %s\n", $e->getMessage());
            });

//        // Publish humidity once
//        $client->publish(new DefaultMessage('sensors/humidity', '55%'))
//            ->then(function (Message $message) {
//                echo sprintf("Publish: %s => %s\n", $message->getTopic(), $message->getPayload());
//            })
//            ->otherwise(function (\Exception $e) {
//                echo sprintf("Error: %s\n", $e->getMessage());
//            });
//
//        // Publish a random temperature every 10 seconds
//        $generator = function () {
//            return mt_rand(-20, 30);
//        };
//        $client->publishPeriodically(10, new DefaultMessage('sensors/temperature'), $generator)
//            ->progress(function (Message $message) {
//                echo sprintf("Publish: %s => %s\n", $message->getTopic(), $message->getPayload());
//            })
//            ->otherwise(function (\Exception $e) {
//                echo sprintf("Error: %s\n", $e->getMessage());
//            });
    }
);

$loop->run();