<?php

// require '../app/bootstrap.php';
require '../app/Mage.php';

Mage::init();

$product = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('sankhya_id')
    ->load();
    // ->addAttributeToFilter('taxvat', 123);
//
// foreach ($product as $products) {
//     echo '<pre>';
//     print_r($products->sankhya_id);
//     echo '</pre>';
//     // die();
// }

$rootCatId = Mage::app()->getStore()->getRootCategoryId();

function getTreeCategories($parentId, $isChild){
    $allCats = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                // ->addAttributeToFilter('is_active','1')
                // ->addAttributeToFilter('include_in_menu','1')
                ->addAttributeToFilter('parent_id',array('eq' => $parentId));

    $class = ($isChild) ? "sub-cat-list" : "cat-list";
    $html .= '<ul class="'.$class.'">';
    //$children = Mage::getModel('catalog/category')->getCategories(7);
    foreach ($allCats as $category)
    {
        $html .= '<li>'.$category->getId().": ".$category->getName()."";
        // $subcats = $category->getChildren();
        // if($subcats != ''){
            $html .= getTreeCategories($category->getId(), true);
        // }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}
$catlistHtml = getTreeCategories($rootCatId, false);

echo $catlistHtml;

?>
