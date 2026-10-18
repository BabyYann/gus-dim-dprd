<?php

require 'backend/vendor/autoload.php';
$app = require_once 'backend/bootstrap/app.php';

try {
    $request = Illuminate\Http\Request::create('/', 'GET');
    $response = $app->handleRequest($request);
    echo "STATUS CODE: " . $response->getStatusCode() . "\n";
    echo "CONTENT PREVIEW: " . substr($response->getContent(), 0, 300) . "\n";
} catch (Throwable $e) {
    echo "EXCEPTION: " . get_class($e) . "\n";
    echo "MESSAGE: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "TRACE:\n" . $e->getTraceAsString() . "\n";
}
