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
    <title>Edit Profile</title>

    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body>
    <div class="row col-md-8 border rounded mx-auto mt-5 p-2 shadow-lg">
        <div class="col-md-4 text-center mt-5">
            <img src="<?= get_image($row["image"]); ?>" alt="" class="js-image img-fluid rounded" style="width:180px;height:180px;object-fit:cover;">
        
            <div class="mt-4">
                <label for="formFile" class="form-label">Click below to select an image</label>
                <input onchange="display_image(this.files[0])" name="image" class="js-image-input form-control" type="file" id="formFile">
            </div>

            <div><small class="js-error js-error-image text-danger"></small></div>
        </div>

        <div class="col-md-8">
            <div class="h2">Edit Profile</div>

            <?php if(!empty($row)): ?>
                <form action="" method="POST" onsubmit="myaction.collect_data(event, 'profile-edit')">
                    <table class="table table-striped">
                        <tr>
                            <th colspan="2">User Details:</th>
                        </tr>
                        <tr>
                            <th>First Name <i class="bi bi-person-square"></i></th>
                            <td>
                                <input type="text" name="firstname"  class="form-control" value="<?= $row['firstname'] ?>">
                                <div><small class="js-error js-error-firstname text-danger"></small></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Last Name <i class="bi bi-person-circle"></i></th>
                            <td>
                                <input type="text" name="lastname"  class="form-control" value="<?= $row['lastname'] ?>">
                                <div><small class="js-error js-error-lastname text-danger"></small></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Email <i class="bi bi-envelope"></i></th>
                            <td>
                                <input type="text" name="email"  class="form-control" value="<?= $row['email'] ?>">
                                <div><small class="js-error js-error-email text-danger"></small></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Phone No. <i class="bi bi-telephone"></i></th>
                            <td>
                                <input type="text" name="phone_no"  class="form-control" value="<?= $row['phone_no'] ?>">
                                <div><small class="js-error js-error-phone text-danger"></small></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Gender <i class="bi bi-gender-ambiguous"></i></th>
                            <td>
                                <select name="gender" class="form-select form-select mb-3" aria-label=".form-select-lg example">
                                    <option value="<?= $row['gender'] ?>" selected>-- <?= esc($row['gender']) ?> --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <div><small class="js-error js-error-gender text-danger"></small></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Password <i class="bi bi-key"></i></th>
                            <td>
                                <input type="password" name="password"  class="form-control" placeholder="Password (Leave empty to keep old password)">
                                <div><small class="js-error js-error-password text-danger"></small></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Confirm Password <i class="bi bi-key-fill"></i></th>
                            <td>
                                <input type="password" name="retype_password"  class="form-control" placeholder="Confirm Password">
                            </td>
                        </tr>
                    </table>

                    <div class="progress my-2 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 15%">Working..... 25%</div>
                    </div>

                    <div class="p-2">
                        <button class="btn btn-primary float-end">Save</button>
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
    var image_added = false;

    function display_image(file){
        var img = document.querySelector(".js-image");
        img.src = URL.createObjectURL(file);
        image_added = true; 
    }

    const myaction = {
        collect_data: function (e, data_type){
            e.preventDefault();
            e.stopPropagation();
            var inputs = document.querySelectorAll("form input, form select");

            let myform = new FormData();
            myform.append("data_type", data_type);

            for(var i = 0; i < inputs.length; i++){
                myform.append(inputs[i].name, inputs[i].value);
            }

            if(image_added){
                console.log(document.querySelector(".js-image-input").files[0]);
                myform.append("image", document.querySelector(".js-image-input").files[0]);
            }

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
                alert("Profile Edited Succesfully!");
                window.location.href = "index.php";
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