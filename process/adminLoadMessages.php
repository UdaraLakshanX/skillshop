<?php
session_start();
require_once "../db/connection.php";

header('Content-Type: application/json');

if (!isset($_SESSION["admin_logged_in"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$action = isset($_GET["action"]) ? $_GET["action"] : "";

if ($action == "getUsers") {

    $query = "SELECT u.`id`, u.`fname`, u.`lname`, 
              (SELECT `message` FROM `admin_chat` WHERE `user_id` = u.`id` ORDER BY `created_at` DESC LIMIT 1) as `last_message`,
              (SELECT `created_at` FROM `admin_chat` WHERE `user_id`=u.`id` ORDER BY `created_at` DESC LIMIT 1) as `last_time`,
              (SELECT COUNT(id) FROM `admin_chat` WHERE `user_id` = u.`id` AND `sender` = 'user' AND `status` = 'unseen') as `unseen_count`
              FROM `user` u 
              WHERE u.`id` IN (SELECT DISTINCT `user_id` FROM `admin_chat`)
              ORDER BY `last_time` DESC";
    $result = Database::search($query);
    
    $users = [];
    if($result){
        while ($row = $result->fetch_assoc()) {
            $row['initials'] = strtoupper(substr($row['fname'],0,1) . substr($row['lname'],0,1));
            $users[] = $row;
        }
    }
    echo json_encode(["success" => true, "users" => $users]);

} else if ($action == "getChat") {
    $userId = isset($_GET["user_id"]) ? intval($_GET["user_id"]) : 0;
    if ($userId <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid User ID"]);
        exit();
    }

    // Mark as seen for admin
    Database::iud("UPDATE `admin_chat` SET `status` = 'seen' WHERE `user_id` = ? AND `sender` = 'user'", "i", [$userId]);

    $query = "SELECT * FROM `admin_chat` WHERE `user_id` = ? ORDER BY `created_at` ASC";
    $result = Database::search($query, "i", [$userId]);
    
    $chats = [];
    if($result){
        while ($row = $result->fetch_assoc()) {
            $chats[] = $row;
        }
    }
    
    // Get user details
    $userRes = Database::search("SELECT `fname`, `lname` FROM `user` WHERE `id` = ?", "i", [$userId]);
    $userDetails = $userRes ? $userRes->fetch_assoc() : ["fname" => "Unknown", "lname" => "User"];
    
    echo json_encode(["success" => true, "chats" => $chats, "user" => $userDetails]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid Action!"]);
}
