<?php
$logDir = getenv("LOG_DIR") ?: "/logs/";
$logFileName = getenv("LOG_FILENAME") ?: "requests.log";
$mode = getenv("MODE") ?: "verbose";
$logFile = $logDir . "/" . $logFileName;

// Zápis do logu
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$logEntry = date("Y-m-d H:i:s") . " " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . "\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

// Odpoveď
header("Content-Type: application/json");

if ($mode === "verbose") {
    echo json_encode(["message" => "Request to " . $_SERVER['REQUEST_URI'] . " logged"]);
} else {
    echo json_encode(["ok" => true]);
}
