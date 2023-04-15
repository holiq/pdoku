<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');
header('Accept: application/json');

require_once 'query.php';

$query = new Query;
$table = 'categories';
$request = $_SERVER['REQUEST_METHOD'];
$json = json_decode(file_get_contents('php://input'));

if ($request == 'GET') {
    $query->get($table);
    if (isset($_GET['id'])) {
        $query->where(['id' => $_GET['id']]);
    }

    $response = $query->exec();
}

if ($request == 'POST') {
    $data = [
        'name' => $json->name,
    ];
    $response = $query->store($table, $data)->exec();
}

if ($request == 'PUT') {
    $data = [
        'name' => $json->name,
    ];
    $response = $query->update($table, $data, $json->id)->exec();
}

if ($request == 'DELETE') {
    $response = $query->delete($table, $json->id)->exec();
}

echo $response;
