<?php 

// Make sure the user has the wright access

if(!is_logged_in() || user("id") != $_POST["id"]){
    $info["errors"]["Anauthorized-Access"] = "You don't have permission to delete this profile!";
}


if(empty($info["errors"]) && $row){
    // Delete data from the database 

    $arr = [];
    $arr["id"] = $id;
    db_query("DELETE FROM users WHERE id = :id LIMIT 1;", $arr);

    if(file_exists($row["image"])){
        unlink($row["image"]);
    }

    $info["success"] = true;
}
