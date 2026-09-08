<?php
session_start();
require_once "../db/connection.php";

header('Content-Type: application/json');

if (!isset($_SESSION["logged_in"]) || !isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized!"]);
    exit();
}

$userId = $_SESSION["user_id"];
$message = isset($_POST["message"]) ? $_POST["message"] : "";

if (empty($message)) {
    echo json_encode(["success" => false, "message" => "Message cannot be empty!"]);
    exit();
}

Database::iud("INSERT INTO `admin_chat` (`user_id`, `message`, `sender`, `status`) VALUES (?, ?, 'user', 'unseen')", "is", [$userId, $message]);

echo json_encode(["success" => true, "message" => "Message sent successfully!"]);