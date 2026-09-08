<?php

require "../db/connection.php";

$email = $_POST["email"];
$action = $_POST["action"];

if($action == "verify"){

    $code = $_POST["code"];

    if(empty($code)){
        echo "Verification code is required.";
    } else {
        $result = Database::search("SELECT `id` FROM `user` WHERE `email`=?", "s", [$email]);

        if(!$result && $result->num_rows == 0){
            echo "User not found!";
        } else {
            $user = $result->fetch_assoc();

            $codeResult = Database::search(
                "SELECT `token_hash`,`expiry` FROM `password_reset_tokens` WHERE `user_id`=? ORDER BY `created_at` DESC LIMIT 1",
                "i",
                [$user["id"]]
            );

            if(!$codeResult || $codeResult->num_rows == 0){
                echo "No code requested.";
            } else {
                $codeRecord = $codeResult->fetch_assoc();
                $expiry = strtotime($codeRecord["expiry"]);
                $now = time();

                if($now > $expiry){
                    echo "Code expired";
                } else if (password_verify($code,$codeRecord["token_hash"])){
                    echo "success";
                } else {
                    echo "Invalid code.";
                }
            }
        }
    }

} else if ($action == "reset"){
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    if(empty($password)){
        echo "Please enter the password.";
    } else if($password != $cpassword){
        echo "Password do no match.";
    }  else if(strlen($password) < 8){
        echo "Password must be 8+ characters.";
    } else {

        $result = Database::search("SELECT `id` FROM `user` WHERE `email`=?", "s", [$email]);

        if($result || $result->num_rows > 0){
            $user = $result->fetch_assoc();

            Database::iud(
                "UPDATE `user` SET `password_hash`=? WHERE `id`=?",
                "si", [password_hash($password,PASSWORD_DEFAULT),$user["id"]]
            );
            Database::iud("DELETE FROM `password_reset_tokens` WHERE `user_id`=?","i",[$user["id"]]);
            echo "success";

        } else {
            echo "User not found!";
        }
    }
} else {
    echo "Invalid action";
}

?>