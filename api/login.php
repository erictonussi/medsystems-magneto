<?php
$ch = curl_init('http://177.188.121.233:8180/mge/service.sbr?serviceName=MobileLoginSP.login');

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
  '<serviceRequest serviceName="MobileLoginSP.login">
      <requestBody>
        <NOMUSU>lojav</NOMUSU>
        <INTERNO>medsys2017</INTERNO>
      </requestBody>
    </serviceRequest>'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: text/xml;charset=ISO-8859-1',
    'User-Agent: teste'
    ));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);

$xml = curl_exec($ch);
curl_close($ch);

$parsed = new SimpleXMLElement($xml);
// print_r($parsed);

$GLOBALS['jsessionid'] = $parsed->responseBody->jsessionid;
// echo $parsed->responseBody->jsessionid;

?>
