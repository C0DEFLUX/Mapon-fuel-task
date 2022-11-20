<?php
session_start();
if(isset($_SESSION["username"])) {
    $id = $_SESSION["id"];    
}else {
    header("location:../../login.php");
}
require_once "db.php";
require_once "xml.php";

//All fuel type array
$fuel_type = [
    "Diesel",
    "E95",
    "E98",
    "Electricity",
    "CNG",
    "Extra premium CNG",
    "Premium Diesel",
    "BIOCNG",
    "Super Plus 98",
    "95 Miles",
    "98E0 milesPLUS",
    "D Miles",
    "FUTURA 95",
];

//Post file data from form. 
//Puts CSV file rows into arrays for further use.
if(isset($_POST["submit"])){
    $fileName = $_FILES["file"]["tmp_name"];
    $csvFile = file($fileName);
    $all_data = [];
    foreach ($csvFile as $line) {
        $all_data[] = str_getcsv($line);
    }
}

//Bellow is the code for the API section of the task
//Not used becouse of preformance issues

// $unit_ids = json_decode(file_get_contents("https://mapon.com/api/v1/unit/list.json?key=f94e281f9eff169647620454a2f62839524452a8&car_number=ML-8196"), true);
// $unit_id = $unit_ids["data"]["units"][0]["unit_id"];

// $unit_data = json_decode(file_get_contents("https://mapon.com/api/v1/unit_data/history_point.json?key=f94e281f9eff169647620454a2f62839524452a8&unit_id=".$unit_id."&datetime=2022-04-17T13:50:20Z&include[]=can_total_distance&include[]=mileage&include[]=position"), true);


// // print_r($unit_data);
// $can_data = $unit_data["data"]["units"][0]["can_total_distance"]["value"];
// $gps_data = $unit_data["data"]["units"][0]["mileage"]["value"];
// $position = $unit_data["data"]["units"][0]["position"]["value"];
// $loc = [
//     'car_pos' => [$position['lat'], $position['lng']],
// ];

// $car_loc_lat = $loc['car_pos'][0];
// $car_loc_lng = $loc['car_pos'][1];

// echo $can_data;
// echo $gps_data;
// echo $car_loc_lat;
// echo $car_loc_lng;

//Loops thru all fuel types
foreach($fuel_type as $fuel) {
    //Loops thru all data rows
    foreach($all_data as $data) {
        //Checks if any fuel names from fuel_type array matches any data from CSV file.
        if(in_array($fuel, $data)) {
            //Checks the 7th column (Currenecy type) for any values that don't match the set currency type which is EUR.
            if ($data[7] !== $currency_type) {
                //If there are not matching currency, It loops thru currency conversion XML file.
                //About the XML file in xml.php
                foreach($currency_xml_data as $xml_data) {
                    //Checks if there are matching currency.
                    //Changes found currency which is not EUR to EUR and makes the total sum to euro.
                    if($data[7] == $xml_data["currency"]){
                       $data[7] = $currency_type;
                       $data[6] = ($data[6]/$xml_data["rate"]);
                    }
                }
            }
            //Set time zone
            $my_time_zone = "Europe/Riga";
            $time_arr =[];
            //Set all country ISO
            $timezone_identifiers = DateTimeZone::listIdentifiers( DateTimeZone::PER_COUNTRY, "$data[9]");
            //Loop thru all the ISO and put them in an array
            foreach( $timezone_identifiers as $identifier ) {
                array_push($time_arr, $identifier);
            }
            //Set a new date with the first timezone from the time array
            $date = new DateTime("$data[1]", new DateTimeZone($time_arr[0]));
            //Sets time zone to my time zone
            $date->setTimezone(new DateTimeZone("$my_time_zone"));
            //Formats time to set format
            $new_date = $date->format("H:i:s");
            //Sets all dates to new formated dates
            $data[1] = $new_date;
                //Inserts all of the data into database
                try {
                    $sql = "INSERT INTO mapon_fuel (
                    fuel_date, 
                    fuel_time, 
                    fuel_card_nr, 
                    fuel_vehicle_nr, 
                    fuel_product, 
                    fuel_amount, 
                    fuel_total_sum,
                    fuel_currency,
                    fuel_country,
                    fuel_country_iso,
                    fuel_station,
                    user_id
                    )VALUES (
                    '$data[0]', 
                    '$data[1]', 
                    '$data[2]', 
                    '$data[3]', 
                    '$data[4]', 
                    '$data[5]', 
                    '$data[6]', 
                    '$data[7]', 
                    '$data[8]', 
                    '$data[9]', 
                    '$data[10]' ,
                     $id
                    )";
                    //If everything worked return to index.php
                    $conn->exec($sql);
                    header("Location: ../../index.php");
                    //Else throw an error
                  } catch(PDOException $e) {
                        echo $sql . "<br>" . $e->getMessage();
                  }
            }
        }
    }

?>
