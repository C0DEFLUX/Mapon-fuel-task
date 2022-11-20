<?php
session_start();
$err_msg = "";

try {
    if(isset($_POST['submit'])){
       require_once "assets/api/db.php";
       //Checks if input fiels are empty
       //Throws an error if the fields are empty, else proceeds
       if(empty($_POST["username"]) || empty($_POST["password"])) {
            $err_msg = "<p>All fields are required!</p>";
       }else {
            //Gets submited value
            $username = $_POST["username"];
            //Checks for special charaters
            $verify_username = preg_replace("/[^\w]+/", "",$_POST["username"]);
            //If there are special charaters. Out put an error message
            if($verify_username !==  $username) {
                $err_msg = "<p>Can't use special characters!</p>";
            }else {
                //Hashes the password
                $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                
                //Check if username already exists in database
                $sql = "SELECT count(username) AS num FROM mapon_user WHERE username = :username";
                $check_user = $conn -> prepare($sql);
    
                $check_user -> bindValue(':username', $username);
                $check_user -> execute();
                $row = $check_user -> fetch(PDO::FETCH_ASSOC);
                //If the returned value from fetch is greater than 0 it returns an error
                //Meaning that a user exists with the same name
                if($row['num'] > 0) {
                    $err_msg = "<p>Username already exists!</p>";
                }else {
                    //Inserts new user data
                    $register_user = $conn -> prepare(
                    "INSERT INTO mapon_user(
                        username, 
                        password
                    )VALUES(
                        :username, 
                        :password
                    )");
                    $register_user -> bindParam(':username', $username);
                    $register_user -> bindParam(':password', $password);
                    //If everything is succesful returns you to login page
                    //Else throws an error
                    if($register_user -> execute()) {
                        header("location: login.php");
                    }else {
                        $err_msg = "<p>An error occured!</p>";
                    }
                }
            }
        }
    }
}catch (PDOException $error) {
    $err_msg = $error -> getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <title>Document</title>
</head>
<body>
    <main>
        <article class="login-section h-v middle col">
            <div class="login-box__wrapper flex col">
                <div class="task-title-box flex start w-v">
                    <div class="task-title">
                        <h1>Mapon fuel task</h1>
                    </div>
                </div>
                <div class="login-box flex">
                    <div class="login-form">
                        <form class="flex col" method="POST">  
                        <h1>REGISTER</h1>
                            <div class="input-box flex col">

                                <label>Username</label>
                                <?php
                                if(isset($err_msg)) {
                                    echo "<p data-aos='zoom-in' class='error-msg'>".$err_msg."</p>";
                                }
                                ?>
                                <input type="text" name="username">
                            </div>
                            <div class="input-box flex col">
                                <label>Password</label>
                                <?php
                                if(isset($err_msg)) {
                                    echo "<p data-aos='zoom-in' class='error-msg'>".$err_msg."</p>";
                                }
                                ?>
                                <input type="password" name="password">
                            </div>
                            <a href="login.php">Have an account?</a>
                            <div class="center">
                                <button class="login-btn" name="submit">Register</button>
                            </div>
                        </form>
                    </div>
                    <div class="login-img">
                    </div>
                </div>
                <div class="task-title-box flex end w-v">
                    <div class="task-title">
                        <h1>Made by Niks Kļaviņš IP20</h1>
                    </div>
                </div>
            </div>
        </article>
    </main>