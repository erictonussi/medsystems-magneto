<?php

require '../app/Mage.php';
Mage::init();

$product = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('sankhya_id')
    ->load();
$sankhya_ids = [];
foreach ($product as $products) {
  if ( $products->sankhya_id) {
    $sankhya_ids[] = (String)$products->sankhya_id;
  }
    // die();
}

include 'sankhya.php';

$sankhya = new Sankhya();

$parsed = $sankhya->consulta_produtos('(this.CODPROD in (336, 337, 2735, 2736, 2737, 2738, 2739, 2740, 2948, 2945, 2950, 2960, 2961, 2962, 3002, 3009) )');
// print_r($parsed); die();

$produtos = [];
foreach ($parsed->responseBody->produtos[0]->produto as $entity) {
  // echo "$entity->CODPROD\n";

  $produto = array(
    name => (String)$entity->f1,
    group => (String)$entity->f3
  );

  $sankhya_id = $entity->CODPROD;

  $sku = trim($entity->DESCRPROD);
  $sku = str_replace(' ', '-', $sku);
  $sku = preg_replace('/[^A-Za-z0-9\-]/', '-', $sku);
  // $sku = str_replace('/[^a-z0-9]/i', '-', $sku);
  $sku = preg_replace('/-+/', '-', $sku);
  $sku = trim($sku, '-');

  $sku = "$sankhya_id-$sku";

  // echo "$sku\n";
  // continue;

  if ( array_search($sankhya_id, $sankhya_ids) ) {
    $produto['status'] = 0;
  } else {
    $produto['status'] = 1;

    $product = Mage::getModel('catalog/product');

    try {
      $product
    //    ->setStoreId(1) //you can set data in store scope
        ->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
        ->setAttributeSetId(4) //ID of a attribute set named 'default'
        ->setTypeId('simple') //product type
        ->setCreatedAt(strtotime('now'))
        ->setSku($sku) //SKU
        ->seturl_key($sku) //SKU
        ->setName($entity->DESCRPROD) //product name
        ->setPrice($entity->Preço_1) //price in form 11.22
        ->setStatus(1)
        ->setDescription($entity->DESCRPROD)
        // ->setCategoryIds(array($value)) //assign product to categories
        ->setsankhya_id($sankhya_id)
        ->setStockData(array(
          'use_config_manage_stock' => 0, //'Use config settings' checkbox
          'manage_stock'=> 1, //manage stock
          // 'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
          // 'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
          'is_in_stock' => 0, //Stock Availability
          'qty' => $entity->Estoque_1 //qty
        ))
      ;
      $product->save();
    } catch(Exception $e) {
      Mage::log($e->getMessage());
      var_dump($e);
    }
  }

  $produtos[] = $produto;
}
echo json_encode($produtos);

?>
