<?php
/*
 * Naming the classes the same as the functions
 */

class RequestorID {
    private $MessagePassword;
    public function __construct($MessagePassword)
    {
        $this->MessagePassword = $MessagePassword;
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

class TPA_ExtensionsType {
    private $any;
    public function __construct($any){
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

    public function __construct($CompanyName, $TPA_Extensions, $Type, $Primary)
    {
        $this->CompanyName    = $CompanyName;   //CompanyNameType
        $this->TPA_Extensions = $TPA_Extensions;//TPA_ExtensionsType
        $this->Type           = $Type;          //string
        $this->Primary        = $Primary;       //boolean
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

    public function __construct($RequestorID, $Position, $BookingChannel, $TPA_Extensions, $AgentSine, $PseudoCityCode, $ISOCountry, $ISOCurrency, $AgentDutyCode, $AirlineVendorID, $AirportCode, $FirstDepartPoint, $ERSP_UserID, $TerminalID)
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
    public function __construct($source)
    {
        $this->Source = $source;    //SourceType
    }
}

class ProcessingInfo
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
        $this->TargetSource = $TargetSource;  //anonymous2
        $this->FlightSvcInfoIndicator = $FlightSvcInfoIndicator;   //boolean
        $this->DisplayOrder = $DisplayOrder; //DisplayOrderType
        $this->ReducedDataIndicator = $ReducedDataIndicator; //boolean
        $this->BaseFaresOnlyIndicator = $BaseFaresOnlyIndicator;   //boolean
        $this->SearchType = $SearchType;       //anonymous3
        $this->AvailabilityIndicator = $AvailabilityIndicator;    //boolean
    }
}

class MultiModalOfferType {
    private $RequestingParty;
    private $Ontology;
    private $RequestedOffer;
    private $TripCharacteristics;
    private $TravelerCharacteristics;
    private $OntologyExtension;
    public function __construct($RequestingParty, $Ontology, $RequestedOffer, $TripCharacteristics, $TravelerCharacteristics, $OntologyExtension)
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

class for_function_OTA_AirLowFareSearchRQ 
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

    public function __construct($POS, $ProcessingInfo, $MultimodalOffer, $OriginDestinationInformation, $SpecificFlightInfo, $TravelPreferences, $TravelerInfoSummary, $ArrangerInfoSummary, $EchoToken, $TimeStamp, $Target, $TargetName, $Version, $TransactionIdentifier, $SequenceNmbr, $TransactionStatusCode, $RetransmissionIndicator, $CorrelationID, $PrimaryLangID, $AltLangID, $MaxResponses, $DirectFlightsOnly, $AvailableFlightsOnly)
    {
        $this->POS = $POS;   //ArrayOfSourceType
        $this->ProcessingInfo = $ProcessingInfo;    //ProcessingInfo
        $this->MultimodalOffer = $MultimodalOffer;   //MultiModalOfferType
        $this->OriginDestinationInformation = $OriginDestinationInformation;  //OriginDestinationInformation
        $this->SpecificFlightInfo = $SpecificFlightInfo;    //SpecificFlightInfoType
        $this->TravelPreferences = $TravelPreferences;     //TravelPreferences
        $this->TravelerInfoSummary = $TravelerInfoSummary;   //TravelerInfoSummary
        $this->ArrangerInfoSummary = $ArrangerInfoSummary;   //AirArrangerType
        $this->EchoToken = $EchoToken;     //string
        $this->TimeStamp = $TimeStamp; //dateTime
        $this->Target = $Target;     //anonymous12
        $this->TargetName = $TargetName;    //string
        $this->Version = $Version;       //decimal
        $this->TransactionIdentifier = $TransactionIdentifier; //string
        $this->SequenceNmbr = $SequenceNmbr;      //nonNegativeInteger
        $this->TransactionStatusCode = $TransactionStatusCode;     //anonymous13
        $this->RetransmissionIndicator = $RetransmissionIndicator;       //boolean
        $this->CorrelationID = $CorrelationID;     //string
        $this->PrimaryLangID = $PrimaryLangID;     //language
        $this->AltLangID = $AltLangID;         //language
        $this->MaxResponses = $MaxResponses;      //positiveInteger
        $this->DirectFlightsOnly = $DirectFlightsOnly; //boolean
        $this->AvailableFlightsOnly = $AvailableFlightsOnly;  //boolean
	}
}

// Set the request parameters
$parameters = new for_function_OTA_AirLowFareSearchRQ(

);

$function = "OTA_AirLowFareSearchRQ";

// Invoke WS method (Function1) with the request params 
$response = $client->__soapCall($function, array($parameters));
