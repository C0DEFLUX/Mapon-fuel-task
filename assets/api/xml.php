<?php
//Set a currency to conver to
$currency_type = "EUR";

//XML data
$xml_url = "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
//Converts xml file to object
$xml = simplexml_load_file($xml_url);
//Get currency data from XML file
$currency_xml_data = $xml -> Cube -> Cube -> Cube;
?>
