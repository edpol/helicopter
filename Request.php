<?php
namespace Takeflite;
/*
 * all the calls to the api
 * If all the strings in the array are single quote all the tags become lower case
 * with just one exception they keep their case
 */
class Request {
    private $Credentials;

    public function __construct($AgentLogin, $AgentPassword, $ServiceId)
    {
        $this->Credentials = array(
            "AgentLogin" => $AgentLogin,
            "AgentPassword" => $AgentPassword,
            "ServiceId" => $ServiceId
        );
    }

    //  [0] => OTA_AirLowFareSearchRQResponse OTA_AirLowFareSearchRQ(OTA_AirLowFareSearchRQ $parameters)
    public function OTA_AirLowFareSearchRQ($client, $parameters)
    {

    }

    //  [1] => OTA_AirBookRQResponse OTA_AirBookRQ(OTA_AirBookRQ $parameters)
    public function OTA_AirBookRQ($client, $parameters)
    {

    }

    //  [2] => OTA_AirScheduleRQResponse OTA_AirScheduleRQ(OTA_AirScheduleRQ $parameters)
    public function OTA_AirScheduleRQ($client, $parameters)
    {

    }

    //  [3] => OTA_AirFlifoRQResponse OTA_AirFlifoRQ(OTA_AirFlifoRQ $parameters)
    public function OTA_AirFlifoRQ($client, $parameters)
    {

    }

    //  [4] => OTA_AirBookModifyRQResponse OTA_AirBookModifyRQ(OTA_AirBookModifyRQ $parameters)
    public function OTA_AirBookModifyRQ($client, $parameters)
    {

    }

    //  [5] => OTA_ReadRQResponse OTA_ReadRQ(OTA_ReadRQ $parameters)
    public function OTA_ReadRQ($client, $parameters)
    {

    }

    //  [6] => OTA_ReadProfileRQResponse OTA_ReadProfileRQ(OTA_ReadProfileRQ $parameters)
    public function OTA_ReadProfileRQ($client, $parameters)
    {

    }

    //  [7] => OTA_PkgAvailRQResponse OTA_PkgAvailRQ(OTA_PkgAvailRQ $parameters)
    public function OTA_PkgAvailRQ($client, $EchoToken, $ID="", $Start="", $Code="", $Quantity="")
    {
        $PkgAvailRQ = array(
            "EchoToken"      => $EchoToken,
            "PackageRequest" => array(
                "ID" => $ID,
                "DateRange" => array(
                    "Start" => $Start
                ),
            ),
            "CustomerCounts" => array(
                "CustomerCount" => array( "Code"=>$Code, "Quantity"=>$Quantity)
            )
        );

        $parameters = array( "PkgAvailRQ"=>$PkgAvailRQ, "Credentials"=>$this->Credentials);
        try{
            $response = $client->OTA_PkgAvailRQ($parameters);
        } catch(Exception $e) {
            echo "<pre>test8: <br>";
            dumpCatch($e, $client, __FUNCTION__. " in " . __CLASS__ . " at " . __LINE__ );
            echo "</pre>";
            return false;
        }
        return $response;
    }

    //  [8] => OTA_PkgBookRQResponse OTA_PkgBookRQ(OTA_PkgBookRQ $parameters)
    public function OTA_PkgBookRQ($client, $parameters)
    {

    }



}
