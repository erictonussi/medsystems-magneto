<?php
$client = new SoapClient('http://magento.local/api/soap/?wsdl');

// var magento = new MagentoAPI({
//   host: 'magento.local',
//   port: 80,
//   path: '/api/xmlrpc/',
//   login: 'eric',
//   pass: '123123'
// });

// If somestuff requires api authentification,
// then get a session token
$session = $client->login('eric', '123123');

$filters = array(
  'filters' => array(
      'sankhya_id' => 1,
      // 'type_id' => 'complex',
  )
);

$result = $client->call($session, 'catalog_product.list', $filters);
var_dump($result);

// If you don't need the session anymore
$client->endSession($session);

// $proxy = new SoapClient('http://magento.local/api/v2_soap/?wsdl');

// $sessionId = $proxy->login((object)array('username' => 'eric', 'apiKey' => '123123'));

// $result = $proxy->catalogProductList((object)array('sessionId' => $sessionId->result, 'filters' => null));
// var_dump($result->result);

?>
