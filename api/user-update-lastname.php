<?php

require '../app/Mage.php';

Mage::init();

// $customer = Mage::getModel('customer/customer')->load(41);
// $customer->setsankhya_id(666)->save();
// // $customer->setsankhya(666)->save();

// // var_dump($customer); die();

$customers = Mage::getModel('customer/customer')
    ->getCollection()
    ->load();

foreach ($customers as $customer) {
  echo "\n" . $customer->getFirstname();
  echo "\n" . $customer->getName();
  $customer
    // ->setLastname($customer->getFirstname())
    ->setLastname('.')
    ->save();
}
