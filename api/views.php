<?php

// include 'login.php';

$ch = curl_init('http://177.188.121.233:8180/mge/service.sbr?serviceName=CRUDServiceProvider.loadView');

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
  '<serviceRequest serviceName="CRUDServiceProvider.loadView">
    <requestBody>
      <query viewName="VGFFIN" orderBy="CODEMP,DTNEG">
         <fields>
            <field>CODPARC</field>
            <field>CODEMP</field>
            <field>DTNEG</field>
           <!-- exemplo de campo com expressão SQL -->
           <field>(VLRDESDOB + VLRJURO) AS VLRLIQUIDO</field>
         </fields>
         <where>VGFFIN.DHBAIXA IS NULL
                AND VGFFIN.RECDESP = 1
                AND EXISTS(
                    SELECT 1 FROM TGFFIN FIN
                    WHERE
                    FIN.NUFIN = VGFFIN.NUFIN
                )
         </where>
      </query>
    </requestBody>
  </serviceRequest>'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: text/xml;charset=ISO-8859-1',
  'User-Agent: teste'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);

curl_setopt($ch, CURLOPT_COOKIE, 'JSESSIONID=F08F80B54924F2AED0FAE971EE586A27');

$xml = curl_exec($ch);
curl_close($ch);

// var_dump($xml);
$parsed = new SimpleXMLElement($xml);
print_r($parsed);
// print_r($parsed->responseBody->entities);

?>
