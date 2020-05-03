<?php
require 'lib/nusoap.php';
$client=new nusoap_client("http://localhost/helicopter/NuSOAP/service.php?wsdl");
$output="";
if(isset($_POST['submit']))
{
	$book_name=$_POST['bookid'];
	$price=$client->call('price',array('name'=>"$book_name"));
	if(empty($price)) {
        $output = "book data not available";
    }
	else {
        $output = "the price of the book  " . $book_name . " is " . $price;
    }
}
?>
<html lang="en">
<body>
<form method="POST">
<label for="bookid">enter book name :</label><input type="text" name="bookid" id="bookid" />
<p><?php echo $output; ?></p>
<input type="submit" name="submit" value="submit">
</form>
</body>
</html>
