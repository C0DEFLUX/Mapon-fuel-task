<?php
$db = parse_ini_file("config.ini", true);


try {
    $conn = new PDO("mysql:host=".$db['DB']['server'].";dbname=".$db['DB']['dbname'], $db['DB']['username'], $db['DB']['password']);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }catch(PDOException $e) {
    echo "Connection failed: " . $e -> getMessage();
  }

?>