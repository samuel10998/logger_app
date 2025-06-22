<?php
$logDir = getenv("LOG_DIR") ?: "/logs/";
$logFile = $logDir . "/requests.log";

if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}

$logEntry = date("Y-m-d H:i:s") . " " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . "\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

header("Content-Type: application/json");
http_response_code(200);
echo json_encode(["message" => "Request to " . $_SERVER['REQUEST_URI'] . " logged"]);
