<?php
require_once 'autoload.php';

use Klarna\XMLRPC\Klarna;
use Klarna\XMLRPC\Country;
use Klarna\XMLRPC\Language;
use Klarna\XMLRPC\Currency;

class base extends Klarna
{
	function accessProtected($obj, $prop) 
	{
	  $reflection = new ReflectionClass($obj);
	  $property = $reflection->getProperty($prop);
	  $property->setAccessible(true);
	  return $property->getValue($obj);
	}
}

$base = new base();

$base->config(
    6945,              // Merchant ID
    'AsUL5TGfCSTnLqe', // Shared secret
    Country::SE,    // Purchase country
    Language::SV,   // Purchase language
    Currency::SEK,  // Purchase currency
    Klarna::BETA    // Server
);
?>