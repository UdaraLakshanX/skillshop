<?php

session_start();
require_once "../db/connection.php";

header('Content-Type: application/json');

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$userId = $_SESSION["user_id"];
$pid = isset($_POST["pid"]) ? intval($_POST["pid"]) : 0;
$rating = isset($_POST["rating"]) ? intval($_POST["rating"]) : 5;
$message = isset($_POST["message"]) ? $_POST["message"] : "";

if ($pid <= 0 || empty($message)) {
    echo json_encode(["success" => false, "message" => "Invalid feedback data!"]);
    exit();
}

// Verfiy user actully puchased the product
$puchaseCheck = Database::search(
    "SELECT ii.`id` FROM `invoice_item` ii 
    JOIN `invoice` i ON ii.`invoice_id`=i.`id`
    WHERE i.`user_id`=? AND ii.`product_id`=? LIMIT 1",
    "ii",[$userId,$pid]
);

if (!$puchaseCheck || $puchaseCheck->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "You can only give feedback on products you puchased!"]);
    exit();
}

// Check feedback for same product
$exisiting = Database::search("SELECT `id` FROM `feedback` WHERE `user_id`=? AND `product_id`=? LIMIT 1","ii",[$userId,$pid]);
if ($exisiting && $exisiting->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Feedback already provided for this product!"]);
    exit();
}

// Save
$date =  date("Y-m-d H:i:s");
$res = Database::iud(
    "INSERT INTO `feedback` (`user_id`, `product_id`, `rating`, `message`, `created_at`) VALUES(?,?,?,?,?)",
    "iiiss",
    [$userId,$pid,$rating,$message,$date]
);

if($res){
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to save feedback to database!"]);
}

?>