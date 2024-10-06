<?php
$dataFile = 'heartbeat_data.json';

if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}

if (isset($_GET['server'])) {
    $server = $_GET['server'];
    $timestamp = time();

    $data = json_decode(file_get_contents($dataFile), true);

    if (!isset($data[$server])) {
        $data[$server] = [];
    }

    $data[$server][] = ['time' => $timestamp];

    if (count($data[$server]) > 20) {
        array_shift($data[$server]);
    }

    file_put_contents($dataFile, json_encode($data));
}

header('Content-Type: application/json');
echo file_get_contents($dataFile);
?>
