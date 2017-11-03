<?php

// require '../app/bootstrap.php';
require '../app/Mage.php';

Mage::init();

function getTreeCategories($parentId){
    $allCats = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                // ->addAttributeToFilter('is_active','1')
                // ->addAttributeToFilter('include_in_menu','1')
                ->addAttributeToFilter('parent_id',array('eq' => $parentId));
    $tree = array();
    foreach ($allCats as $category)
    {
        $tree[] = array(
            'id' => $category->getId(),
            'parentid' => $parentId,
            'name' => $category->getName(),
            'children' => getTreeCategories($category->getId(), true)
        );
    }
    return $tree;
}

$rootCatId = Mage::app()->getStore()->getRootCategoryId();
$catlistHtml = getTreeCategories($rootCatId);

echo json_encode($catlistHtml)

?>
