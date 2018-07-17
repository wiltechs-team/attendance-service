<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2018/7/16
 * Time: 22:11
 */
namespace Wiltechsteam\FoundationServiceSingle\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

use Wiltechsteam\FoundationServiceSingle\LoggerHandler;

class FoundationSingleCommand extends Command
{
    protected $signature = 'foundation:single';

    protected $description = 'Foundation Queue Work Attendance OA';

    protected $loggerHandler;

    public function __construct()
    {
        parent::__construct();

        $this->loggerHandler = new LoggerHandler();
    }

    public function handle()
    {
        ini_set("memory_limit","1024M");
        $queue = config('foundationsingle.rabbitmq_queue');
        $consumerTag = 'consumer-' . getmypid();
        $connection = new AMQPStreamConnection(
            config('foundationsingle.rabbitmq_host'),
            config('foundationsingle.rabbitmq_port'),
            config('foundationsingle.rabbitmq_login'),
            config('foundationsingle.rabbitmq_password')
        );
        $channel = $connection->channel();

        $channel->queue_declare($queue, true, false, true, false);

        $channel->basic_qos(0, 1, false);

        $channel->basic_consume($queue, $consumerTag, false, false, false, false, function($e)
        {
            $this->callback($e);
        });

        while (count($channel->callbacks))
        {
            $channel->wait();
        }
        $channel->close();

        $connection->close();
    }

    private function callback($queue)
    {
        $body = $queue->body;

        $bodyData = json_decode($body,true);

        $fromExchange = $queue->delivery_info['exchange'];


        $this->loggerHandler->messageQueueLog($fromExchange, $bodyData);

        $this->info(date('Y-m-d H:i:s') . ' ' . $fromExchange . ' - ' . 'succeed');

        $this->bindEvent($fromExchange, $bodyData);

        // RabbitMQ ack 回复
        $queue->delivery_info['channel']->basic_ack($queue->delivery_info['delivery_tag']);
    }

    private function bindEvent($exchangeName, $bodyData)
    {
        if (strpos($exchangeName, ':') === false)
        {
            throw new \Exception('Exchange name is illegality.');
        }

        $exchangeNames = explode(':', $exchangeName);

        $eventClass = "Wiltechsteam\\FoundationServiceSingle\\Events\\" . $exchangeNames[1];

        if (!class_exists($eventClass))
        {
            throw new \Exception("Event '$eventClass' is not found.");
        }

        return event(new $eventClass($bodyData["message"]));
    }
}