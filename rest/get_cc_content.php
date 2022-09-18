<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.cc.email/v3/emails/activities/7387a854-28ad-4f7e-9819-09758ea3c66e?include=html_content',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Content-type: application/json',
    'Authorization: Bearer DvoMyXkkZ9lZ6yuNS4Twox0wK5Vm'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;