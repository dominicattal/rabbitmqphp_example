<?php

/**
    @brief gets the currrent machine information and optionally any other task
    specific INI folder installed in the system path.
    Uses the default path of /var/system_ini/ unless $INIPATH is set

    @return parsed ini machine information
*/
function getHostInfo($ini)
{
    return parse_ini_file($ini,true);
}

class rabbitMQServer
{
	public $machine;
	public $HOST;
	public $PORT;
	public $USER;
	public $PASSWORD;
	public $VHOST;
	public $exchange_name;
	public $exchange_type;
	public $listen_queue_name;
    public $listen_queue;
	public $routing_key;
    public $send_queue_name;
    public $send_routing_key;
    public $callback;
    public $send_responses;

	function __construct($ini, $server = "rabbitMQ")
	{
		$this->machine              = getHostInfo($ini);
		$this->HOST                 = $this->machine[$server]["MQ_HOST"];
		$this->PORT                 = $this->machine[$server]["MQ_PORT"];
		$this->USER                 = $this->machine[$server]["MQ_USER"];
		$this->PASSWORD             = $this->machine[$server]["MQ_PASSWORD"];
		$this->VHOST                = $this->machine[$server]["MQ_VHOST"];
		$this->exchange_name        = $this->machine[$server]["MQ_EXCHANGE_NAME"];
		$this->exchange_type        = $this->machine[$server]["MQ_EXCHANGE_TYPE"];
		$this->listen_queue_name    = $this->machine[$server]["MQ_LISTEN_QUEUE_NAME"];
        $this->listen_routing_key   = $this->machine[$server]["MQ_LISTEN_ROUTING_KEY"];
        $this->send_responses       = isset($this->machine[$server]["MQ_SEND_RESPONSE"]) && $this->machine[$server]["MQ_SEND_RESPONSE"] === "true";
        if ($this->send_responses) {
            $this->send_queue_name      = $this->machine[$server]["MQ_SEND_QUEUE_NAME"];
            $this->send_routing_key     = $this->machine[$server]["MQ_SEND_ROUTING_KEY"];
        }
	}

	function process_message($msg)
	{
		$this->listen_queue->ack($msg->getDeliveryTag());
		try
		{
            $body = $msg->getBody();
            $payload = json_decode($body, true);
            $payload["routing_key"] = $msg->getRoutingKey();
            if (isset($this->callback))
                $response = call_user_func($this->callback,$payload);

            if (!$this->send_responses)
                return;

            $params = array();
            $params['host'] = $this->HOST;
            $params['port'] = $this->PORT;
            $params['login'] = $this->USER;
            $params['password'] = $this->PASSWORD;
            $params['vhost'] = $this->VHOST;
            $conn = new AMQPConnection($params);
            $conn->connect();
            $channel = new AMQPChannel($conn);
            $exchange = new AMQPExchange($channel);
            $exchange->setName($this->exchange_name);
            $exchange->setType($this->exchange_type);

            $send_queue = new AMQPQueue($channel);
            $send_queue->setName($this->send_queue_name);
            $replykey = $this->send_routing_key;
            $send_queue->bind($exchange->getName(),$replykey);
            echo "responded\n";
            $exchange->publish(json_encode($response),$replykey,AMQP_NOPARAM,array('correlation_id'=>$msg->getCorrelationId()));
		}
		catch(Exception $e)
		{
			// ampq throws exception if get fails...
			echo "error: rabbitMQServer: process_message: exception caught: ".$e;
		}
	}

	function process_requests($callback)
	{
		try
		{
			$this->callback = $callback;
			$params = array();
			$params['host'] = $this->HOST;
			$params['port'] = $this->PORT;
			$params['login'] = $this->USER;
			$params['password'] = $this->PASSWORD;
			$params['vhost'] = $this->VHOST;
			$conn = new AMQPConnection($params);
			$conn->connect();

			$channel = new AMQPChannel($conn);

			$exchange = new AMQPExchange($channel);
			$exchange->setName($this->exchange_name);
			$exchange->setType($this->exchange_type);

			$this->listen_queue = new AMQPQueue($channel);
			$this->listen_queue->setName($this->listen_queue_name);
			$this->listen_queue->bind($this->exchange_name,$this->listen_routing_key);

			$this->listen_queue->consume(array($this,'process_message'));

			// Loop as long as the channel has callbacks registered
			while (count($channel->callbacks))
				$channel->wait();
		}
		catch (Exception $e)
		{
			trigger_error("Failed to start request processor: ".$e,E_USER_ERROR); 
		}
	}
}

class rabbitMQClient
{
	public $machine;
	public $HOST;
	public $PORT;
	public $USER;
	public $PASSWORD;
	public $VHOST;
	public $exchange_name;
	public $exchange_type;
	public $send_queue_name;
	public $reply_queue_name;
	public $send_routing_key;
	public $reply_routing_key;
    public $send_queue;
    public $response;
    public $correlation_id;

	function __construct($ini, $server = "rabbitMQ")
	{
		$this->machine              = getHostInfo($ini);
		$this->HOST                 = $this->machine[$server]["MQ_HOST"];
		$this->PORT                 = $this->machine[$server]["MQ_PORT"];
		$this->USER                 = $this->machine[$server]["MQ_USER"];
		$this->PASSWORD             = $this->machine[$server]["MQ_PASSWORD"];
		$this->VHOST                = $this->machine[$server]["MQ_VHOST"];
		$this->exchange_name        = $this->machine[$server]["MQ_EXCHANGE_NAME"];
		$this->exchange_type        = $this->machine[$server]["MQ_EXCHANGE_TYPE"];
		$this->send_routing_key     = $this->machine[$server]["MQ_SEND_ROUTING_KEY"];
		$this->reply_routing_key    = $this->machine[$server]["MQ_REPLY_ROUTING_KEY"];
		$this->send_queue_name      = $this->machine[$server]["MQ_SEND_QUEUE_NAME"];
		$this->reply_queue_name     = $this->machine[$server]["MQ_REPLY_QUEUE_NAME"];
	}

	function process_response(AMQPEnvelope $response)
	{
		$correlation_id = $response->getCorrelationId();
        if ($correlation_id !== $this->correlation_id)
            return true;
		if (!isset($this->response))
		{
			echo  "unknown uid\n";
			return true;
		}
		$this->send_queue->ack($response->getDeliveryTag());
		$body = $response->getBody();
		$payload = json_decode($body, true);
		if (!(isset($payload)))
		{
			$payload = "[empty response]";
		}
		$this->response = $payload;
		return false;
	}

	function send_request($message)
	{
		$this->correlation_id = uniqid();
		$json_message = json_encode($message);
		try
		{
			$params = array();
			$params['host'] = $this->HOST;
			$params['port'] = $this->PORT;
			$params['login'] = $this->USER;
			$params['password'] = $this->PASSWORD;
			$params['vhost'] = $this->VHOST;

			$conn = new AMQPConnection($params);
			$conn->connect();

			$channel = new AMQPChannel($conn);

            // exchange, response_queue, conn_queue are assumed to exist on the broker
            // since declare is not called, these should fail if they do not exist
			$exchange = new AMQPExchange($channel);
			$exchange->setName($this->exchange_name);
			$exchange->setType($this->exchange_type);

			$reply_queue = new AMQPQueue($channel);
			$reply_queue->setName($this->reply_queue_name);
			$reply_queue->bind($this->exchange_name,$this->reply_routing_key);

			$this->send_queue = new AMQPQueue($channel);
			$this->send_queue->setName($this->send_queue_name);
			$this->send_queue->bind($this->exchange_name,$this->send_routing_key);

			$exchange->publish($json_message,$this->send_routing_key,AMQP_NOPARAM,array('correlation_id'=>$this->correlation_id));

			$reply_queue->consume(array($this,'process_response'));

			$response = $this->response;
            unset($this->response);

			return $response;
		}
		catch(Exception $e)
		{
			die("failed to send message to exchange: ". $e->getMessage()."\n");
		}
	}

	/**
	  @brief send a one-way message to the server.  These are
	  auto-acknowledged and give no response.

	  @param message the body of the request.  This must make sense to the
	  server
	 */
	function publish($message)
	{
		$json_message = json_encode($message);
		try
		{
			$params = array();
			$params['host'] = $this->HOST;
			$params['port'] = $this->PORT;
			$params['login'] = $this->USER;
			$params['password'] = $this->PASSWORD;
			$params['vhost'] = $this->VHOST;
			$conn = new AMQPConnection($params);
			$conn->connect();
			$channel = new AMQPChannel($conn);
			$exchange = new AMQPExchange($channel);
			$exchange->setName($this->exchange_name);
			$exchange->setType($this->exchange_type);
			$this->send_queue = new AMQPQueue($channel);
			$this->send_queue->setName($this->send_queue_name);
			$this->send_queue->bind($this->exchange_name,$this->send_routing_key);
			return $exchange->publish($json_message,$this->send_routing_key);
		}
		catch(Exception $e)
		{
			die("failed to send message to exchange: ". $e->getMessage()."\n");
		}
	}
}
?>

