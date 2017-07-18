<?php

include 'magento.php';

$magento = new Magento();

$result = $magento->product_create(13);

var_dump($result);

?>
