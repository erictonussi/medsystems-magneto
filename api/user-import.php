<?php

require '../app/Mage.php';
include 'sankhya.php';

Mage::register('isSecureArea', true);

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
  // echo "\n\nid:" . (String)$customer->entity_id;
  $ids[] = (String)$customer->entity_id;
  // $customer->setIsDeleteable(true);
  // $customer->delete();
}

// var_dump($ids); // die();

$sankhya = new Sankhya();

$parsed = $sankhya->get_users($ids);
// var_dump($parsed); die();
// var_dump($parsed->responseBody->result->row); die();

$websiteId = Mage::app()->getWebsite()->getId();
$store = Mage::app()->getStore();

echo "<pre>";

$total = 0;
foreach ($parsed->responseBody->entities[0]->entity as $entity) {
  // var_dump($entity); die();
  $total++;
  echo "\n\n#$total";

  echo "\n\n" . (int)$entity->f0;
  echo "\n" . (String)$entity->f1;
  echo "\n" . (String)$entity->f2;

  $cpf_cnpj = (String)$entity->f3;
  $cpf_cnpj = mask($cpf_cnpj, strlen($cpf_cnpj) == 11 ? '###.###.###-##' : '##.###.###/####-##');

  echo "\ncpf/cnpj: $cpf_cnpj";
  echo "\nsenha: ".substr((String)$entity->f3, 0, 6);

  echo "\nPostcode: " . (String)$entity->f4;
  echo "\nEstado: " . $sankhya->estados[(String)$entity->f12]['estado'];

  // die();

  echo "\n";

  $customer = Mage::getModel("customer/customer");
  $customer->setWebsiteId($websiteId)
           ->setStore($store)
           ->setentity_id((String)$entity->f0)
           // ->setcreated_at("2014-01-01 09:00:00")
           ->setcreated_at(date("Y-m-d H:i:s"))
           ->setFirstname((String)$entity->f1)
           // ->setLastname((String)$entity->f1)
           ->setLastname(".")
           // ->setLastname('Doe')
           ->setEmail((String)$entity->f2)
           ->setTaxvat($cpf_cnpj)
           ->setPassword(substr((String)$entity->f3, 0, 6));

  try {
      $customer->save();

      $address = Mage::getModel("customer/address");
      $address->setCustomerId($customer->getId())
              ->setFirstname((String)$entity->f1)
              // ->setMiddleName($customer->getMiddlename())
              ->setLastname('.')
              ->setCountryId('BR')
              // ->setRegionId( $sankhya->estados[(String)$entity->f12]['estado'] ) //state/province, only needed if the country is USA
              ->setPostcode((String)$entity->f4)
              ->setCity((String)$entity->f11)
              ->setTelephone((String)$entity->f7)
              // ->setFax((String)$entity->f1)
              // ->setCompany((String)$entity->f1)
              ->setStreet(array(
                '0' => (String)$entity->f8 . ' ' . (String)$entity->f9 . ', ' . (String)$entity->f5,
                '1' => (String)$entity->f6
              ))
              ->setIsDefaultBilling('1')
              ->setIsDefaultShipping('1')
              ->setSaveInAddressBook('1');
      $address->save();
  }
  catch (Exception $e) {
    echo 'erro: ' . $e->getMessage();
      // Zend_Debug::dump($e->getMessage());
  }

  echo "\n";

}
