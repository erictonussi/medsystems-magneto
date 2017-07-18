<?php

class Sankhya {

  var $jsessionid;

  function __construct () {

    $parsed = $this->curl_call('mge/service.sbr?serviceName=MobileLoginSP.login',
      '<serviceRequest serviceName="MobileLoginSP.login">
          <requestBody>
            <NOMUSU>lojav</NOMUSU>
            <INTERNO>medsys2017</INTERNO>
          </requestBody>
        </serviceRequest>');

    $this->jsessionid = $parsed->responseBody->jsessionid;
    // echo $parsed->responseBody->jsessionid;
  }

  function curl_call($service, $xml, $userMgeSession = false) {
    $ch = curl_init('http://177.188.121.233:8180/'.$service . ( $userMgeSession ? '&mgeSession='.$this->jsessionid : '' ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: text/xml;charset=ISO-8859-1',
        'User-Agent: teste'
        ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);

    if ( $this->jsessionid ) {
      curl_setopt($ch, CURLOPT_COOKIE, 'JSESSIONID='.$this->jsessionid);
    }

    $xml = curl_exec($ch);
    curl_close($ch);

    return new SimpleXMLElement($xml);
    // print_r($parsed);
  }

  function consulta_produtos ($expression) {
    return $this->curl_call('mgecom/service.sbr?serviceName=ConsultaProdutosSP.consultaProdutos',
      '<serviceRequest serviceName="ConsultaProdutosSP.consultaProdutos">
        <requestBody>
          <filtros>
            <criterio resourceID="br.com.sankhya.com.cons.consultaProdutos" orderByExpression="this.CODPROD">
              <CODPROD />
              <CARACTERISTICAS />
            </criterio>
            <filtroPersonalizado>
              <expression>' . $expression . '</expression>
            </filtroPersonalizado>
          </filtros>
        </requestBody>
      </serviceRequest>', true);
  }

  function crud ($xml) {
    return $this->curl_call('mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords', $xml);
  }

  function __destruct () {

  }
}
?>
