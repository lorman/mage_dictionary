<?php
class HubCo_Dictionary_Block_Adminhtml_Abbreviation_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        // Instantiate a new form to display our brand for editing.
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl(
                'hubco_dictionary_admin/abbreviation/edit',
                array(
                    '_current' => true,
                    'continue' => 0,
                )
            ),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));
        $form->setUseContainer(true);
        $this->setForm($form);

        // Define a new fieldset. We need only one for our simple entity.
        $fieldset = $form->addFieldset(
            'general',
            array(
                'legend' => $this->__('Abbreviation')
            )
        );

        $dictionarySingleton = Mage::getSingleton(
            'hubco_dictionary/abbreviation'
        );
        $helper = Mage::helper('hubco_dictionary');
        $brandSingleton = Mage::getSingleton(
            'hubco_brand/brand'
        );

        // Add the fields that we want to be editable.
        $this->_addFieldsToFieldset($fieldset, array(
            'abbreviation' => array(
                'label' => $this->__('Abbreviation'),
                'input' => 'text',
                'required' => true,
            ),
            'translation' => array(
                'label' => $this->__('Translation'),
                'input' => 'text',
                'required' => false,
            ),
            'suppliers' => array(
                'name' => 'suppliers[]',
                'label' => $this->__('Suppliers'),
                'input' => 'multiselect',
                'required' => false,
                'values' => $helper->getAvailableSuppliers(true),
            ),
            'brands' => array(
                'name' => 'brands[]',
                'label' => $this->__('Brands'),
                'input' => 'multiselect',
                'required' => false,
                'values' => $brandSingleton->getAvailableBrands(true),
            ),
            'categories' => array(
                'name' => 'categories[]',
                'label' => $this->__('Applicable Categories'),
                'input' => 'multiselect',
                'required' => false,
                'values'=> $helper->getAvailableCategories(true),
            ),
            /**
             * Note: we have not included created_at or updated_at.
             * We will handle those fields ourself in the model
       * before saving.
             */
        ));

        return $this;
    }

    /**
     * This method makes life a little easier for us by pre-populating
     * fields with $_POST data where applicable and wrapping our post data
     * in 'brandData' so that we can easily separate all relevant information
     * in the controller. You could of course omit this method entirely
     * and call the $fieldset->addField() method directly.
     */
    protected function _addFieldsToFieldset(
        Varien_Data_Form_Element_Fieldset $fieldset, $fields)
    {
        $requestData = new Varien_Object($this->getRequest()
            ->getPost('abbreviationData'));

        foreach ($fields as $name => $_data) {
            if ($requestValue = $requestData->getData($name)) {
                $_data['value'] = $requestValue;
            }

            // Wrap all fields with brandData group.
            $_data['name'] = "abbreviationData[$name]";

            // Generally, label and title are always the same.
            $_data['title'] = $_data['label'];

            // If no new value exists, use the existing brand data.
            if (!array_key_exists('value', $_data)) {
                $_data['value'] = $this->_getAbbreviation()->getData($name);
            }

            // Finally, call vanilla functionality to add field.
            $fieldset->addField($name, $_data['input'], $_data);
        }

        return $this;
    }

    /**
     * Retrieve the existing brand for pre-populating the form fields.
     * For a new brand entry, this will return an empty brand object.
     */
    protected function _getAbbreviation()
    {
        if (!$this->hasData('abbreviation')) {
            // This will have been set in the controller.
            $abbreviation = Mage::registry('current_abbreviation');

            // Just in case the controller does not register the brand.
            if (!$abbreviation instanceof
                    HubCo_Dictionary_Model_Abbreviation) {
                $abbreviation = Mage::getModel(
                    'hubco_dictionary/abbreviation'
                );
            }

            $this->setData('abbreviation', $abbreviation);
        }

        return $this->getData('abbreviation');
    }
}