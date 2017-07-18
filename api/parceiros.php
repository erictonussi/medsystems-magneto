<?php

// include 'login.php';

$ch = curl_init('http://177.188.121.233:8180/mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords');

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
  '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
    <requestBody>
      <dataSet rootEntity="Parceiro" includePresentationFields="S" parallelLoader="false" disableRowsLimit="false" orderByExpression="this.CODPARC">
        <entity path="">
          <fieldset list="CODPARC,NOMEPARC" />
        </entity>
        <entity path="Cidade">
          <fieldset list="NOMECID" />
        </entity>
        <entity path="Cidade.UnidadeFederativa">
          <fieldset list="UF,DESCRICAO" />
        </entity>

        <criteria>
          <expression>
            this.CODPARC &gt; 0 AND (this.CLIENTE = \'S\' AND this.DTALTER &gt; ?)
          </expression>
          <parameter type="D">01/10/2015</parameter>
        </criteria>
      </dataSet>
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

?>
