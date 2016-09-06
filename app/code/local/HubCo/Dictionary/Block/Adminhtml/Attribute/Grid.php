<?php
class HubCo_Dictionary_Block_Adminhtml_Attribute_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        /**
         * Tell Magento which collection to use to display in the grid.
         */
        $collection = Mage::getResourceModel(
            'hubco_dictionary/attribute_collection'
        );
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    public function getRowUrl($row)
    {
        /**
         * When a grid row is clicked, this is where the user should
         * be redirected to - in our example, the method editAction of
         * BrandController.php in BrandDirectory module.
         */
        return $this->getUrl(
            'hubco_dictionary_admin/attribute/edit',
            array(
                'id' => $row->getId()
            )
        );
    }

    protected function _prepareColumns()
    {
        /**
         * Here, we'll define which columns to display in the grid.
         */
        $helper = Mage::helper('hubco_dictionary');
        $this->addColumn('entity_id', array(
            'header' => $this->_getHelper()->__('ID'),
            'type' => 'number',
            'index' => 'entity_id',
        ));

        $this->addColumn('attribute_name', array(
            'header' => $this->_getHelper()->__('Source Attribute'),
            'type' => 'text',
            'index' => 'attribute_name',
        ));

        $this->addColumn('attribute_code', array(
            'header' => $this->_getHelper()->__('Attribute'),
            'type' => 'options',
            'index' => 'attribute_code',
            'options' => $helper->getAvailableProductAttributes(),
        ));

        $this->addColumn('value', array(
            'header' => $this->_getHelper()->__('Value'),
            'type' => 'text',
            'index' => 'value',
        ));

        $this->addColumn('translation', array(
            'header' => $this->_getHelper()->__('Translation'),
            'type' => 'options',
            'index' => 'translation',
            'options'=> $helper->getAllAttributeValues(),
        ));

        $this->addColumn('ignore', array(
            'header' => $this->_getHelper()->__('Ignore'),
            'type' => 'checkbox',
            'index' => 'ignore',
        ));

        $this->addColumn('description', array(
            'header' => $this->_getHelper()->__('To Description'),
            'type' => 'checkbox',
            'index' => 'description',
        ));

        $this->addColumn('created_at', array(
            'header' => $this->_getHelper()->__('Created'),
            'type' => 'datetime',
            'index' => 'created_at',
        ));

        $this->addColumn('updated_at', array(
            'header' => $this->_getHelper()->__('Updated'),
            'type' => 'datetime',
            'index' => 'updated_at',
        ));

        /**
         * Finally, we'll add an action column with an edit link.
         */
        $this->addColumn('action', array(
            'header' => $this->_getHelper()->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'actions' => array(
                array(
                    'caption' => $this->_getHelper()->__('Edit'),
                    'url' => array(
                        'base' => 'hubco_dictionary_admin'
                                  . '/attribute/edit',
                    ),
                    'field' => 'id'
                ),
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'entity_id',
        ));

        return parent::_prepareColumns();
    }

    protected function _getHelper()
    {
        return Mage::helper('hubco_dictionary');
    }
}