<?php
session_start();
if(isset($_SESSION["username"])) {
    $id = $_SESSION["id"];    
}else {
    header("location:../../login.php");
}
require_once "assets/api/db.php";
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
    <main>
        <article class="summary-section middle">
            <div class="summary-box">
                <a href="index.php" class="summary-back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <table>
                    <tr class="header-row">
                        <th>Fuel type</th>
                        <th>Amount</th>
                        <th>Cost</th>
                    </tr>
                <?php
                try {
                    $fuel_types = $conn -> prepare("SELECT DISTINCT fuel_product FROM mapon_fuel WHERE user_id = '$id'");
                    $fuel_types -> execute();
                    $count_fuel = $fuel_types -> rowCount();
                    if($count_fuel > 0) {
                        $total_sum = $conn -> prepare("SELECT sum(fuel_amount) AS fuel, sum(fuel_total_sum) AS price FROM mapon_fuel WHERE user_id = '$id'");
                        $total_sum ->execute();
                        foreach($fuel_types as $fuel_type){
                            $fuel_type1 = $fuel_type['fuel_product'];
                            $sum  = $conn -> prepare("SELECT sum(fuel_amount) AS fuel, sum(fuel_total_sum) AS price FROM mapon_fuel WHERE fuel_product = '$fuel_type1'");
                            $sum -> execute();
                            foreach($sum as $fuel) {
                                echo "<tr>";
                                echo "<td>" . $fuel_type1 . "</td>";
                                echo "<td>" . $fuel["fuel"] . "</td>";
                                echo "<td>" . number_format($fuel["price"], 2, ',', ' '). "</td>";
                                echo "</tr>";
                            }
                        }
                        foreach($total_sum as $sum) {
                            echo "<tr>";
                            echo "<td class='total_row'>Total</td>";
                            echo "<td class='total_row'>" . $sum["fuel"] . "</td>";
                            echo "<td class='total_row'>" . number_format($sum["price"], 2, ',', ' ') . "</td>";
                            echo "</tr>";
                        }
                    }
                }catch(PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage();
                }
                ?>
                </table>
            </div>
        </article>
    </main>
</body>
</html>
