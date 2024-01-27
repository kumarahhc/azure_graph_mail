<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include_once 'graph_i3c_mailer.php';


$sClientID = "ae35949b-e5a5-4bfb-8d5b-c1f817927e59";
$sTenantID = "9d95cde8-c4ec-4b9c-8ee2-249d44b79acf";
//$sClientSecret = "WuU8Q~GLZU2u7KnTv7RGUVJ3Vqc3osWcHXdYsbJm";
$sClientSecret="Teh8Q~P0SQTnZIekWzTAfD0XrXnfl7FRoN7fnbMK";

//$mailer=new graphI3cMailer($sTenantID, $sClientID, $sClientSecret);
//$mailer->Token=$mailer->getToken();
$ch1 = curl_init ();
//127.0.0.1/restCore/public/api/azureLogin
        curl_setopt ($ch1, CURLOPT_URL, "https://175.188.81.43/restCore/public/api/mail");
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS,"userName=test");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode (curl_exec ($ch1), 1);
        
        $httpCode = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
        print "AAA";
        print_r($httpCode);
        
        print_r($res);
        curl_close ($ch1);

        exit();
$mailArgs =  array('subject' => 'Test message',
    'replyTo' => array('name' => 'cinnamonlife_career', 'address' => 'cinnamonlife_careers@cinnamonhotels.com'),
    'toRecipients' => array( 
        array('name' => 'Tharanga', 'address' => 'anuradha.spta@gmail.com'),
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
    if($mailer->sendMail('cinnamonlife_careers@cinnamonhotels.com', $mailArgs)){
        print "Mail Sent";
    }
    else{
        print "Mail Not Sent";
    }
    
    
    //$messages = $mailer->getMessages('cinnamonlife_careers@cinnamonhotels.com');
    //echo '<pre>';
    //print_r($messages);
    //echo '</pre>';

