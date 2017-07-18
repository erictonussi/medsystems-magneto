<?php

// include 'login.php';

$ch = curl_init('http://177.188.121.233:8180/mgecom/service.sbr?serviceName=ConsultaProdutosSP.getDetalhesEstoques&mgeSession=D75074EDF127F8E096F3FE0DA9B1D3FE');

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
  '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
    <requestBody>
      <criterio RESOURCEID="br.com.sankhya.com.cons.consultaProdutos"
      CODPROD="2"
      MANDARVOLUMES="true"
      >
            <filtroPersonalizado>
              <expression>(this.CODEMP = 2)</expression>
            </filtroPersonalizado>
          </criterio>
    </requestBody>
  </serviceRequest>'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: text/xml;charset=ISO-8859-1',
  'User-Agent: teste'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);

curl_setopt($ch, CURLOPT_COOKIE, 'JSESSIONID=D75074EDF127F8E096F3FE0DA9B1D3FE');

$xml = curl_exec($ch);
curl_close($ch);

var_dump($xml);
$parsed = new SimpleXMLElement($xml);
print_r($parsed);

?>
