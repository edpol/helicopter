<?php
class ArrayOfSourceType{
    public function __construct($source) {
    SourceType Source;``
}

$ArrayOfSourceType

class for_function_OTA_AirLowFareSearchRQ 
{
	public function __contruct()
	{
		 ArrayOfSourceType POS;
		 ProcessingInfo ProcessingInfo;
		 MultiModalOfferType MultimodalOffer;
		 OriginDestinationInformation OriginDestinationInformation;
		 SpecificFlightInfoType SpecificFlightInfo;
		 TravelPreferences TravelPreferences;
		 TravelerInfoSummary TravelerInfoSummary;
		 AirArrangerType ArrangerInfoSummary;
		 string EchoToken;
		 dateTime TimeStamp;
		 anonymous12 Target;
		 string TargetName;
		 decimal Version;
		 string TransactionIdentifier;
		 nonNegativeInteger SequenceNmbr;
		 anonymous13 TransactionStatusCode;
		 boolean RetransmissionIndicator;
		 string CorrelationID;
		 language PrimaryLangID;
		 language AltLangID;
		 positiveInteger MaxResponses;
		 boolean DirectFlightsOnly;
		 boolean AvailableFlightsOnly;
	}
}
// Set the request parameters
$parameters = new for_function_OTA_AirLowFareSearchRQ(

);

$function = "OTA_AirLowFareSearchRQ";

// Invoke WS method (Function1) with the request params 
$response = $client->__soapCall($function, array($parameters));
