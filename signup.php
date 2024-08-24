<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body>
    
    <form action="" method="POST" onsubmit="myaction.collect_data(event, 'signup')">
        <div class="col-md-7 border rounded mx-auto mt-5 p-4 shadow">
            <div class="h2 mb-4">Sign Up</div>

            <div class="input-group mt-4">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-square"></i></span>
                <input type="text" class="form-control p-2" placeholder="First Name" name="firstname">
            </div>

            <div><small class="js-error js-error-firstname text-danger"></small></div>

            <div class="input-group mt-4">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-circle"></i></span>
                <input type="text" class="form-control p-2" placeholder="Last Name" name="lastname">
            </div>

            <div><small class="js-error js-error-lastname text-danger"></small></div>

            <div class="input-group mt-4">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-envelope"></i></span>
                <input type="text" class="form-control p-2" placeholder="Email" name="email">
            </div>
            <div><small class="js-error js-error-email text-danger"></small></div>

            <div class="input-group mt-4">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-telephone"></i></span>
                <input type="text" class="form-control p-2" placeholder="Tel No." name="phone_no">
            </div>
            <div><small class="js-error js-error-phone text-danger"></small></div>

            <div class="input-group mt-4">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-gender-ambiguous"></i></span>
                <select name="gender" id="" class="form-select">
                    <option value="" selected>-- Select Gender --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div><small class="js-error js-error-gender text-danger"></small></div>

            <div class="input-group mt-4">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-key"></i></span>
                <input type="password" class="form-control p-2" placeholder="Password" name="password">
            </div>

            <div><small class="js-error js-error-password text-danger"></small></div>

            <div class="input-group mt-4">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-key-fill"></i></span>
                <input type="password" class="form-control p-2" placeholder="Confirm Password" name="retype_password">
            </div>

            <div class="progress m-2 d-none">
                <div class="progress-bar" role="progressbar" style="width: 15%">Working..... 25%</div>
            </div>

            <button class="col-12 btn btn-primary p-2 mt-4">Sign Up</button>

            <div class="m-2 text-center">
                Already have an Account? <a href="login.php">Log In Here!</a>
            </div>
        </div>
    </form>
    
</body>
<script>
    const myaction = {
        collect_data: function (e, data_type){
            e.preventDefault();
            e.stopPropagation();
            var inputs = document.querySelectorAll("form input, form select");

            let myform = new FormData();

            myform.append("data_type", data_type)
            for(var i = 0; i < inputs.length; i++){
                myform.append(inputs[i].name, inputs[i].value);
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
                alert("Profile Created Succesfully!");
                window.location.href = "login.php";
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
