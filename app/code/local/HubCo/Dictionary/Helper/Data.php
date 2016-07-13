<?php
class HubCo_Dictionary_Helper_Data
    extends Mage_Core_Helper_Abstract
{

  public function getAvailableCategories($multi = false)
  {
    $categories = array();
    $allCategoriesCollection = Mage::getModel('catalog/category')
    ->getCollection()
    ->addAttributeToSelect('name')
    ->addFieldToFilter('level', array('gt'=>'0'));
    $allCategoriesArray = $allCategoriesCollection->load()->toArray();
    $categoriesArray = $allCategoriesCollection
    ->addAttributeToSelect('level')
    ->addAttributeToSort('path', 'asc')
    ->addFieldToFilter('is_active', array('eq'=>'1'))
    ->addFieldToFilter('level', array('gt'=>'1'))
    ->load()
    ->toArray();
    foreach ($categoriesArray as $categoryId => $category)
    {
      if (!isset($category['name'])) {
        continue;
      }
      $categoryIds = explode('/', $category['path']);
      $nameParts = array();
      foreach($categoryIds as $catId) {
        if($catId == 1) {
          continue;
        }
        $nameParts[] = $allCategoriesArray[$catId]['name'];
      }
      if ($multi)
      {
        $categories[$categoryId] = array(
            'value' => $categoryId,
            'label' => implode(' / ', $nameParts)
        );
      }
      else
      {
        $categories[$categoryId] = implode(' / ', $nameParts);
      }
    }

    return $categories;
  }

  public function getAvailableSuppliers($multi = false)
  {
    $suppliers = array();
    $allSuppliersCollection = Mage::getModel('suppliers/supplier')
    ->getCollection()
    ->addFieldToSelect('name');
    $allSuppliers = $allSuppliersCollection->load()->toArray();
    foreach ($allSuppliers['items'] as $supplierId => $supplier)
    {
      if (!isset($supplier['name'])) {
        continue;
      }
      if ($multi)
      {
        $suppliers[$supplierId] = array(
            'value' => $supplierId,
            'label' => $supplier['name']
        );
      }
      else
      {
        $suppliers[$supplierId] = $supplier['name'];
      }
    }

    return $suppliers;
  }

  public function getAvailableProductAttributes($multi = false) {

    $type = Mage::getModel('eav/entity_type')->loadByCode(Mage_Catalog_Model_Product::ENTITY);
    $allAttributes = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter($type);

    $attributes = array();
    foreach ($allAttributes as $attribute){
      if ($attribute->getIsVisibleOnFront()) {
        if ($multi)
        {
          $attributes[$attribute->getAttributecode()] = array(
              'value' => $attribute->getAttributecode(),
              'label' => $attribute->getFrontendLabel()
          );
        }
        else
        {
          $attributes[$attribute->getAttributecode()] = $attribute->getFrontendLabel();
        }
      }
    }
    return $attributes;
  }

  public function getAvailableMakes($multi = false) {
    $allMakes = Mage::getModel('partfinder/db')->getColumnValues("make");
    $makes = array();
    foreach ($allMakes as $make){
      if ($multi)
      {
        $makes[$make] = array(
            'value' => $make,
            'label' => $make
        );
      }
      else
      {
        $makes[$make] = $make;
      }
    }
    return $makes;
  }

  public function getAvailableModels($multi = false) {
    $allModels = Mage::getModel('partfinder/db')->getColumnValues("model");
    $models = array();
    foreach ($allModels as $model){
      if ($multi)
      {
        $models[$model] = array(
            'value' => $model,
            'label' => $model
        );
      }
      else
      {
        $models[$model] = $model;
      }
    }
    return $models;
  }

}