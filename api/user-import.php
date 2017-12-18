<?php

require '../app/Mage.php';
include 'sankhya.php';

function mask($val, $mask)
{
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++)
 {
 if($mask[$i] == '#')
 {
 if(isset($val[$k]))
 $maskared .= $val[$k++];
 }
 else
 {
 if(isset($mask[$i]))
 $maskared .= $mask[$i];
 }
 }
 return $maskared;
}

Mage::init();

// $customer = Mage::getModel('customer/customer')->load(41);
// $customer->setsankhya_id(666)->save();
// // $customer->setsankhya(666)->save();

// // var_dump($customer); die();

$customers = Mage::getModel('customer/customer')
    ->getCollection()
    ->load();

$ids = array(0);

foreach ($customers as $customer) {
  echo "\n\nid:" . (String)$customer->entity_id;
  $ids[] = (String)$customer->entity_id;
}

// var_dump($ids); // die();

$sankhya = new Sankhya();

$parsed = $sankhya->get_users($ids);
// var_dump($parsed); die();
// var_dump($parsed->responseBody->result->row); die();

$websiteId = Mage::app()->getWebsite()->getId();
$store = Mage::app()->getStore();

foreach ($parsed->responseBody->result->row as $entity) {
  // var_dump($entity); die();

  echo "\n\n" . (int)$entity->CODPARC;
  echo "\n" . (String)$entity->NOMEPARC;
  echo "\n" . (String)$entity->EMAIL;

  $cpf_cnpj = (String)$entity->CGC_CPF;
  $cpf_cnpj = mask($cpf_cnpj, strlen($cpf_cnpj) == 11 ? '###.###.###-##' : '##.###.###/####-##');

  echo "\ncpf/cnpj: $cpf_cnpj";

  $customer = Mage::getModel("customer/customer");
  $customer->setWebsiteId($websiteId)
           ->setStore($store)
           ->setentity_id((String)$entity->CODPARC)
           // ->setcreated_at("2014-01-01 09:00:00")
           ->setcreated_at(date("Y-m-d H:i:s"))
           ->setFirstname((String)$entity->NOMEPARC)
           // ->setLastname((String)$entity->NOMEPARC)
           // ->setLastname('Doe')
           ->setEmail((String)$entity->EMAIL)
           ->setTaxvat($cpf_cnpj)
           ->setPassword('somepassword');

  try {
      $customer->save();

      $parsed_endereco = $sankhya->consulta_endereco((String)$entity->CEP);
      // var_dump($parsed_endereco); die();

      $endereco = $parsed_endereco->responseBody->entities[0]->entity[0];
      // var_dump($endereco); die();

      $address = Mage::getModel("customer/address");
      $address->setCustomerId($customer->getId())
              ->setFirstname((String)$entity->NOMEPARC)
              // ->setMiddleName($customer->getMiddlename())
              ->setLastname($customer->getFirstname())
              ->setCountryId('BR')
              //->setRegionId('1') //state/province, only needed if the country is USA
              ->setPostcode((String)$entity->CEP)
              ->setCity((String)$endereco->f9)
              ->setTelephone((String)$entity->TELEFONE)
              // ->setFax((String)$entity->NOMEPARC)
              // ->setCompany((String)$entity->NOMEPARC)
              ->setStreet(array(
                (String)$endereco->f7),
                (String)$entity->NUMEND
              )
              ->setIsDefaultBilling('1')
              ->setIsDefaultShipping('1')
              ->setSaveInAddressBook('1');
      $address->save();
  }
  catch (Exception $e) {
    var_dump($e->getMessage());
      // Zend_Debug::dump($e->getMessage());
  }

}
