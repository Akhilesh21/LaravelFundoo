<?php

namespace App\Rabbit;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RBMQReceiver
{
    public function receiveMail()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('email_queue', false, false, false, false);

        $callback = function ($msg) {

            $data = json_decode($msg->body, true);

            $from = 'Akhilesh';
            $from_email = 'akhilesh.mc4@gmail.com';
            $to_email = $data['toEmail'];
            $subject = $data['subject'];
            $message = $data['message'];

            $transporter = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                ->setUsername('akhilesh.mc4@gmail.com')
                ->setPassword('9972693247');

            $mailer = new \Swift_Mailer($transporter);

            $body = (new \Swift_Message($transporter))
                ->setSubject($subject)
                ->setFrom([$from_email => $from])
                ->setTo(array($to_email))
                ->setBody($message);

            $mailer->send($body);
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('email_queue', '', false, false, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
          break;
        }

        

    }
}