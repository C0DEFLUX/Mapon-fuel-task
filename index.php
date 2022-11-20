<?php
//Checks for a session
//Else returns to login
session_start();
if(isset($_SESSION["username"])) {
    $id = $_SESSION["id"];    
}else {
    header("location:login.php");
}
require_once "assets/api/db.php";

//Check the search bar input
//If empty Selects all data
if(empty($_POST["keyword"])) {
    $all_data = $conn -> query("SELECT * FROM mapon_fuel WHERE user_id = '$id'") -> fetchAll();
}else {
    //If not empty POST the keyword form input and puts it in the Query
    $keyword = $_POST["keyword"];
    $all_data = $conn -> query("SELECT * FROM mapon_fuel WHERE user_id = '$id' AND fuel_product LIKE '%$keyword%' OR fuel_card_nr LIKE '%$keyword%' OR fuel_vehicle_nr LIKE '%$keyword%' OR fuel_date LIKE '%$keyword%'") -> fetchAll();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <title>Document</title>
</head>
<body>
    <div class="home__wrapper h-v flex col" >
        <header class="header w-v flex middle">
                <div class="username-box">
                    <?php echo "<h1 class='username'>Welcome, ".$_SESSION['username']."</h1>" ?>
                </div>
            <div class="logout-box flex end">
                <a href="assets/api/logout.php">
                    Logout
                </a>
            </div>
        </header>
        <main class="home-section">
            <article class="middle col">
                <div class="data-nav flex">
                    <div class="data-nav-upload start">
                        <h1>Data table</h1>
                    </div>
                    <div class="search-bar-box flex end">
                        <div class="search-bar-wrapper flex">
                            <div class="summary-box">
                                <div class="summary-btn middle">
                                    <a href="summary.php">
                                        Summary
                                    </a>
                                </div>
                            </div>
                            <div class="flex">
                                <form class="search-bar" method="POST">
                                    <input type="text" name="keyword" placeholder="Search">
                                    <button name="search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                                <div class="upload-btn">
                                    <button onclick="showPopUp()" id="show-upload">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="upload-form-box" class="upload-form-box">

                            <form class="upload-form flex col" method="POST" action="assets/api/data.php" enctype="multipart/form-data">
                                <p onclick="hidePopUp()">
                                    <i class="fas fa-times" id="hide-upload"></i>
                                </p>
                                <label>Upload CSV file</label>
                                <div class="flex col">
                                    <label class="custom-file-upload">
                                        <input type="file" name="file">
                                        <i class="fas fa-upload"></i> Choose File
                                    </label>
                                    <button name="submit">Upload</buttom>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="data-box">
                    <table class="data-table">
                        <tr class="data-th">
                            <th>Date</th>
                            <th>Time</th>
                            <th>Card Nr.</th>
                            <th>Vehicle Nr.</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Total sum</th>
                            <th>Cost per liter/weight/unit</th>
                            <th>Currency</th>
                            <th>Country</th>
                            <th>Country ISO</th>
                            <th>Fuel station</th>
                        </tr>
                        </tr>
                            <?php
                            //Goes thru all data
                            foreach ($all_data as $data) {
                                echo "<tr class='data-rows'>";
                                echo "<td>" . $data['fuel_date'] . " </td>" ;
                                echo "<td>" . $data['fuel_time'] . " </td>" ;
                                echo "<td>" . $data['fuel_card_nr'] . " </td>" ;
                                echo "<td>" . $data['fuel_vehicle_nr'] . " </td>" ;
                                echo "<td>" . $data['fuel_product'] . " </td>" ;
                                echo "<td>" . $data['fuel_amount'] . " </td>" ;
                                //Formats float values to have 0,00
                                echo "<td>" . number_format($data['fuel_total_sum'], 2, ',', ' ') . " </td>";
                                //Calculate the Cost per liter/weight/unit
                                $cost_per = $data['fuel_total_sum'] / $data['fuel_amount'];
                                echo "<td>" . number_format($cost_per, 2, ',', ' ') . " </td>";
                                echo "<td>" . $data['fuel_currency'] . " </td>" ;
                                echo "<td>" . $data['fuel_country'] . " </td>" ;
                                echo "<td>" . $data['fuel_country_iso'] . " </td>" ;
                                echo "<td>" . $data['fuel_station'] . " </td>" ;
                                echo "</tr>";
                            }
                            ?>
                    </table>
                </div>
            </article>
        </main>
    </div>
<script src="assets/scripts.js"></script>
</body>
</html>