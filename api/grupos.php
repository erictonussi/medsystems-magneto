<?php

include 'sankhya.php';

$sankhya = new Sankhya();

$parsed = $sankhya->consulta_grupos();

$arr = array();

foreach ($parsed->responseBody->entities[0]->entity as $entity) {
  // var_dump($entity);

  // $sku = str_replace(' ', '-', $entity->f1);
  // $sku = preg_replace('/-+/', '-', $sku);

  // echo "\nid: $entity->f22";
  // echo "\npai: $entity->f8";
  // echo "\nnome: $entity->f1";

  $parentid = $entity->f8 != -999999999 ? $entity->f8 : 0;

  $arr[] = array(
    'id' => (String)$entity->f22,
    'parentid' => (String)$parentid,
    'name' => (String)$entity->f1
  );

  // $result = $magento->product_create("$entity->f0", $sku, "$entity->f1", "$entity->f2");


  // echo "\n\n\n\n";
}

// $arr = array(
//   array('id'=>100, 'parentid'=>0, 'name'=>'a'),
//   array('id'=>101, 'parentid'=>100, 'name'=>'a'),
//   array('id'=>102, 'parentid'=>101, 'name'=>'a'),
//   array('id'=>103, 'parentid'=>101, 'name'=>'a'),
// );

$new = array();
foreach ($arr as $a){
    $new[$a['parentid']][] = $a;
}
$html = "";
$tree = createTree($new, $new[0]); // changed
// print_r($tree);
echo $html;

function createTree(&$list, $parent){
    global $html;
    $tree = array();
    $html .= '<ul>';
    foreach ($parent as $k=>$l){
        if(isset($list[$l['id']])){
            $html .= '<li>'.$l['name']."";
            $l['children'] = createTree($list, $list[$l['id']]);
            $html .= '</li>';
        }
        $tree[] = $l;
    }
    $html .= '</ul>';
    return $tree;
}



?>
