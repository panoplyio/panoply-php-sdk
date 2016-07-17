<?php

require_once( "panoply.php" );

$KEY = "";
$SECRET = "";

$sdk = new panoply\SDK( $KEY, $SECRET );
$sdk->write( "sdktest", array(
    "hello" => "world",
    "foo" => "bar"
));

$sdk->flush();