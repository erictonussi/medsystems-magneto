<?php
include 'sankhya.php';
$sankhya = new Sankhya();

require '../app/Mage.php';
Mage::init();

$products = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('name')
    ->addAttributeToSelect('sankhya_id')
    ->addAttributeToSelect('price')
    ->addAttributeToFilter(
    'status',
      array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
    )
    ->load();
// var_dump($products); die();

$produtos = [];
foreach ($products as $product) {
  // var_dump($product); die();

  if ( $product->sankhya_id) {

    $parsed = $sankhya->consulta_precos($product->sankhya_id);
    // var_dump($parsed->responseBody->produto->precos[0]->preco->VALOR);
    $valor = (String)$parsed->responseBody->produto->precos[0]->preco->VALOR; // die();

    $produtos[] = array(
      "name" => $product->name,
      "current" => $product->price,
      "new" => $valor
    );

    // echo $product->name."\n";
    // echo ($valor && $product->price != $valor ? 'true' : 'false' )."\n";
    // echo $product->price."\n";
    // echo $valor."\n";
    // json_encode($produtos);

    if ( $valor && $product->price != $valor ) {
      $product->setPrice($valor)->save();
    }

  }
}

echo json_encode($produtos);
