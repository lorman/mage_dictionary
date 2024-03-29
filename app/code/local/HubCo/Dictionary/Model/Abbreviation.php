<?php
class HubCo_Dictionary_Model_Abbreviation
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
        $this->_init('hubco_dictionary/abbreviation');
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

    public function translate($word, $brand = '', $supplier = '') {
      //TODO Find and return a translated word
      $collection = Mage::getModel('hubco_dictionary/abbreviation')->getCollection();
      $collection->addFieldToFilter('abbreviation',array('eq'=>$word));
      $collection->addFieldToFilter(array('brands','brands'),array(array('regexp'=>'(,|^)'.$brand.'(,|$)'), array('eq'=>'')));
      $collection->addFieldToFilter(array('suppliers','suppliers'),array(array('regexp'=>'(,|^)'.$supplier.'(,|$)'), array('eq'=>'')));
      $collection->setOrder('suppliers', 'desc');
      $collection->setOrder('brands', 'desc');

      $collection->getSelect()
      ->order('suppliers desc')
      ->order('brands desc');

      foreach ($collection as $item) {
        return $item['translation'];
      }
      return null;
    }
}