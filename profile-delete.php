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
<lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Profile</title>

    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body>
    <div class="row col-md-8 border rounded mx-auto mt-5 p-2 shadow-lg">
        <div class="col-md-4 text-center mt-5">
            <img src="<?= get_image($row["image"]); ?>" alt="" class="js-image img-fluid rounded" style="width:180px;height:180px;object-fit:cover;">
        </div>

        <div class="col-md-8">
            <div class="h2">Delete Profile</div>

            <div class="alert-danger alert text-center my-2">Are you sure you want to delete your profile?!</div>
            
            <?php if(!empty($row)): ?>
                <form action="" method="POST" onsubmit="myaction.collect_data(event, 'profile-delete')">
                    <table class="table table-striped">
                        <tr>
                            <th colspan="2">User Details:</th>
                        </tr>
                        <tr>
                            <th>First Name <i class="bi bi-person-square"></i></th>
                            <td>
                                <div class="form-control"><?= $row['firstname']; ?></div>
                                <div><small class="js-error js-error-firstname text-danger"></small></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Last Name <i class="bi bi-person-circle"></i></th>
                            <td>
                                <div class="form-control"><?= $row['lastname']; ?></div>
                                <div><small class="js-error js-error-lastname text-danger"></small></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Email <i class="bi bi-envelope"></i></th>
                            <td>
                                <div class="form-control"><?= $row['email']; ?></div>
                                <div><small class="js-error js-error-email text-danger"></small></div>
                            </td>
                        </tr>
                    </table>

                    <div class="progress my-2 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 15%">Working..... 25%</div>
                    </div>

                    <div><small class="js-error js-error-Anauthorized-Access text-danger"></small></div>

                    <div class="p-2">
                        <button class="btn btn-danger float-end">Delete</button>
                        <a href="index.php"><label class="btn btn-secondary">Back</label></a>
                    </div>
                </form>

            <?php else: ?>
                <div class="text-center alert alert-danger">That Profile was not found !</div>

                <a href="logout.php">
                    <button class="btn btn-primary m-4">Home</button>
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
<script>
    const myaction = {
        collect_data: function (e, data_type){
            e.preventDefault();
            e.stopPropagation();

            let myform = new FormData();
            myform.append("data_type", data_type);
            myform.append("id", <?= $row["id"] ?? 0; ?>);

            myaction.send_data(myform);
        },

        send_data: function (form){
            var ajax = new XMLHttpRequest();
            
            document.querySelector(".progress").classList.remove("d-none");
            document.querySelector(".progress-bar").style.width = "0%";
            document.querySelector(".progress-bar").innerHTML = "Working..... 0%";

            ajax.addEventListener("readystatechange", function(){
                if(ajax.readyState == 4){
                    if(ajax.status == 200){
                        myaction.handle_result(ajax.responseText);
                    } else {
                        console.log(ajax);
                        alert("An error occured!");
                    }
                }
            });

            ajax.upload.addEventListener("progress", function(e){
                let percent = Math.round((e.loaded / e.total) * 100);
                document.querySelector(".progress-bar").style.width = percent + "%";
                document.querySelector(".progress-bar").innerHTML = "Working..... " + percent + "%";
            });

            ajax.open("post", "ajax.php", true);
            ajax.send(form);
        }, 

        handle_result: function(result){
            var obj = JSON.parse(result);
            
            if(obj.success){
                alert("Profile Deleted Succesfully!");
                window.location.href = "logout.php";
                // window.location.reload();
            } else {
                // Show errors 
                let error_inputs = document.querySelectorAll(".js-error");

                for (var i = 0; i < error_inputs.length; i++) {
                    error_inputs[i].innerHTML = "";
                }

                for(key in obj.errors){
                    document.querySelector(".js-error-" + key).innerHTML = obj.errors[key];
                }
            }
        }
    };
</script>
</html>