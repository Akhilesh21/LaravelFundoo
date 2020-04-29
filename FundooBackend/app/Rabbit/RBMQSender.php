<?php

namespace App\Rabbit;
use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPConnection;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RBMQSender
{
    public function sendMail($toEmail, $subject, $message)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

        $channel = $connection->channel();
        $channel->queue_declare('email_queue', false, false, false, false);
        $data['toEmail'] = $toEmail;
        $data['subject'] = $subject;
        $data['message'] = $message;
        $data = json_encode($data);
        $msg = new AMQPMessage($data, array('delivery_mode' => 2));
        $channel->basic_publish($msg, '', 'email_queue');

        $receiver = new RBMQReceiver();
        $receiver->receiveMail();
        $channel->close();
        $connection->close();
        return true;

     }
}