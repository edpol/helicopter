<?php

<xs:complexType name="OTA_AirLowFareSearchRQ">
        <xs:sequence>
            <xs:element minOccurs="0" maxOccurs="1" name="POS" type="tns:ArrayOfSourceType"/>
            <xs:element minOccurs="0" maxOccurs="1" name="ProcessingInfo">
                <xs:complexType>
                    <xs:attribute name="TargetSource">
                        <xs:simpleType>
                            <xs:restriction base="xs:string">
                                <xs:enumeration value="Core"/>
                                <xs:enumeration value="Vendor"/>
                            </xs:restriction>
                        </xs:simpleType>
                    </xs:attribute>
                    <xs:attribute name="FlightSvcInfoIndicator" type="xs:boolean"/>
                    <xs:attribute name="DisplayOrder" type="tns:DisplayOrderType"/>
                    <xs:attribute name="ReducedDataIndicator" type="xs:boolean"/>
                    <xs:attribute name="BaseFaresOnlyIndicator" type="xs:boolean"/>
                    <xs:attribute name="SearchType">
                        <xs:simpleType>
                            <xs:restriction base="xs:string">
                                <xs:enumeration value="Normal"/>
                                <xs:enumeration value="Window"/>
                                <xs:enumeration value="ArrivalTime"/>
                                <xs:enumeration value="PowerFlight"/>
                                <xs:enumeration value="AvailableOnly"/>
                                <xs:enumeration value="WaitlistOnly"/>
                                <xs:enumeration value="AvailableAndWaitlist"/>
                                <xs:enumeration value="FreighterFlights"/>
                                <xs:enumeration value="Reward"/>
                            </xs:restriction>
                        </xs:simpleType>
                    </xs:attribute>
                    <xs:attribute name="AvailabilityIndicator" type="xs:boolean"/>
                </xs:complexType>
            </xs:element>
            <xs:element minOccurs="0" maxOccurs="1" name="MultimodalOffer" type="tns:MultiModalOfferType"/>
            <xs:element minOccurs="0" maxOccurs="unbounded" name="OriginDestinationInformation">
                <xs:complexType>
                    <xs:complexContent mixed="false">
                        <xs:extension base="tns:OriginDestinationInformationType">
                            <xs:sequence>
                                <xs:element minOccurs="0" maxOccurs="1" name="AlternateLocationInfo">
                                    <xs:complexType>
                                        <xs:attribute name="OriginLocation">
                                            <xs:simpleType>
                                                <xs:list itemType="xs:string"/>
                                            </xs:simpleType>
                                        </xs:attribute>
                                        <xs:attribute name="DestinationLocation">
                                            <xs:simpleType>
                                                <xs:list itemType="xs:string"/>
                                            </xs:simpleType>
                                        </xs:attribute>
                                    </xs:complexType>
                                </xs:element>
                                <xs:element minOccurs="0" maxOccurs="1" name="TPA_Extensions" type="tns:TPA_ExtensionsType"/>
                            </xs:sequence>
                            <xs:attribute name="RPH" type="xs:string"/>
                            <xs:attribute name="RefNumber" type="xs:integer"/>
                        </xs:extension>
                    </xs:complexContent>
                </xs:complexType>
            </xs:element>
            <xs:element minOccurs="0" maxOccurs="1" name="SpecificFlightInfo" type="tns:SpecificFlightInfoType"/>
            <xs:element minOccurs="0" maxOccurs="unbounded" name="TravelPreferences">
                <xs:complexType>
                    <xs:complexContent mixed="false">
                        <xs:extension base="tns:AirSearchPrefsType">
                            <xs:attribute name="FlexDatePref">
                                <xs:simpleType>
                                    <xs:restriction base="xs:string">
                                        <xs:enumeration value="Outbound"/>
                                        <xs:enumeration value="Return"/>
                                        <xs:enumeration value="Both"/>
                                    </xs:restriction>
                                </xs:simpleType>
                            </xs:attribute>
                            <xs:attribute name="FlexWeekendIndicator" type="xs:boolean"/>
                            <xs:attribute name="FlexLevelIndicator" type="xs:boolean"/>
                            <xs:attribute name="NoFareBreakIndicator" type="xs:boolean"/>
                            <xs:attribute name="OriginDestinationRPHs">
                                <xs:simpleType>
                                    <xs:list itemType="xs:string"/>
                                </xs:simpleType>
                            </xs:attribute>
                        </xs:extension>
                    </xs:complexContent>
                </xs:complexType>
            </xs:element>
            <xs:element minOccurs="0" maxOccurs="1" name="TravelerInfoSummary">
                <xs:complexType>
                    <xs:complexContent mixed="false">
                        <xs:extension base="tns:TravelerInfoSummaryType">
                            <xs:attribute name="TicketingCountryCode" type="xs:string"/>
                            <xs:attribute name="SpecificPTC_Indicator" type="xs:boolean"/>
                        </xs:extension>
                    </xs:complexContent>
                </xs:complexType>
            </xs:element>
            <xs:element minOccurs="0" maxOccurs="1" name="ArrangerInfoSummary" type="tns:AirArrangerType"/>
        </xs:sequence>
        <xs:attribute name="EchoToken" type="xs:string"/>
        <xs:attribute name="TimeStamp" type="xs:dateTime"/>
        <xs:attribute name="Target">
            <xs:simpleType>
                <xs:restriction base="xs:string">
                    <xs:enumeration value="Test"/>
                    <xs:enumeration value="Production"/>
                </xs:restriction>
            </xs:simpleType>
        </xs:attribute>
        <xs:attribute name="TargetName" type="xs:string"/>
        <xs:attribute name="Version" type="xs:decimal" use="required"/>
        <xs:attribute name="TransactionIdentifier" type="xs:string"/>
        <xs:attribute name="SequenceNmbr" type="xs:nonNegativeInteger"/>
        <xs:attribute name="TransactionStatusCode">
            <xs:simpleType>
                <xs:restriction base="xs:string">
                    <xs:enumeration value="Start"/>
                    <xs:enumeration value="End"/>
                    <xs:enumeration value="Rollback"/>
                    <xs:enumeration value="InSeries"/>
                    <xs:enumeration value="Continuation"/>
                    <xs:enumeration value="Subsequent"/>
                </xs:restriction>
            </xs:simpleType>
        </xs:attribute>
        <xs:attribute name="RetransmissionIndicator" type="xs:boolean"/>
        <xs:attribute name="CorrelationID" type="xs:string"/>
        <xs:attribute name="PrimaryLangID" type="xs:language"/>
        <xs:attribute name="AltLangID" type="xs:language"/>
        <xs:attribute name="MaxResponses" type="xs:positiveInteger"/>
        <xs:attribute name="DirectFlightsOnly" type="xs:boolean"/>
        <xs:attribute name="AvailableFlightsOnly" type="xs:boolean"/>
    </xs:complexType>