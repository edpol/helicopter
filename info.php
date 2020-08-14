<?php
namespace Takeflite;

/*
 *  so now the user needs to input some ContactDetail, PassengerListItems, PaymentDetails information
 *  then we can call OTA_PkgBookRQ to book it, and save to a cookie
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('initialize.php');

if(!isset($_POST['Start'])){
    die('Missing Data in ' . __FILE__);
}
extract($_POST, EXTR_OVERWRITE);

$array = json_decode(json_encode($_POST), true);


// Delete this when Live
$array['UniqueID_ID'] = 'reference';
$PhoneNumber = '1234567890';
$Email = 'person@example';
$array['PaymentType'] = "34";
$SpecialNeed_Value      = array(
                            array('98',     'Shell Fish'),
                            array('97')
                          );
$SpecialNeed_Code       = array(
                            array('Weight', 'Allergy'),
                            array('Weight')
                          );
$Gender                 = array('Male' , 'Female');
$PassengerListItem_Code = array('ADT'  , 'ADT');
$CodeContext            = array('AQT'  , 'AQT');
$GivenName              = array('John' ,'Jane');
$Surname                = array('Doe'  , 'Doe');
//Delete this when live


// Initialize variables to empty if not defined above
$string_list = array("PhoneNumber", "Address", "Email");
foreach($string_list as $value){
    if(!isset($$value)) {$$value = '';}
}
$array_list = array('Gender', 'PassengerListItem_Code', 'CodeContext', 'GivenName', 'MiddleName', 'Surname', 'NameTitle');
foreach($array_list as $value){
    if(!isset($$value)) {$$value = array();}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Helicopter</title>
    <link rel="icon" href="favicon.png">
    <link href="styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</head>
<body>

    <form action='book.php' method="post">
<?php

//print "<pre>"; print_r($array); print "</pre>\r\n";

    /*
     *  Display Strings
     */
    $list = array('EchoToken', 'UniqueID_ID', 'PackageRequest_ID', 'PackageRequest_TravelCode', 'Start', 'PaymentType', 'Quantity');
    foreach($list as $value){
        if(isset($array[$value])) {
            echo echoThis($value) . ": $array[$value] <br>\r\n ";
            echo "\t<input type='hidden' name='{$value}' value='{$array[$value]}' />\r\n";
        }
    }

    /*
     *  Display the data we have
     */
    $flight_list = array('DepartureDateTime', 'ArrivalDateTime', 'TravelCode', 'Duration', 'CheckInDate', 'DepartureAirport_LocationCode', 'ArrivalAirport_LocationCode', 'FlightNumber' );

    // get largest index
    $largest_count = 0;
    foreach($flight_list as $value) {
        $count = count($array[$value]);
        if($count>$largest_count) {
            $largest_count = $count;
        }
    }
    for($i=0; $i<$largest_count; $i++){
        foreach($flight_list as $value) {
            echo "<label class='label3'>";
            echo "\t<input type='hidden' name='{$value}[]' value='{$array[$value][$i]}' />\r\n";
            echo echoThis($value) . "[{$i}]: ";
            echo "</label>";
            print_r($array[$value][$i]);
            echo "<br>\r\n ";
        }
    }

    /*
     *  Get the data we need
     */
//what else can be in ContactDetail
// for testing I am giving values, just make them blank when you go live


    /*************************************************************
     *  here we input the Contact information, <ContactDetail>
     */
    $ContactDetail = array('PhoneNumber'=>$PhoneNumber, 'Address'=>$Address, 'Email'=>$Email);
    echo "<br>\r\nContact Detail: <br>\r\n";
    foreach($ContactDetail as $key => $value){
        echo "<p><label class='label2' for='{$key}'>" . echoThis($key) . ": </label>";
        echo "<input id='{$key}' type='text' name='{$key}' value='{$value}' /></p>\r\n";
    }
// allow add another phone number or address or email

    /*************************************************************
     *  here we input the person information, <PassengerListItem>
     */
    $PassengerListItem_list = array('PassengerListItem_Code'=>$PassengerListItem_Code, 'Gender'=>$Gender, 'CodeContext'=>$CodeContext, 'GivenName'=>$GivenName, 'MiddleName'=>$MiddleName, 'Surname'=>$Surname, 'NameTitle'=>$NameTitle);
    $Genders_list = array("Male", "Female", "Unknown", "Male_NoShare", "Female_NoShare");
    echo "<form action='book.php' method='POST'>\r\n";

    $Quantity = $array['Quantity'];
    for($i=0; $i<$Quantity; $i++) {
        echo "<br>\r\n";
        $RPH = $i+1;
        echo "<p>RPH: $RPH</p>\r\n";
        echo "<input id='RPH' type='hidden' name='RPH[]' value='{$RPH}' /></p>\r\n";

        $passenger_gender = $PassengerListItem_list['Gender'][$i];
        echo "<p><label class='label2' for='Gender'>Gender: </label><input list='Genders' name='Gender[]' id='Gender' value='{$passenger_gender}'>";
        echo "<datalist id='Genders'>";
        foreach ($Genders_list as $value) {
            echo "<option value='{$value}'>";
        }
        echo "</datalist></p>";

        foreach ($PassengerListItem_list as $key => $value) {
            if(isset($value[$i]) && $key !== "Gender") {
                echo "<p><label class='label2' for='{$key}'>" . echoThis($key) . ": </label>";
                echo "<input id='{$key}' type='text' name='{$key}[]' value='{$value[$i]}' /></p>\r\n";
            }
        }

//        $SpecialNeed_Value = array( array('98',     'Shell Fish '), array('97')     );
//        $SpecialNeed_Code  = array( array('Weight', 'Allergy'    ), array('Weight') );
//        on the others page, what do i do with name Weight[]??
//        need to save weight as hidden value and the number for weight as another value
        $target_code  = $SpecialNeed_Code[$i];
        $target_value = $SpecialNeed_Value[$i];
        $count = count($SpecialNeed_Code[$i]);
        for($j=0; $j<$count; $j++){
            echo "<p>";
            echo "<label class='label2' for='SpecialNeed_Code'>"  . echoThis('SpecialNeed_Code')  . ": </label>";
            echo "<input id='SpecialNeed_Code'  type='text' name='SpecialNeed_Code[]'  value='{$target_code[$j]}' /> ";
            echo "<label class='label2' for='SpecialNeed_Value'>" . echoThis('SpecialNeed_Value') . ": </label>";
            echo "<input id='SpecialNeed_Value' type='text' name='SpecialNeed_Value[]' value='{$target_value[$j]}' />";
            echo "</p>\r\n";
        }

    }

    echo "<button class='blue_up' type='Submit'>Book</button>";
    echo "</form>";
?>
    <script type="text/javascript" src="mysrc.js"></script>
</body>
</html>
<?php
/*
EchoToken="test"
UniqueID_ID="reference"
PackageRequest_ID="128257"
TravelCode="Dog Sled Tour"
Start="2021-05-10T13:05:00"
ItineraryItem[0]
    DepartureDateTime="2021-05-10T13:05:00"
    ArrivalDateTime="2021-05-10T14:42:00"
    TravelCode="126402,126477"
    Duration="97"
    CheckInDate="2021-05-10T12:35:00"
// can you have multiple flights in one itinerary
    DepartureAirport_LocationCode="Juneau Airport"
    ArrivalAirport_LocationCode="Juneau Airport"
    FlightNumber="DS1305,DR1430"

// can you have multiple ContactDetail tags
PhoneNumber="123456789x"
Address
Email person@example.com
PassengerListItem[0]
    RPH="1"
    Gender="Unknown"
    Code="ADT"
    CodeContext="AQT"
    Quantity="1"
    GivenName=John
    MiddleName
    Surname Doe
    NameTitle
    SpecialNeed_Code= array("Weight"=>98)
PassengerListItem[1]
    RPH="2"
    Gender="Unknown"
    Code="ADT"
    CodeContext="AQT"
    Quantity="1"
    GivenName=Jane
    MiddleName
    Surname Doe
    NameTitle
    SpecialNeed_Code= array("Weight"=>97)
PaymentType="34"
*/