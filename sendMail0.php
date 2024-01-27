<?php

/* send mail using graph_i3c_mailer class, by Kumara HHC 2023
 */

include_once 'graph_i3c_mailer.php';


$sClientID = "xxxxxx";
$sTenantID = "xxxxxxxxx";
//$sClientSecret = "xxxxxxxxxxxxxx";
$sClientSecret="xxxxxxxxxxxx";

$mailer=new graphI3cMailer($sTenantID, $sClientID, $sClientSecret);
$mailer->Token=$mailer->getToken();

$mailArgs =  array('subject' => 'Test message',
    'replyTo' => array('name' => 'cinnamonlife_career', 'address' => 'cinnamonlife_careers@cinnamonhotels.com'),
    'toRecipients' => array( 
        array('name' => 'Kumara', 'address' => 'kumara@abc.com'),
        array('name' => 'Isuru', 'address' => 'isuru@abc.com')
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
    if($mailer->sendMail('user@xyz.com', $mailArgs)){
        print "Mail Sent";
    }
    else{
        print "Mail Not Sent";
    }

