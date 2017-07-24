<?php

class Magento {

  var $client;
  var $session;
  var $attributeSet;

  function __construct () {
    $this->client = new SoapClient('http://medsystems.kavecode.com.br/api/soap/?wsdl');

    $this->session = $this->client->login('eric', '123123');
  }

  function product_create($sankhya_id, $sku, $name, $description) {
    if ( !$this->attributeSet ) {
      $attributeSets = $this->client->call($this->session, 'product_attribute_set.list');
      $this->attributeSet = current($attributeSets);
    }

    $result = $this->client->call($this->session, 'catalog_product.create', array(
      'simple',
      $this->attributeSet['set_id'],
      $sku,
      array(
        'categories'        => array(2),
        'websites'          => array(1),
        'name'              => trim($name),
        'description'       => trim($description),
        // 'short_description' => 'Product short description',
        // 'weight'            => '10',
        'status'            => '1',
        'url_key'           => $sku,
        // 'url_path'          => 'product-url-path',
        // 'visibility'        => '4',
        // 'price'             => '100',
        // 'tax_class_id'      => 1,
        // 'meta_title'        => 'Product meta title',
        // 'meta_keyword'      => 'Product meta keyword',
        // 'meta_description'  => 'Product meta description',
        'sankhya_id'        => $sankhya_id
      )
    ));

    return $result;
  }

  function sankhya_ids (){
     $products = $this->client->call($this->session, 'catalog_product.list');

     $ids = array();

     foreach ($products as $product) {
       $result = $this->client->call($this->session, 'catalog_product.info', $product['product_id']);

       var_dump($result);
       die();

       $ids[] = $result['sankhya_id'];
     }

     var_dump($ids);
  }

  function __destruct () {
    $this->client->endSession($this->session);
  }
}
?>
