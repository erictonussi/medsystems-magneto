<?php
include 'sankhya.php';
$sankhya = new Sankhya();

$ids = [0];

$parsed = $sankhya->curl_call('mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords',
      "<serviceRequest serviceName=\"CRUDServiceProvider.loadRecords\">
        <requestBody>
          <dataSet crudListener=\"br.com.sankhya.modelcore.crudlisteners.ParceiroCrudListener\" datasetid=\"1514463233118_1\" includePresentationFields=\"S\" parallelLoader=\"true\" rootEntity=\"Parceiro\">
            <entity path=\"\">
              <fieldset list=\"CODPARC,NOMEPARC,EMAIL,CGC_CPF,CEP,NUMEND,COMPLEMENTO,TELEFONE\"/>
            </entity>
            <entity path=\"Endereco\">
              <field name=\"TIPO\"/>
              <field name=\"NOMEEND\"/>
            </entity>
            <entity path=\"Bairro\">
              <field name=\"NOMEBAI\"/>
            </entity>
            <entity path=\"Cidade\">
              <field name=\"NOMECID\"/>
              <field name=\"UF\"/>
            </entity>
            <criteria>
              <expression>(this.CODPARC &lt; 30000) AND this.CLIENTE = 'S' AND this.EMAIL != '' and this.CODPARC not in (". join($ids, ',') .")</expression>
            </criteria>
          </dataSet>
        </requestBody>
      </serviceRequest>", true);
// var_dump($parsed); die();
$total = 0;
foreach ($parsed->responseBody->entities[0]->entity as $entity) {
  var_dump($entity);
  $total++;
}

echo "\n\n$total";


// <fieldset list='CODPARC,CLIENTE,NOMEPARC,EMAIL,CGC_CPF,CEP,NUMEND,COMPLEMENTO'/>
// <expression>(this.CODPARC <= 8) AND this.CLIENTE = 'S' AND this.EMAIL != '' and this.CODPARC not in (". join($ids, ',') .")</expression>
