<?php
function price($name)
{
	$details=array(
				'php'=>100,
				'java'=>200
				);
	foreach($details as $n=>$p)
	{
		if($name==$n)
			$price=$p;
	}
	return $price;
}
?>