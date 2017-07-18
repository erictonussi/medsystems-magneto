<?php

include 'login.php';

$ch = curl_init('http://177.188.121.233:8180/mgecom/service.sbr?serviceName=ConsultaProdutosSP.getDetalhesPrecos&mgeSession='.$GLOBALS['jsessionid']);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
  '<serviceRequest serviceName="ConsultaProdutosSP.getDetalhesPrecos">
    <requestBody>
    <criterio CODPROD="46" RESOURCEID="br.com.sankhya.com.cons.consultaProdutos"/>
    </requestBody>
  </serviceRequest>'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: text/xml;charset=ISO-8859-1',
  'User-Agent: teste'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);

curl_setopt($ch, CURLOPT_COOKIE, 'JSESSIONID='.$GLOBALS['jsessionid']);

$xml = curl_exec($ch);
curl_close($ch);

// var_dump($xml);
$parsed = new SimpleXMLElement($xml);
print_r($parsed);

?>
