<?php
 
// Specify your Office 365 credentials
$clientId = "xxxxxxxxxxxxxxxx";
$clientSecret = "xxxxxxxxxxxxxx";
$tenantId = "xxxxxxxxxxxxxxxx";
$userEmail = "user@xzy.com";
 
$recipientEmail = "kumara@abc.com";
$subject = "Test email subject";
$body = "Hi Boys";
 
$scopes = "https://graph.microsoft.com/.default";
$authority = "https://login.microsoftonline.com/$tenantId";
 
// Authenticate to Azure AD
$tokenEndpoint = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";
$tokenRequestBody = [
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'grant_type' => 'client_credentials',
    'scope' => $scopes,
];
 
$ch = curl_init($tokenEndpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenRequestBody));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
$response = curl_exec($ch);
curl_close($ch);
 
$result = json_decode($response, true);

print_r($result);
$accessToken = $result['access_token'];
 
// Configure the Graph API request headers
$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json',
];
 
// Create the email message
$emailData = [
    "message"=>[
        'subject' => $subject,
        'body' => [
            'content' => $body,
            'contentType' => 'Text',
        ],
        'toRecipients' => [
            [
                'emailAddress' => [
                    'address' => $recipientEmail,
                ],
            ],
        ],
        "attachments"=>[
        [
          "@odata.type"=>"#microsoft.graph.fileAttachment",
          "name"=>"attachment.txt",
          "contentType"=>"text/plain",
          "contentBytes"=>"SGVsbG8gV29ybGQh"
        ]
      ]
    ]
];
 
print json_encode($emailData);
// Send the email message
$graphEndpoint = "https://graph.microsoft.com/v1.0/users/$userEmail/sendMail";
$ch = curl_init($graphEndpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
$response = curl_exec($ch);
curl_close($ch);
 
print_r($response);
echo "Email sent successfully!";

?>