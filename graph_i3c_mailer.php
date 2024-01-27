<?php
/*
 *   Azure mail send by Kumara HHC
 *
 *   Sends messages from an Exchange Online mailbox using MS Graph API
 *	 Based on Katy Nicholson post on https://katystech.blog/2020/08/php-graph-mailer/
 */
class graphI3cMailer {

    var $tenantID;
    var $clientID;
    var $clientSecret;
    var $Token;
    var $baseURL;
    var $tokenEndpoint;
    
    var $write_log=false;

    function __construct($sTenantID, $sClientID, $sClientSecret) {
        $this->tenantID = $sTenantID;
        $this->clientID = $sClientID;
        $this->clientSecret = $sClientSecret;
        $this->baseURL = 'https://graph.microsoft.com/v1.0/';
        //$this->Token = $this->getToken();
        $this->tokenEndpoint = "https://login.microsoftonline.com/$this->tenantID/oauth2/v2.0/token";
    }

    function getToken() {
        $scopes = "https://graph.microsoft.com/.default";
        $tokenRequestBody = [
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => $scopes,
        ];
        
        $ch = curl_init($this->tokenEndpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenRequestBody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        if($this->write_log){
            writeLog((array) $result);
        }
        //print_r($result);
        $accessToken = $result['access_token'];
        return $accessToken;
    }

	function createMessageJSON($messageArgs) {
            /*
                $messageArgs[   subject,
                replyTo{'name', 'address'},
                toRecipients[]{'name', 'address'},
                ccRecipients[]{'name', 'address'},
                importance,
                conversationId,
                body,
                attachments[]
            ]

            */
            $messageArray = array();
            if (array_key_exists('toRecipients', $messageArgs)) {
                foreach ($messageArgs['toRecipients'] as $recipient) {
                    if (array_key_exists('name', $recipient)) {
                            $messageArray['toRecipients'][] = array('emailAddress' => array('name' => $recipient['name'], 'address' => $recipient['address']));
                    } else {
                            $messageArray['toRecipients'][] = array('emailAddress' => array('address' => $recipient['address']));
                    }
                }
            }
            if (array_key_exists('ccRecipients', $messageArgs)) {
                foreach ($messageArgs['ccRecipients'] as $recipient) {
                    if (array_key_exists('name', $recipient)) {
                        $messageArray['ccRecipients'][] = array('emailAddress' => array('name' => $recipient['name'], 'address' => $recipient['address']));
                    } else {
                        $messageArray['ccRecipients'][] = array('emailAddress' => array('address' => $recipient['address']));
                    }
                }
            }
            if (array_key_exists('bccRecipients', $messageArgs)) {
                foreach ($messageArgs['bccRecipients'] as $recipient) {
                    if (array_key_exists('name', $recipient)) {
                        $messageArray['bccRecipients'][] = array('emailAddress' => array('name' => $recipient['name'], 'address' => $recipient['address']));
                    } else {
                        $messageArray['bccRecipients'][] = array('emailAddress' => array('address' => $recipient['address']));
                    }
                }
            }
            if (array_key_exists('subject', $messageArgs)) $messageArray['subject'] = $messageArgs['subject'];
            if (array_key_exists('importance', $messageArgs)) $messageArray['importance'] = $messageArgs['importance'];
            if (isset($messageArgs['replyTo'])) $messageArray['replyTo'] = array(array('emailAddress' => array('name' => $messageArgs['replyTo']['name'], 'address' => $messageArgs['replyTo']['address'])));
            if (array_key_exists('body', $messageArgs)) $messageArray['body'] = array('contentType' => 'HTML', 'content' => $messageArgs['body']);
            
            if (array_key_exists('images', $messageArgs)) {
                foreach ($messageArgs['images'] as $image) {
                    $mageAry = array('@odata.type' => '#microsoft.graph.fileAttachment', 'name' => $image['Name'], 'contentBytes' => base64_encode($image['Content']), 'contentType' => $image['ContentType'], 'isInline' => true, 'contentId' => $image['ContentID']);
                    $messageArray['attachments'][]=$mageAry;
                }
            }
            if (array_key_exists('attachments', $messageArgs)) {
                foreach ($messageArgs['attachments'] as $attachment) {
                    $atachmmentAry = array('@odata.type' => '#microsoft.graph.fileAttachment', 'name' => $attachment['Name'], 'contentBytes' => base64_encode($attachment['Content']), 'contentType' => $attachment['ContentType'], 'isInline' => false);
                    $messageArray['attachments'][]=$atachmmentAry;
                }
            }
            return json_encode(["message"=>$messageArray]);
	}

    function sendMail($mailbox, $messageArgs) {
        if (!$this->Token) {
            throw new Exception('No token defined');
        }

        /*
        $messageArgs[   subject,
                replyTo{'name', 'address'},
                toRecipients[]{'name', 'address'},
                ccRecipients[]{'name', 'address'},
                importance,
                conversationId,
                body,
                images[],
                attachments[]
                ]

        */
        $messageJSON = $this->createMessageJSON($messageArgs);
        $response = $this->sendPostRequest($this->baseURL . 'users/' . $mailbox . '/sendMail', $messageJSON, array('Content-type: application/json'));
        //print_r($response);
        if($this->write_log){
            writeLog((array) $response);
        }
        if ($response['code'] == '202') return true;
        return false;
    }


    function sendPostRequest($URL, $Fields, $Headers = false) {
        echo $URL.PHP_EOL;
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($Fields) curl_setopt($ch, CURLOPT_POSTFIELDS, $Fields);
        if ($Headers) {
            $Headers[] = 'Authorization: Bearer ' . $this->Token;
            $Headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        return array('code' => $responseCode, 'data' => $response);
    }
    function writeLog($ary_log = []){
        $log_file="/var/log/clir_logs/email_graph_".date("Y-m").".txt";
        //$log_file="email_sent_".date("Y-m").".txt";
        $str=date("Y-m-d H:i:s")." || {".implode(",",array_keys($ary_log))."} --> {".implode(",",$ary_log)."}";
        if ($file=fopen($log_file, "w+")) {
                fputs($file,"$str \n");
                fclose($file);
        }
    }

}
?>