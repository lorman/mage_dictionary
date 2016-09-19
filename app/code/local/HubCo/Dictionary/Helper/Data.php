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

  public function getAvailableProductAttributes($multi = false) {
    $type = Mage::getModel('eav/entity_type')->loadByCode(Mage_Catalog_Model_Product::ENTITY);
    $allAttributes = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter($type);
    if ($multi)
    {
      $attributes[0] = array('value' => 0,
              'label' => 'None');
    }
    else
    {
      $attributes[0] = 'None';
    }
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

  public function getAvailableAttributeValues($code, $multi = false) {
    $attribute = Mage::getSingleton('eav/config')
      ->getAttribute(Mage_Catalog_Model_Product::ENTITY, $code);
    if ($attribute->usesSource()) {
        $options = $attribute->getSource()->getAllOptions(false);
    }
    if ($multi)
    {
      $values[0] = array('value' => 0,
          'label' => 'None');
    }
    else
    {
      $values[0] = 'None';
    }
    if (!empty($options)) {
      foreach ($options as $value){
          if ($multi)
          {
            $values[$value['value']] = array(
                'value' => $value['value'],
                'label' => $value['label']
            );
          }
          else
          {
            $values[$value['value']] = $value['label'];
          }
      }
    }
    return $values;
  }

  public function getAllAttributeValues($multi = false) {
    $type = Mage::getModel('eav/entity_type')->loadByCode(Mage_Catalog_Model_Product::ENTITY);
    $allAttributes = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter($type);
    $values = array();
    foreach ($allAttributes as $attribute){
      if ($attribute->getIsVisibleOnFront()) {
        $attribute = Mage::getSingleton('eav/config')
        ->getAttribute(Mage_Catalog_Model_Product::ENTITY, $attribute->getAttributecode());
        if ($attribute->usesSource()) {
          $options = $attribute->getSource()->getAllOptions(false);
        }
        foreach ($options as $value){
          if ($multi)
          {
            $values[$value['value']] = array(
                'value' => $value['value'],
                'label' => $value['label']
            );
          }
          else
          {
            $values[$value['value']] = $value['label'];
          }
        }
      }
    }
    return $values;
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