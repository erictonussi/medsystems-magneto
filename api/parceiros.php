<?php

include 'sankhya.php';
// include 'magento.php';

// $magento = new Magento();
$sankhya = new Sankhya();

$parsed = $sankhya->crud(
  '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
    <requestBody>
      <dataSet rootEntity="Parceiro" includePresentationFields="S" parallelLoader="false" disableRowsLimit="false" orderByExpression="this.CODPARC">
        <entity path="">
          <fieldset list="*" />
          <!--fieldset list="CODPARC,NOMEPARC" /-->
        </entity>
        <entity path="Cidade">
          <fieldset list="NOMECID" />
        </entity>
        <entity path="Cidade.UnidadeFederativa">
          <fieldset list="UF,DESCRICAO" />
        </entity>
        <criteria>
          <expression>
            this.CODPARC &lt; 20 AND (this.CLIENTE = \'S\' AND this.DTALTER &gt; ?)
          </expression>
          <parameter type="D">01/10/2015</parameter>
        </criteria>
      </dataSet>
    </requestBody>
  </serviceRequest>'
);

// var_dump($parsed);



foreach ($parsed->responseBody->entities[0]->entity as $entity) {
  // var_dump($entity);

  // $sku = str_replace(' ', '-', $entity->f1);
  // $sku = preg_replace('/-+/', '-', $sku);

  $i = 0;
  foreach ($parsed->responseBody->entities[0]->metadata->fields->field as $key => $metadata) {
    echo ((string)$metadata[0]->attributes()) . ': ' . $entity->{'f'.$i};
    echo "\n";
    $i++;
  }

  // echo "\nid: $entity->f2";
  // echo "\npai: $entity->f8";
  // echo "\nname: $entity->f1";
  // echo "\nsku: $sku";
  // echo "\nurl_key: $sku";
  // echo "\ndescription: $entity->f2";

  // // echo trim($entity->f0), "\n";
  // // echo trim($entity->f1), "\n";
  // // echo trim($entity->f2), "\n";

  // $result = $magento->product_create("$entity->f0", $sku, "$entity->f1", "$entity->f2");


  echo "\n\n\n\n";
}

?>
