<?php

include 'sankhya.php';

$sankhya = new Sankhya();

$parsed = $sankhya->consulta_produtos('(this.CODPROD in (35, 3) )');
print_r($parsed);

?>
