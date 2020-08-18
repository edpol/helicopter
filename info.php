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
$Email = 'person@example.com';
$array['PaymentType'] = "34";
$SpecialNeed =
    array(
        array(
            array("Code"=>"Weight" , '_'=>98),
            array("Code"=>"Allergy", '_'=>'Peanuts')
        ),
        array(
            array('Code'=>'Weight', '_'=>97)
        )
    );
$Gender                 = array('Male' , 'Female');
$PassengerListItem_Code = array('ADT'  , 'ADT');
$CodeContext            = array('AQT'  , 'AQT');
$GivenName              = array('John' ,'Jane');
$Surname                = array('Doe'  , 'Doe');
$PassengerListItem_Quantity = array('1'    , '1'  );
//Delete this when live


// Initialize variables to empty if not defined above
$string_list = array("PhoneNumber", "Address", "Email");
foreach($string_list as $value){
    if(!isset($$value)) {$$value = '';}
}
$array_list = array('Gender', 'PassengerListItem_Code', 'CodeContext', 'GivenName', 'MiddleName', 'Surname', 'NameTitle', 'PassengerListItem_Quantity');
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
            echo "\t" . echoThis($value) . ": $array[$value] <br>\r\n";
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
            echo "\t<span class='label3'>" . echoThis($value) . "[{$i}]: </span>";
            echo "\t<input type='hidden' name='{$value}[]' value='{$array[$value][$i]}' />\r\n\t";
            print_r($array[$value][$i]);
            echo "\t<br>\r\n ";
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
    echo "\r\n\t<br>\r\n\tContact Detail: <br>\r\n";
    foreach($ContactDetail as $key => $value){
        echo "\t<p><label class='label2' for='{$key}'>" . echoThis($key) . ": </label>";
        echo "<input id='{$key}' type='text' name='{$key}' value='{$value}' /></p>\r\n";
    }
// allow add another phone number or address or email

    /*************************************************************
     *  here we input the person information, <PassengerListItem>
     */
    $PassengerListItem_list = array('PassengerListItem_Code'=>$PassengerListItem_Code, 'Gender'=>$Gender, 'CodeContext'=>$CodeContext, 'GivenName'=>$GivenName, 'MiddleName'=>$MiddleName, 'Surname'=>$Surname, 'NameTitle'=>$NameTitle, 'PassengerListItem_Quantity'=>$PassengerListItem_Quantity);
    $Genders_list = array("Male", "Female", "Unknown", "Male_NoShare", "Female_NoShare");

    $Quantity = $array['Quantity'];
    for($i=0; $i<$Quantity; $i++) {
        echo "\t<br>\r\n";
        $RPH = $i+1;
        echo "\r\n\t<p>RPH: $RPH</p>\r\n";
        echo "\t<input id='RPH' type='hidden' name='RPH[]' value='{$RPH}' />\r\n";

        $passenger_gender = $PassengerListItem_list['Gender'][$i];
        echo "\t<p>\r\n\t\t<label class='label2' for='Gender'>Gender: </label><input list='Genders' name='Gender[]' id='Gender' value='{$passenger_gender}'>\r\n";
        echo "\t\t<datalist id='Genders'>\r\n";
        foreach ($Genders_list as $value) {
            echo "\t\t\t<option value='{$value}'>\r\n";
        }
        echo "\t\t</datalist>\r\n\t</p>\r\n";

        foreach ($PassengerListItem_list as $key => $value) {
            if(isset($value[$i]) && $key !== "Gender") {
                echo "\t<p><label class='label2' for='{$key}'>";
                echo ($key==='PassengerListItem_Quantity') ? 'Quantity' : echoThis($key);
                echo ": </label>";
                echo "<input id='{$key}' type='text' name='{$key}[]' value='{$value[$i]}' /></p>\r\n";
            }
        }

//        $SpecialNeed_Value = array( array('98',     'Shell Fish '), array('97')     );
//        $SpecialNeed_Code  = array( array('Weight', 'Allergy'    ), array('Weight') );
//        on the others page, what do i do with name Weight[]??
//        need to save weight as hidden value and the number for weight as another value

        $j = 0;
        foreach($SpecialNeed[$i] as $element){

            echo "\t<p>\r\n";
            $SpecialNeed_list = array('Code', '_');
            foreach($SpecialNeed_list as $key => $target){
                echo "\t\t<label class='label2' for='SpecialNeed{$i}{$j}{$target}'>Special Need: </label>";
                echo "<input id='SpecialNeed{$i}{$j}{$target}'  type='text' name='SpecialNeed[$i][$j][$target]' value='{$SpecialNeed[$i][$j][$target]}' />\r\n";
            }
            echo "\t</p>\r\n";

//            $target = 'Code';
//            echo "\t<p>\r\n";
//            echo "\t\t<label class='label2' for='SpecialNeed_Code'>"  . echoThis('SpecialNeed_Code')  . ": </label>";
//            echo "<input id='SpecialNeed_Code'  type='text' name='SpecialNeed[$i][$j][$target]'  value='{$SpecialNeed[$i][$j][$target]}' />\r\n";
//
//            $target = '_';
//            echo "\t\t<label class='label2' for='SpecialNeed_Value'>" . echoThis('SpecialNeed_Value') . ": </label>";
//            echo "<input id='SpecialNeed_Value' type='text' name='SpecialNeed[$i][$j][$target]' value='{$SpecialNeed[$i][$j][$target]}' />\r\n";
//            echo "\t</p>\r\n";

            $j++;
        }

    }

    echo "<button class='blue_up' type='Submit'>Book</button>\r\n";
    echo "</form>\r\n";
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