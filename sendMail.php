<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include_once 'graph_i3c_mailer.php';


$sClientID = "xxxxxxxxxxxxxx";
$sTenantID = "xxxxxxxxxxxxxxx";
$sClientSecret="xxxxxxxxxxxxxxx";

$mailer=new graphI3cMailer($sTenantID, $sClientID, $sClientSecret);
$mailer->Token=$mailer->getToken();

$mailArgs =  array('subject' => 'Test message',
    'replyTo' => array('name' => 'kumara', 'address' => 'kumara@xyz.com'),
    'toRecipients' => array( 
        array('name' => 'Tharanga', 'address' => 'tharanga@xyz.com'),
        //array('name' => 'Isuru', 'address' => 'isuru@i3cubes.com')
    ),     // name is optional
    'importance' => 'normal',
    'conversationId' => '',   //optional, use if replying to an existing email to keep them chained properly in outlook
    'body' => "<html>Blah blah blah</html>",
    'images' => array(
        array('Name' => 'logo.png', 'ContentType' => 'image/png', 'Content' => file_get_contents("logo.png"), 'ContentID' => 'cid:blah')
    ),   //array of arrays so you can have multiple images. These are inline images. Everything else in attachments.
    'attachments' => array(
        //array('Name' => 'blah.pdf', 'ContentType' => 'application/pdf', 'Content' => 'results of file_get_contents(blah.pdf)')
    )
    );
    
    //print $mailer->createMessageJSON($mailArgs);
    if($mailer->sendMail('kumara@xyz.com', $mailArgs)){
        print "Mail Sent";
    }
    else{
        print "Mail Not Sent";
    }
    
    
    //$messages = $mailer->getMessages('cinnamonlife_careers@cinnamonhotels.com');
    //echo '<pre>';
    //print_r($messages);
    //echo '</pre>';

