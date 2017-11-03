<?php

require '../app/Mage.php';
include 'sankhya.php';

Mage::init();

// $customer = Mage::getModel('customer/customer')->load(41);
// $customer->setsankhya_id(666)->save();
// // $customer->setsankhya(666)->save();

// // var_dump($customer); die();

$product = Mage::getModel('customer/customer')
    ->getCollection()
    ->load();

foreach ($product as $products) {
  // var_dump($product); //die();
  // if ( $products->sankhya_id) {
    echo "\n\nid:" . (String)$products->entity_id;
  // }
    // die();
}

    // die();

$sankhya = new Sankhya();

$parsed = $sankhya->get_users();
// var_dump($parsed); die();
// var_dump($parsed->responseBody->result->row); die();

$websiteId = Mage::app()->getWebsite()->getId();
$store = Mage::app()->getStore();

foreach ($parsed->responseBody->result->row as $entity) {
  // var_dump($entity); //die();

  echo "\n\n" . (int)$entity->CODPARC;
  echo "\n" . (String)$entity->NOMEPARC;
  echo "\n" . (String)$entity->EMAIL;


  $customer = Mage::getModel("customer/customer");
  $customer->setWebsiteId($websiteId)
           ->setStore($store)
           ->setentity_id((String)$entity->CODPARC)
           ->setcreated_at("2014-01-01 09:00:00")
           ->setFirstname((String)$entity->NOMEPARC)
           ->setLastname((String)$entity->NOMEPARC)
           // ->setLastname('Doe')
           ->setEmail((String)$entity->EMAIL)
           ->setPassword('somepassword');

  try{
      $customer->save();
  }
  catch (Exception $e) {
      Zend_Debug::dump($e->getMessage());
  }

}
