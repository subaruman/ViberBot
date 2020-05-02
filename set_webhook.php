<?php
$auth_token = '4b5e762590e7ddb8-30a31dbdae9b3a73-6e442410ff4620ee';
$webhook = 'https://www.ulstu.club/viber-bot/';


$jsonData =
    '{
		"auth_token": "' . $auth_token . '",
		"url": "' . $webhook . '",
		"event_types":[
          "delivered",
          "seen",
          "failed",
          "subscribed",
          "unsubscribed",
          "conversation_started"
          ],
         "send_name": true,
         "send_photo": true
	}';


$curl = curl_init("https://chatapi.viber.com/pa/set_webhook");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($curl, CURLOPT_HTTPHEADER,
    [
        "Cache-Control: no-cache",
        "Content-Type: application/JSON",
        "X-Viber-Auth-Token: $auth_token"
    ]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}


/*$ch = curl_init('https://chatapi.viber.com/pa/set_webhook');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);
if ($err) {
    echo($err);
} else {
    echo($response);
}*/
?>
