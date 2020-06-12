<?php
    function passengerListItems($RPH, $Gender, $Code, $CodeContext, $Quantity,
             $GivenName=null, $MiddleName=null, $Surname=null, $NameTitle=null, $SpecializedNeed=null)
    {
		$cnt = count($RPH);  // some variable that cannot be skipped
		
        $fields = array();
        $fields[] = '                <ns:PassengerListItems>';

		$list = array("GivenName", "MiddleName", "Surname", "NameTitle");
		
		for($i=0; $i<$cnt; $i++)
		{
			$fields[] = '                    <ns:PassengerListItem RPH = "' . $RPH . '" Gender = "' . $Gender . '" Code = "' . $Code . '" CodeContext = "' . $CodeContext . '" Quantity = "' . $Quantity . '">';
			$fields[] = '                        <ns:Name>';

			foreach($list as $tag)
			{
				$temp = (is_null($$tag[$i])) ? "<ns:{$tag} />" : "<ns:{$tag}>{$$tag[$i]}</ns:{$tag}>";
				$fields[] = str_repeat(" ", 28) . $temp;
			}
			$needs = $SpecializedNeed[$i];

			foreach($needs as $Code => $value)
			{
				$temp = '<ns:SpecialNeed Code = "' . $Code . '">' . $value . '</ns:SpecialNeed>'; 
				$fields[] = str_repeat(" ", 28) . $temp;
			}
			$fields[] = '                        </ns:Name>';
			$fields[] = '                    </ns:PassengerListItem>';
		}
        $fields[] = '               </ns:PassengerListItems>';
        return $fields;
    }

	function x($a, $b=null, $c=null){
		$total = $a;
		if(!is_null($b)) $total += $b;
		if(!is_null($c)) $total += $c;
		return $total;
	}

$RPH = array("1", "2");
$Gender = array("Male", "Female");
$Code = array("ADT", "ADT");
$CodeContext = array("AQT", "AQT");
$Quantity = array("1", "1");
$GivenName = array("John", "Jane");
$MiddleName = null;
$Surname = array("Doe", "Doe");
$NameTitle = null
$SpecializedNeed = array( array("Weight"=>98, "Allergies"=>"Peanut"), array("Weight"=>97) );
$PaymentType = "34";
$Address = null
$Email = "person@example.com";

$results = passengerListItems($RPH , $Gender, $Code, $CodeContext, $Quantity, $GivenName, $MiddleName, $Surname, $NameTitle, $SpecializedNeed);
print_r($results);
echo "<hr />";

echo "|" . x(5,,2) . "|";
