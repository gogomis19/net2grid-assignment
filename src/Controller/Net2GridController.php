<?php

namespace App\Controller;

// we need this to create the controller
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// we need this to create the routes
use Symfony\Component\Routing\Annotation\Route;
// we need this to create the request to the URL
use Symfony\Component\HttpFoundation\Request;
//we need this to create the client for the request to the URL
use Symfony\Component\HttpClient\HttpClient;
// we need this to create a new connection to the RabbitMQ server
use PhpAmqpLib\Connection\AMQPStreamConnection;
// we need this to create messages that we can push to the queue
use PhpAmqpLib\Message\AMQPMessage;

// Controller
class Net2GridController extends AbstractController
{
    /**
     * @Route("/", name="net2_grid")
     */
    public function index()
    {
        // load 'index.html.twig' page
        return $this->render('index.html.twig');
    }


    /**
     * @Route("/get-data", name="get_data")
     */
    public function getData()   // getData function will capture data from the API and will create the results exchange
    {
    	// we create a client 
        $client = HttpClient::create();

        // we make a request to 'https://x3en0vtak6.execute-api.eu-west-1.amazonaws.com/prod/results' to capture data
        $response = $client->request('GET', 'https://x3en0vtak6.execute-api.eu-west-1.amazonaws.com/prod/results');

        // we get the $statusCode = 200
        $statusCode = $response->getStatusCode();
        
        // data that were captured in JSON format
        // $content = '{"gatewayEui":84df0c0078479200, "profileId":"0x0104", ...}'
        $message = $response->getContent();

        // we convert the message from JSON format to array format
        $arr = json_decode($message, true);

        // we define the routing key, which consists of 5 fields
        // we are using the decimal representation for gatewayEui, profileId, endpointId, clusterId, attributeId
        $x = hexdec($arr['gatewayEui']);
        //we use sprintf to get the large number without scientific notation
        $gatewayEui = sprintf("%.0f",$x);


        $routingKey = $gatewayEui.'.'.hexdec($arr['profileId']).'.'.hexdec($arr['endpointId']).'.'.hexdec($arr['clusterId']).'.'.hexdec($arr['attributeId']);


        // we load 'autoload.php' file
        require_once  'C:\xampp\htdocs\assignment_n2g\vendor\autoload.php';   // You can also try: __DIR__ . '/vendor/autoload.php';
        
        // we create a new connection by creating a new instance of the AMQPStreamConnection class (host,port,user,password)
        $connection = new AMQPStreamConnection('candidatemq.n2g-dev.net','','candidate','Crs$4tDzX}W_Jh35mp');

        // we create a channel
        $channel = $connection->channel();

        // we create the reults exchange, which is of type topic
        $channel->exchange_declare('results', 'topic', false, true, false);

        // we create the message
        $msg = new AMQPMessage($message);

        // we publish the message, this requires 3 arguments:  the message, the exchange and the routing key
        $channel->basic_publish($msg,'results','$routingKey');

        // we close the channel
        $channel->close();

        // we close the connection
        $connection->close();
       
        // printing info
        echo('<h5>Data captured from the API and sent to results exchange</h5>');
        echo('<br>');        
        echo('<br>');

        
        // reload 'index.html.twig' page
        return $this->render('index.html.twig');
               
    }

   

}
