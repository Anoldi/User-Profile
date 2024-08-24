<?php 
    require "functions.php"; 
    if(!is_logged_in()){
        redirect("login.php");
    }

    $id = $_GET["id"] ?? $_SESSION["PROFILE"]["id"]; // Putting an or value 

    $row = db_query("SELECT * FROM users WHERE id = :id LIMIT 1", ["id" => $id]);
    if($row){$row = $row[0];}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body>

    <?php if(!empty($row)): ?>
        <div class="row col-md-8 border rounded mx-auto mt-5 p-2 shadow-lg">
            <div class="col-md-4 text-center mt-4">
                <img src="<?= get_image($row["image"]); ?>" alt="" class="img-fluid rounded" style="width:180px;height:180px;object-fit:cover;">
            
                <div class="my-3">
                    <?php if(user("id") == $row["id"]): ?>
                        <a href="profile-edit.php"><button class="mx-auto m-1 btn btn-sm btn-primary">Edit</button></a>
                        <a href="profile-delete.php"><button class="mx-auto m-1 btn btn-sm btn-warning text-white">Delete</button></a>
                        <a href="logout.php"><button class="mx-auto m-1 btn btn-sm btn-info text-white">Log Out</button></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-8">
                <div class="h2">User Profile</div>
                <table class="table table-striped">
                    <tr>
                        <th colspan="2">User Details:</th>
                    </tr>
                    <tr>
                        <th>First Name <i class="bi bi-person-square"></i></th>
                        <td><?= esc($row["firstname"]) ?></td>
                    </tr>
                    <tr>
                        <th>Last Name <i class="bi bi-person-circle"></i></th>
                        <td><?= esc($row["lastname"]) ?></td>
                    </tr>
                    <tr>
                        <th>Email <i class="bi bi-envelope"></i></th>
                        <td><?= esc($row["email"]) ?></td>
                    </tr>
                    <tr>
                        <th>Phone No. <i class="bi bi-telephone"></i></th>
                        <td><?= esc($row["phone_no"]) ?></td>
                    </tr>
                    <tr>
                        <th>Gender <i class="bi bi-gender-ambiguous"></i></th>
                        <td><?= esc($row["gender"]) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="text-center p-3 my-5"> 
            <a href="users.php">All Users</a>
        </div>
    <?php else: ?>
        <div class="text-center alert alert-danger">That Profile was not found !</div>

        <a href="logout.php">
            <button class="btn btn-primary m-4">Home</button>
        </a>
    <?php endif; ?>
</body>
</html>

<!--
    Firstname, Lastname, email, gender, password 
-->