<?php

include 'sankhya.php';

$sankhya = new Sankhya();

$parsed = $sankhya->consulta_grupos();

$arr = array();

foreach ($parsed->responseBody->entities[0]->entity as $entity) {

  $parentid = $entity->f8 != -999999999 ? $entity->f8 : 0;

  $arr[] = array(
    'id' => (String)$entity->f22,
    'parentid' => (String)$parentid,
    'name' => (String)$entity->f1
  );
}

$new = array();
foreach ($arr as $a){
    $new[$a['parentid']][] = $a;
}
$tree = createTree($new, $new[0]);
echo json_encode($tree);

function createTree(&$list, $parent) {
    $tree = array();
    foreach ($parent as $k=>$l){
        if(isset($list[$l['id']])){
            $l['children'] = createTree($list, $list[$l['id']]);
        }
        $tree[] = $l;
    }
    return $tree;
}

die();

?>
[{"id":"3","parentid":"2","name":"teste","children":[{"id":"5","parentid":"3","name":"sub-teste","children":[]}]},{"id":"3040800","parentid":"2","name":"teste 2","children":[]}]
