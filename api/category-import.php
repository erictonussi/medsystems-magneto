<?php

require '../app/Mage.php';

$config = json_decode(file_get_contents("config.json"));

// $grupos = [];
// foreach ($config->grupos as $key => $value) {
//   $grupos[] = $key;
// }

// echo join($grupos, ",");
// echo "\n\n\n\n";

// $grupo = $config->grupos

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

// echo join($sankhya_ids, ",");

include 'sankhya.php';
include 'magento.php';

$sankhya = new Sankhya();
$magento = new Magento();

// $grupos = [];
$produtos = [];

foreach ($config->grupos as $key => $value) {

  $parsed = $sankhya->crud(
    '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
      <requestBody>
        <dataSet rootEntity="Produto" parentEntity="GrupoProduto" includePresentationFields="S" parallelLoader="true" datasetid="1502409718430_3">
          <entity path="">
            <fieldset list="CODPROD, DESCRPROD, CARACTERISTICAS"/>
          </entity>
          <entity path="GrupoProduto">
            <field name="DESCRGRUPOPROD"/>
          </entity>
          <foreingKey>
            <CODGRUPOPROD>
              <![CDATA['.$key.']]>
            </CODGRUPOPROD>
          </foreingKey>
        </dataSet>
        <clientEventList/>
      </requestBody>
    </serviceRequest>'
  );
  // var_dump($parsed); die();

  // $produtos = [];

  foreach ($parsed->responseBody->entities[0]->entity as $entity) {
    // var_dump($entity); die();

    $produto = array(
      name => (String)$entity->f1,
      group => (String)$entity->f3
    );

    $sankhya_id = $entity->f0;

    $sku = trim($entity->f1);
    $sku = str_replace(array(' ', '(', ')'), '-', $sku);
    $sku = str_replace('/[^a-z0-9]/i', '-', $sku);
    $sku = preg_replace('/-+/', '-', $sku);
    $sku = trim($sku, '-');

    $sku = "$sankhya_id-$sku";

    // echo "\nsankhya_id: $sankhya_id";
    // echo "\nsku: $sku";
    // echo "\nurl_key: $sku";
    // echo "\nname: $entity->f1";
    // echo "\ndescription: $entity->f2";
    // echo "\nGrupo: $entity->f3";

    // echo "<h4>$entity->f1 ($sankhya_id)</h4>";

    if ( array_search($sankhya_id, $sankhya_ids) ) {
      // echo "<p>Produto já cadastrado</p>";
      $produto['status'] = 0;
    } else {
      $produto['status'] = 1;

      $product = Mage::getModel('catalog/product');
      //    if(!$product->getIdBySku('testsku61')):

      try {
        $product
      //    ->setStoreId(1) //you can set data in store scope
          ->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
          ->setAttributeSetId(4) //ID of a attribute set named 'default'
          ->setTypeId('simple') //product type
          ->setCreatedAt(strtotime('now'))
          ->setSku($sku) //SKU
          ->seturl_key($sku) //SKU
          ->setName($entity->f1) //product name
          ->setPrice(0) //price in form 11.22
          ->setStatus(1)
          ->setDescription($entity->f2)
          ->setCategoryIds(array($value)) //assign product to categories
          ->setsankhya_id($sankhya_id)
          ->setStockData(array(
            'use_config_manage_stock' => 0, //'Use config settings' checkbox
            'manage_stock'=> 1, //manage stock
            // 'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
            // 'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
            'is_in_stock' => 0, //Stock Availability
            'qty' => 0 //qty
          ))
        ;
        $product->save();
        // echo "<p>Produto cadastrado com sucesso</p>";
      //endif;
      } catch(Exception $e) {
        Mage::log($e->getMessage());
        var_dump($e);
      }
    }

    $produtos[] = $produto;

    // echo "\n\n\n\n";
  }

  // $grupos[] = $produtos;
}

echo json_encode($produtos);
