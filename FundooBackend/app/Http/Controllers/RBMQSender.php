<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use PhpAmqpLib\Connection\AMQPConnection;
// use PhpAmqpLib\Connection\AMQPStreamConnection;
// use PhpAmqpLib\Message\AMQPMessage;

// class RBMQSender extends Controller
// {
//     public function sendMail($toEmail, $subject, $body)
//     {

//         $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest'); 
//         $channel = $connection->channel();

//         $channel->queue_declare('hello', false, false, false, false);
//         $data = json_encode(array(
//             "from" => "akhilesh.mc4@gmail.com",
//             "from_email" => "akhilesh.mc4@gmail.com",
//             "to_email" => $toEmail,
//             "subject" => $subject,
//             "message" => $body,
//         ));
//         $msg = new AMQPMessage($data, array('delivery_mode' => 2));                   

//         $channel->basic_publish($msg, '', 'hello');

//         $obj = new RBMQReceiver();

//         $obj->receiverMail();

//         $channel->close();

//         $connection->close();
        
        
//         return true;
//     }
// }

//$obj=new SendMail();
// $obj1=new SendMail();

//$obj->sendEmail('akhilesh.mc4@gmail.com','hi there','hi this is akhilesh');    

