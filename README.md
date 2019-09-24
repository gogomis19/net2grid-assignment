# net2grid-assignment
This is the assignment project for Net2Grid

The net2grid assignment project involves:
* Consuming data from an API, that mimics the dataset which is received from Net2Grid gateways into the platform, send the results to an exchange on a RabbitMQ instance where they are filtered 
*  Consuming the filtered results from a queue and store these in a new database

The Homepage of the project contains two buttons, one for each of the above actions.

On the event of clicking the first button, the 'get_data' Route is loaded, which is used for connection to the API, creation of the 'results' exchange of a RabbitMQ instance, construction of the exchange's routing key and capture of data to a message.

On the event of clicking the second button, the 'consume_data' Route is loaded, which executes the 'Net2GridConsumer.php' file in order to create the 'raw_results' queue, bind this queue with the 'results' exchange and consume the data of the message. The data of the message are inserted into the table 'data' of the 'candidate' database. The 'candidate' database consists of a table which is called 'data' with the following properties:

**Property Name, Type, Length, Required**
* id                  integer         255                NOT NULL                 AUTO_INCREMENT  (automatically created)
* gatewayEui          string          255                NOT NULL
* profileId           string          255                NOT NULL  
* endpointId          string          255                NOT NULL
* clusterId           string          255                NOT NULL
* attributeId         string          255                NOT NULL
* value               integer                            NOT NULL
* timestamp           integer                            NOT NULL


The database and all its componenets (entities, properties e.t.c) were created with Doctrine. Corresponding files reside in 'Entity', 'Migrations' and 'Repository' directories in 'src' directory. The connection to the database for inserting the data was accomplished with MYSQLi extension. XAMPP was used as the development environment and Symfony 4 was used for its set of PHP Components (Routes, Controllers e.t.c).

The project was tested successfully locally on a desktop computer using localhost as a host, a mysql database and a RabbitMQ server which handled exchanges and queues.
