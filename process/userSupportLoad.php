<?php
session_start();
require_once "../db/connection.php";

header('Content-Type: application/json');

if (!isset($_SESSION["logged_in"]) || !isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized!"]);
    exit();
}

$userId = $_SESSION["user_id"];

Database::iud("UPDATE `admin_chat` SET `status`='seen' WHERE `user_id`=? AND `sender`='admin'", "i", [$userId]);

$query = "SELECT * FROM `admin_chat` WHERE `user_id`=? ORDER BY `created_at` ASC";
$result = Database::search($query, "i", [$userId]);

$chats = [];
if($result){
    while($row = $result->fetch_assoc()){
        $chats[] = $row;
    }
}

echo json_encode(["success" => true, "chats" => $chats]);