# panoply-php-sdk

Send events directly from PHP to your Panoply.io Database in Real Time.

#### Usage

```php
require_once("panoply.php");

$sdk = new panoply\SDK( "API-KEY", "API-SECRET" );
$sdk->write( "table-name", array(
    "key1" => "value1",
    "key2" => "value2",
    // ... any arbitrary data.
));

$sdk->flush()
```


#### API

This SDK is used to send arbitrary, semi-structured associative arrays to the Panoply.io database in near real time. There's no schema to maintain - what you send is what will be saved to your data warehouse. Internally, the SDK maintains a small buffer (60KB) of data in memory before flushing it out to the network. If you want to manually flush it out (for example, during a server restart or testing - you'll need to call `flush` manually) - otherwise, the `write` functions will take care of everything.

###### new SDK( apikey, apisecret )

Creates a new SDK instance with your Panoply.io credentials. 

###### .write( table, data )

Writes an event to `table`, which is a string representing the target event type. The `data` is any associative array containing the keys and values of the event. Data may be nested and have any data primitive data type.

**Note** that in order to improve performance this function doesn't always send the data to Panoply.io, and will instead save it in a small in-memory buffer. When the buffer is full, it will be flushed to the network. 

###### .flush()

Forces the SDK to flush its buffer to the network immediately. Generally, there shouldn't be a need to call this function much in production, because the `.write` function flushes the buffer automatically when it's full. However, sometimes you may want to call it manually, for example for server restarts, tests and non-blocking threads.
