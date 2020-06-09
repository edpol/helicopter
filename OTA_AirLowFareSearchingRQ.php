<?php
/*
 * Naming the classes the same as the functions
 */

// these are the allowed values for the given variable
class anonymous {
    public static $anonymous12 = ["Test", "Production"];
    public static $anonymous3 = ["Normal", "Window", "ArrivalTime", "PowerFlight", "AvailableOnly", "WaitlistOnly", "AvailableAndWaitlist", "FreighterFlights", "Reward"];
    public static $anonymous2 = ["Core", "Vendor"];
}

class RequestorID {
    private $MessagePassword;
    public function __construct($MessagePassword)
    {
        $this->MessagePassword = $MessagePassword;  //string
    }
}

// this means that they can append any type of declared element
class TPA_ExtensionsType {
    private $any;
    public function __construct($any=null){
        $this->any = $any;
    }
}

class CompanyNameType {
    private $_;
    private $CompanyShortName;
    private $TravelSector;
    private $Code;
    private $CodeContext;
    private $CountryCode;
    private $Division;
    private $Department;
    public function __construct($_, $CompanyShortName, $TravelSector, $Code, $CodeContext, $CountryCode, $Division, $Department)
    {
        $this->_ = $_;                                  // All string
        $this->CompanyShortName = $CompanyShortName;
        $this->TravelSector = $TravelSector;
        $this->Code         = $Code;
        $this->CodeContext  = $CodeContext;
        $this->CountryCode  = $CountryCode;
        $this->Division     = $Division;
        $this->Department   = $Department;
    }
}

class BookingChannel {
    private $CompanyName;
    private $TPA_Extensions;
    private $Type;
    private $Primary;

    public function __construct($Type, $Primary, $CompanyName=null, $TPA_Extensions=null)
    {
        $this->CompanyName    = $CompanyName;   //CompanyNameType
        $this->TPA_Extensions = $TPA_Extensions;//TPA_ExtensionsType
        $this->Type           = $Type;          //string
        $this->Primary        = $Primary;       //boolean
    }
}

class Position {
    private $Latitude;
    private $Longitude;
    private $Altitude;
    private $AltitudeUnitOfMeasureCode;
    private $PositionAccuracyCode;
    public function __construct($Latitude, $Longitude, $Altitude, $AltitudeUnitOfMeasureCode, $PositionAccuracyCode)
    {
        $this->Latitude  = $Latitude;       // all string
        $this->Longitude = $Longitude;
        $this->Altitude  = $Altitude;
        $this->AltitudeUnitOfMeasureCode = $AltitudeUnitOfMeasureCode;
        $this->PositionAccuracyCode = $PositionAccuracyCode;
    }
}

class SourceType {
    private $RequestorID;
    private $Position;
    private $BookingChannel;
    private $TPA_Extensions;
    private $AgentSine;
    private $PseudoCityCode;
    private $ISOCountry;
    private $ISOCurrency;
    private $AgentDutyCode;
    private $AirlineVendorID;
    private $AirportCode;
    private $FirstDepartPoint;
    private $ERSP_UserID;
    private $TerminalID;

    public function __construct($AgentSine, $PseudoCityCode, $ISOCountry, $ISOCurrency, $AgentDutyCode, $AirlineVendorID, $AirportCode, $FirstDepartPoint, $ERSP_UserID, $TerminalID,
                                $RequestorID=null, $Position=null, $BookingChannel=null, $TPA_Extensions=null )
    {
        $this->RequestorID      = $RequestorID;         //RequestorID
        $this->Position         = $Position;            //Position
        $this->BookingChannel   = $BookingChannel;      //BookingChannel
        $this->TPA_Extensions   = $TPA_Extensions;      //TPA_ExtensionsType

        $this->AgentSine        = $AgentSine;           //string
        $this->PseudoCityCode   = $PseudoCityCode;      //string
        $this->ISOCountry       = $ISOCountry;          //string
        $this->ISOCurrency      = $ISOCurrency;         //string
        $this->AgentDutyCode    = $AgentDutyCode;       //string
        $this->AirlineVendorID  = $AirlineVendorID;     //string
        $this->AirportCode      = $AirportCode;         //string
        $this->FirstDepartPoint = $FirstDepartPoint;    //string
        $this->ERSP_UserID      = $ERSP_UserID;         //string
        $this->TerminalID       = $TerminalID;          //string
    }
}

class ArrayOfSourceType{
    private $Source;
    public function __construct($source=null)
    {
        $this->Source = $source;    //SourceType, unbounded
    }
}

class ProcessingInfo extends anonymous
{
    private $TargetSource;
    private $FlightSvcInfoIndicator;
    private $DisplayOrder;
    private $ReducedDataIndicator;
    private $BaseFaresOnlyIndicator;
    private $SearchType;
    private $AvailabilityIndicator;

    public function __construct($TargetSource, $FlightSvcInfoIndicator, $DisplayOrder, $ReducedDataIndicator, $BaseFaresOnlyIndicator, $SearchType, $AvailabilityIndicator)
    {
        $this->TargetSource           = $TargetSource;          //anonymous2
        $this->FlightSvcInfoIndicator = $FlightSvcInfoIndicator;//boolean
        $this->DisplayOrder           = $DisplayOrder;          //DisplayOrderType
        $this->ReducedDataIndicator   = $ReducedDataIndicator;  //boolean
        $this->BaseFaresOnlyIndicator = $BaseFaresOnlyIndicator;//boolean
        $this->SearchType             = $SearchType;            //anonymous3
        $this->AvailabilityIndicator  = $AvailabilityIndicator; //boolean
    }
}

class OntologyCompanyType {
    private $NameOrCode;
    private $TravelSegment;
    private $OntologyExtension;
    public function __construct($NameOrCode, $TravelSegment, $OntologyExtension)
    {

        $this->NameOrCode        = $NameOrCode;       //OntologyCodeType
        $this->TravelSegment     = $TravelSegment;     //TravelSegment
        $this->OntologyExtension = $OntologyExtension; //OntologyExtensionType
    }
}

class Ontology {
    private $CompatibleWith;
    private $OntologyExtension;
    public function __construct($CompatibleWith, $OntologyExtension)
    {
        $this->CompatibleWith    = $CompatibleWith;     //CompatibleWith
        $this->OntologyExtension = $OntologyExtension;  //OntologyExtensionType
    }
}

class MultiModalOfferType {
    private $RequestingParty;
    private $Ontology;
    private $RequestedOffer;
    private $TripCharacteristics;
    private $TravelerCharacteristics;
    private $OntologyExtension;
    public function __construct($RequestingParty=null, $Ontology=null, $RequestedOffer=null, $TripCharacteristics=null, $TravelerCharacteristics=null, $OntologyExtension=null)
    {
        $this->RequestingParty         = $RequestingParty;          //RequestingParty
        $this->Ontology                = $Ontology;                 //Ontology
        $this->RequestedOffer          = $RequestedOffer;           //RequestedOffer
        $this->TripCharacteristics     = $TripCharacteristics;      //TripCharacteristics
        $this->TravelerCharacteristics = $TravelerCharacteristics;  //TravelerCharacteristics
        $this->OntologyExtension       = $OntologyExtension;        //OntologyExtensionType
    }
}

class OriginDestinationInformationType {
    private $OriginLocation;
    private $DestinationLocation;
    private $ConnectionLocations;
    public function __construct($OriginLocation, $DestinationLocation, $ConnectionLocations)
    {
        $this->OriginLocation      = $OriginLocation;       //OriginLocation
        $this->DestinationLocation = $DestinationLocation;  //DestinationLocation
        $this->ConnectionLocations = $ConnectionLocations;  //ArrayOfConnectionTypeConnectionLocation
    }
}

class for_function_OTA_AirLowFareSearchRQ extends anonymous
{
    private $POS;
    private $ProcessingInfo;
    private $MultimodalOffer;
    private $OriginDestinationInformation;
    private $SpecificFlightInfo;
    private $TravelPreferences;
    private $TravelerInfoSummary;
    private $ArrangerInfoSummary;
    private $EchoToken;
    private $TimeStamp;
    private $Target;
    private $TargetName;
    private $Version;
    private $TransactionIdentifier;
    private $SequenceNmbr;
    private $TransactionStatusCode;
    private $RetransmissionIndicator;
    private $CorrelationID;
    private $PrimaryLangID;
    private $AltLangID;
    private $MaxResponses;
    private $DirectFlightsOnly;
    private $AvailableFlightsOnly;

    public function __construct($EchoToken, $TimeStamp, $Target, $TargetName, $Version, $TransactionIdentifier, $SequenceNmbr, $TransactionStatusCode, $RetransmissionIndicator, $CorrelationID, $PrimaryLangID, $AltLangID, $MaxResponses, $DirectFlightsOnly, $AvailableFlightsOnly,
                                $POS=null, $ProcessingInfo=null, $MultimodalOffer=null, $OriginDestinationInformation=null, $SpecificFlightInfo=null, $TravelPreferences=null, $TravelerInfoSummary=null, $ArrangerInfoSummary=null )
    {
        $this->POS                          = $POS;                             //ArrayOfSourceType
        $this->ProcessingInfo               = $ProcessingInfo;                  //ProcessingInfo
        $this->MultimodalOffer              = $MultimodalOffer;                 //MultiModalOfferType
        $this->OriginDestinationInformation = $OriginDestinationInformation;    //OriginDestinationInformation
        $this->SpecificFlightInfo           = $SpecificFlightInfo;              //SpecificFlightInfoType
        $this->TravelPreferences            = $TravelPreferences;               //TravelPreferences
        $this->TravelerInfoSummary          = $TravelerInfoSummary;             //TravelerInfoSummary
        $this->ArrangerInfoSummary          = $ArrangerInfoSummary;             //AirArrangerType

        $this->EchoToken                    = $EchoToken;                       //string
        $this->TimeStamp                    = $TimeStamp;                       //dateTime
        $this->Target                       = $Target;                          //anonymous12
        $this->TargetName                   = $TargetName;                      //string
        $this->Version                      = $Version;                         //decimal
        $this->TransactionIdentifier        = $TransactionIdentifier;           //string
        $this->SequenceNmbr                 = $SequenceNmbr;                    //nonNegativeInteger
        $this->TransactionStatusCode        = $TransactionStatusCode;           //anonymous13
        $this->RetransmissionIndicator      = $RetransmissionIndicator;         //boolean
        $this->CorrelationID                = $CorrelationID;                   //string
        $this->PrimaryLangID                = $PrimaryLangID;                   //language
        $this->AltLangID                    = $AltLangID;                       //language
        $this->MaxResponses                 = $MaxResponses;                    //positiveInteger
        $this->DirectFlightsOnly            = $DirectFlightsOnly;               //boolean
        $this->AvailableFlightsOnly         = $AvailableFlightsOnly;            //boolean
	}

}

$EchoToken = "";
$TimeStamp = date('Y-m-d H:i:s'); // dateTime;
$Target = "Test"; // ["Test", "Production"];
$TargetName = "";
$Version = 1.0; // decimal
$TransactionIdentifier = "";
$SequenceNmbr = 5; //non negative integer
$TransactionStatusCode = "";
$RetransmissionIndicator = false;
$CorrelationID = "";
$PrimaryLangID = "";
$AltLangID = "";
$MaxResponses = 2; // positive integer
$DirectFlightsOnly = false;
$AvailableFlightsOnly = true;

// Set the request parameters
$parameters = new for_function_OTA_AirLowFareSearchRQ(
    $EchoToken, $TimeStamp, $Target, $TargetName, $Version, $TransactionIdentifier, $SequenceNmbr, $TransactionStatusCode, $RetransmissionIndicator, $CorrelationID, $PrimaryLangID, $AltLangID, $MaxResponses, $DirectFlightsOnly, $AvailableFlightsOnly
);

$function = "OTA_AirLowFareSearchRQ";

require_once('initialize.php');
ini_set('soap.wsdl_cache_enabled', '0');

$opts = [ 'ssl'=> ['verify_peer'=>false, 'verify_peer_name'=>false ] ];
$context = stream_context_create($opts);
$soapClientOptions = [ 'stream_context' => $context, 'soap_version' => SOAP_1_2, 'cache' => WSDL_CACHE_NONE ];

$uri = 'http://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl';
$client = new SoapClient( $uri, $soapClientOptions );

// Invoke WS method (Function1) with the request params 
$response = $client->__soapCall($function, array($parameters));
