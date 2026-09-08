<?php
session_start();
require_once "../db/connection.php";

header('Content-Type: application/json');

if (!isset($_SESSION["admin_logged_in"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$userId = isset($_POST["user_id"]) ? intval($_POST["user_id"]) : 0;
$message = isset($_POST["message"]) ? $_POST["message"] : "";

if ($userId <= 0 || empty($message)) {
    echo json_encode(["success" => false, "message" => "Invalid Data!"]);
    exit();
}

Database::iud("INSERT INTO `admin_chat` (`user_id`, `message`, `sender`, `status`) VALUES (?, ?, 'admin', 'unseen')", "is", [$userId, $message]);

echo json_encode(["success" => true, "message" => "Message sent!"]);
