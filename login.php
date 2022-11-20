<?php
session_start();
$err_msg = "";

try {
    require_once "assets/api/db.php";

    if(isset($_POST["submit"])) {
        //Checks if input fiels are empty
       //Throws an error if the fields are empty, else proceeds
        if(empty($_POST["username"]) || empty($_POST["password"])) {
            $err_msg = "<p>All fields are required!</p>";
        }else {
            //Posts the username input
            $username = $_POST["username"];
            //Checks the username input
            //Finds password with the submited username
            $username_data = "SELECT password FROM mapon_user WHERE username = :username";
            $check_username = $conn -> prepare($username_data);
            $check_username -> bindValue(':username', $username);
            
            $check_username -> execute();

            $password = $check_username -> fetch(PDO::FETCH_ASSOC);
            //Verifying the password, as it's hashed for better safety
            //If the passwords are the same, moves on. Else throws an error
            if(password_verify($_POST["password"], $password["password"])) {
                $sql = "SELECT * FROM mapon_user WHERE username = :username AND password = :password";
                $get_data = $conn -> prepare($sql);
                $get_data -> execute(
                    array (
                        ":username" => $_POST["username"],
                        ":password" => $password["password"]
                    )
                );
                //Gets the the id of the user for further use
                foreach($get_data as $data) {
                    $id = $data["id"];
                }
                //Counts the rows of data
                $count_data_rows = $get_data -> rowCount();
                //If the data rows ar more than 0 moves on
                //Else throws a error
                if($count_data_rows > 0) {
                    //Sets the $_SESSION username and id
                    $_SESSION["username"] = $_POST["username"];
                    $_SESSION['id'] = $id;
                    //Moves to index file
                    header("location:index.php");
                }else {
                    $err_msg = "<p>Wrong password or username!</p>";
                }
            }else {
                $err_msg = "<p>Wrong password or username!</p>";
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
                        <h1>LOGIN</h1>

                            <div class="input-box flex col">
                                <label>Username</label>
                                <?php
                                if(isset($err_msg)) {
                                    echo "<p class='error-msg'>".$err_msg."</p>";
                                }
                                ?>
                                <input type="text" name="username">
                            </div>
                            <div class="input-box flex col">
                                <label>Password</label>
                                <?php
                                if(isset($err_msg)) {
                                    echo "<p class='error-msg'>".$err_msg."</p>";
                                }
                                ?>
                                <input type="password" name="password">
                            </div>
                            <a href="register.php">Don't have an account?</a>
                            <div class="center">
                                <button class="login-btn" name="submit">Login</button>
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
    <script>
    AOS.init();
    </script>
</body>
</html>