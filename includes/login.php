<?php 

$arr = [];
$arr["email"] = $_POST["email"];
$row = db_query("SELECT * FROM users WHERE email = :email LIMIT 1;", $arr);

if(!empty($row)){
    // Check the Password 

    if(password_verify($_POST["password"], $row[0]["password"])) {
        $info["success"] = true;
        $_SESSION["PROFILE"] = $row[0];
    } else {
        $info["errors"]["password"] = "Wrong Email or Password!";
    }

} else {
    $info["errors"]["email"] = "Wrong Email or Password!";
}

