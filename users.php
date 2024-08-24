<?php 
    require "functions.php"; 
    if(!is_logged_in()){
        redirect("login.php");
    }

    $rows = db_query("SELECT * FROM users");
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

    <div class="row">
    <?php if(!empty($rows)): ?>
        <?php foreach($rows as $row): ?>
            <div class="col-3 row border rounded mx-auto mt-5 p-1 shadow-lg" style="width:200px;">
                <a href="index.php?id=<?= $row['id'] ?>">
                    <div class="col-md-12 text-center">
                        <img src="<?= get_image($row["image"]); ?>" alt="" class="img-fluid rounded" style="width:180px;height:180px;object-fit:cover;">
                    
                        <div class="my-2">
                            <div><?= esc($row["firstname"]) ?> <?= esc($row["lastname"]) ?></div>
                            <div><?= esc($row["email"]) ?></div>
                            <div><?= esc($row["phone_no"]) ?></div>
                        </div>
                    </div>
                </a>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center alert alert-danger">That Profile was not found !</div>

        <a href="logout.php">
            <button class="btn btn-primary m-4">Home</button>
        </a>
    <?php endif; ?>
    </div>
</body>
</html>

<!--
    Firstname, Lastname, email, gender, password 
-->