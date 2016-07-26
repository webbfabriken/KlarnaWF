<?php
include 'klarna.config.php';
$base->setCountry('se'); // Sweden only

//get the personal number here
$personal_number = isset($_GET['personal_number']) ? $_GET['personal_number'] : '';
if(!empty($personal_number))
{
	try 
	{
	    $addrs = $base->getAddresses("$personal_number");

	    // $addrs is a list of Address instances.
	    //var_dump($addrs);
	    //echo "OK\n";
	} catch (\Exception $e) {
	    echo "{$e->getMessage()} (#{$e->getCode()})\n";
	}
	//get values now
	$firstname = $base->accessProtected($addrs[0], 'fname');
	echo $firstname;
}
?>