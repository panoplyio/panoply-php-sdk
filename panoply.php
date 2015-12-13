<?php

class Panoply {

    public static $PKGNAME = "panoply-python-sdk";
    public static $VERSION = "1.0.0";

    public $apikey;
    public $apisecret;
    public $qurl;

    function __construct ( $apikey, $apisecret ) {
        $this->apikey = $apikey;
        $this->apisecret = $apisecret;

        // decompose the api key and secret
        // api-key: ACCOUNT/RAND1
        // api-secret: BASE64( RAND2/UUID/AWSACCOUNT/REGION )
        $decoded = explode( "/", base64_decode( $apisecret ) );
        $rand = $decoded[ 0 ];
        $awsaccount = $decoded[ 2 ];
        $region = $decoded[ 3 ];
        $account = explode( "/", $apikey )[ 0 ];

        $this->qurl = sprintf( "https://sqs.%s.amazonaws.com/%s/sdk-%s-%s",
            $region,
            $awsaccount,
            $account,
            $rand );

        $this->buffer = "";
    },

    function write( $table, $data ) {
        $data[ "__table" ] = $table;
        $data = json_encode( $data );
        $data = urlencode( $data );
        $this->buffer .= $data . "\n";
    }

    function send () {
        $body = $this->buffer;
        $this->buffer = "";

        $body = array(
            "Action=SendMessage",
            "MessageBody=" . $body,
            "MessageAttribute.1.Name=key",
            "MessageAttribute.1.Value.DataType=String",
            "MessageAttribute.1.Value.StringValue=" . $this->apikey,
            "MessageAttribute.2.Name=secret",
            "MessageAttribute.2.Value.DataType=String",
            "MessageAttribute.2.Value.StringValue=" . $this->apisecret,
            "MessageAttribute.3.Name=sdk",
            "MessageAttribute.3.Value.DataType=String",
            "MessageAttribute.3.Value.StringValue=" . $this->PKGNAME . "-" . $this->VERSION,
        );

        $body = join( "&", $body );

        $headers = array(
            "Content-Length" => strlen( $body ),
            "Content-Type" => "application/x-www-form-urlencoded"
        )

        $options = array(
            "headers" => $headers
        )

        http_post_data( $this->qurl, $body, $options );
    }


}




