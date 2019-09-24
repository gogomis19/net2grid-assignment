<?php

// we need this to create a new connection to the RabbitMQ server
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPConnection;

// we load 'autoload.php' file
require_once  'C:\xampp\htdocs\assignment\vendor\autoload.php';  // You can also try: __DIR__ . '/vendor/autoload.php';

// we create a function which is used to process the message that was sent from the publisher
$callback = function($msg) {

  // we print the message
	echo 'Message received = ',$msg->body,"\n";	

  // we convert the message from JSON format to array format
	$message = json_decode($msg->body,true);	
 
  // we assing variables to each field of the message
	$gatewayEui = $message['gatewayEui'];
	$profileId = $message['profileId'];
	$endpointId = $message['endpointId'];
	$clusterId = $message['clusterId'];
	$attributeId = $message['attributeId'];
	$value = $message['value'];
	$timestamp = $message['timestamp'];

	// we create a connection to the candidate database (host,user,password,database_name)
    $conn = new mysqli('candidaterds.n2g-dev.net', 'candidate', 'hqRkWQNsJy3TfCKwAh4A8gr', 'candidate');

    // we check if the connection is successfully established
    if ($conn->connect_error) {
       // if the connection fails then we print a message and the script ends
       die("Connection failed: " . $conn->connect_error);
    } else {
       // if the connection is successfully established then we create the query to insert the data, in the table data of our candidate database
       $sql = "INSERT INTO data 
               VALUES ('NULL', '$gatewayEui', '$profileId', '$endpointId', '$clusterId', '$attributeId', '$value', '$timestamp')";
    }

    // we execute the query and if it is successfully executed we print a message that the record has been created, otherwise we print an error message
    if ($conn->query($sql) === TRUE) {
       echo "New record created successfully";
    } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
    }

  // we close the connection to the database
  $conn->close();
	
};

// we create a function which will construct the routing key and return it to variable $routingKey
$routingKey = function($msg) {

   // we convert the message from JSON format to array format
   $arr = json_decode($msg, true);
   return  $arr['gatewayEui'].'.'.$arr['profileId'].'.'.$arr['endpointId'].'.'.$arr['clusterId'].'.'.$arr['attributeId'];
};

// we create a new connection by creating a new instance of the AMQPStreamConnection class (host,port,user,password)
$connection = new AMQPStreamConnection('candidatemq.n2g-dev.net','','candidate','Crs$4tDzX}W_Jh35mp');

// we create a channel
$channel = $connection->channel();

// we create the reults exchange, which is of type topic
$channel->exchange_declare('results', 'topic', false, true, false);

// we create the 'raw_results' queue
$channel->queue_declare( 'raw_results', false, false, false, false);

// we bind the queue with the exchange
$channel->queue_bind('raw_results', 'results', '$routingKey');

// we consume the data of the message
$channel->basic_consume('raw_results','',false,true,false,false,$callback);

// we keep the consumer active for 5 seconds so as to receive messages (we can modify this in the while condition)
$now = time();
while ($now + 5 > time()) {
	$channel->wait();
  
};

// we close the channel
$channel->close();

// we close the connection
$connection->close();

?>