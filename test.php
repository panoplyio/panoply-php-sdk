<?php

require_once( 'panoply.php' );

$KEY = "color/ael0q6qa0jm7vi";
$SECRET = "ZGttNWJ1dnZhZzg1eHcyOS8wMDNhMTRiMi05NzI0LTQxM2YtYTcxMi05ODdlMjNhYjUxMzEvMDM3MzM1OTk5NTYyL3VzLWVhc3QtMQ==";

$sdk = new panoply\SDK( $KEY, $SECRET );
$sdk->write( "sdktest", array(
    "hello" => "world",
    "foo" => "bar"
));

$sdk->send();