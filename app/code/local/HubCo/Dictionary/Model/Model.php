<?php
class HubCo_Dictionary_Model_Model
    extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        /**
         * This tells Magento where the related resource model can be found.
         *
         * For a resource model, Magento will use the standard model alias -
         * in this case 'hubco_brand' - and look in
         * config.xml for a child node <resourceModel/>. This will be the
         * location that Magento will look for a model when
         * Mage::getResourceModel() is called - in our case,
         * HubCo_Brand_Model_Resource.
         */
        $this->_init('hubco_dictionary/model');
    }

    /**
     * This method is used in the grid and form for populating the dropdown.
     */

    protected function _beforeSave()
    {
        parent::_beforeSave();

        /**
         * Perform some actions just before a brand is saved.
         */
        $this->_updateTimestamps();

        return $this;
    }

    protected function _updateTimestamps()
    {
        $timestamp = now();

        /**
         * Set the last updated timestamp.
         */
        $this->setUpdatedAt($timestamp);

        /**
         * If we have a brand new object, set the created timestamp.
         */
        if ($this->isObjectNew()) {
            $this->setCreatedAt($timestamp);
        }

        return $this;
    }

    public function Clean($make, $model, $supplier) {
      $collection = Mage::getModel('hubco_dictionary/model')->getCollection();
      $collection->addFieldToFilter('make',array('eq'=>$make));
      $collection->addFieldToFilter('supplier_model',array('eq'=>$model));
      $collection->addFieldToFilter(array('suppliers','suppliers'),array(array('regexp'=>'(,|^)'.$supplier.'(,|$)'), array('eq'=>'')));
      $collection->setOrder('supplier_model', 'desc');
      $collection->setOrder('suppliers', 'desc');

      $collection->getSelect()
      ->order('supplier_model desc')
      ->order('suppliers desc');
      //->__toString(); exit;
      foreach ($collection as $item) {
        return $item;
      }
      return null;
    }
}