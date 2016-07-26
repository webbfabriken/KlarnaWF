<?php
include 'klarna.config.php';

//this page will work only when orderID is passed to this page via $_POST or $_GET
//
$orderID = "";
try
{
	$s = $conn->prepare("SELECT * FROM orders where orderID = :orderID");
	$s->bindParam(':orderID' $orderID);
	$s->execute();
}
catch(PDOException $e)
{
	echo $e->getMessage();
}

//pseudo code for getting order data, to be edited accordingly
//fetch the orders now
while($orders = $s->fetch(PDO::FETCH_OBJ))
{
	//add products into Klarna now
	$base->addArticle(
    $orders->quantity,                 // Quantity
    "$orders->article_number",        // Article number
    "$orders->article_title", // Article name/title
    $orders->article_price,            // Price
    25,                // 25% VAT
    0,                 // Discount
    Flags::INC_VAT     // Price is including VAT.
	);
}

//we already got the details before..suppose customer details are stored in $customer variable
$addr = new Address(
    "$customer->email", // Email address
    '',                           // Telephone number, only one phone number is needed
    "$customer->cellphone",                 // Cell phone number
    "$customer->firstname",              // First name (given name)
    "$customer->lastname",                   // Last name (family name)
    '',                           // No care of, C/O
    "$customer->streetAddress",                // Street address
    "$customer->zipcode",                      // Zip code
    "$customer->city",                   // City
    Country::SE,                  // Country
    null,                         // House number (AT/DE/NL only)
    null                          // House extension (NL only)
);

$base->setAddress(Flags::IS_BILLING, $addr);
$base->setAddress(Flags::IS_SHIPPING, $addr);

try {
    $result = $base->reserveAmount(
        "$customer->personalnumber",   // PNO (Date of birth for AT/DE/NL)
        null,           // Flags::MALE, Flags::FEMALE (AT/DE/NL only)
        -1,             // Automatically calculate and reserve the cart total amount
        Flags::NO_FLAG,
        PClass::INVOICE
    );

    $rno = $result[0];
    $status = $result[1];

    // $status is Flags::PENDING or Flags::ACCEPTED.

    echo "OK: reservation {$rno} - order status {$status}\n";
} catch (\Exception $e) {
    echo "{$e->getMessage()} (#{$e->getCode()})\n";
}
?>