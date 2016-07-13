<?php
class HubCo_Dictionary_Block_Adminhtml_Model_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        // Instantiate a new form to display our brand for editing.
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl(
                'hubco_dictionary_admin/model/edit',
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
                'legend' => $this->__('Model')
            )
        );

        $dictionarySingleton = Mage::getSingleton(
            'hubco_dictionary/model'
        );
        $helper = Mage::helper('hubco_dictionary');
        $brandSingleton = Mage::getSingleton(
            'hubco_brand/brand'
        );

        // Add the fields that we want to be editable.
        $this->_addFieldsToFieldset($fieldset, array(
            'make' => array(
                'label' => $this->__('Make'),
                'input' => 'select',
                'required' => true,
                'values' => $helper->getAvailableMakes(),
            ),
            'supplier_model' => array(
                'label' => $this->__('Model Name From Source'),
                'input' => 'text',
                'required' => false,
            ),
            'translated_model' => array(
                'label' => $this->__('Model'),
                'input' => 'select',
                'required' => true,
                'values' => $helper->getAvailableModels(),
            ),
            'suppliers' => array(
                'name' => 'suppliers[]',
                'label' => $this->__('Suppliers'),
                'input' => 'multiselect',
                'required' => false,
                'values' => $helper->getAvailableSuppliers(true),
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
            ->getPost('modelData'));

        foreach ($fields as $name => $_data) {
            if ($requestValue = $requestData->getData($name)) {
                $_data['value'] = $requestValue;
            }

            // Wrap all fields with brandData group.
            $_data['name'] = "modelData[$name]";

            // Generally, label and title are always the same.
            $_data['title'] = $_data['label'];

            // If no new value exists, use the existing brand data.
            if (!array_key_exists('value', $_data)) {
                $_data['value'] = $this->_getModel()->getData($name);
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
    protected function _getModel()
    {
        if (!$this->hasData('model')) {
            // This will have been set in the controller.
            $model = Mage::registry('current_model');

            // Just in case the controller does not register the brand.
            if (!$model instanceof
                    HubCo_Dictionary_Model_Model) {
                $model = Mage::getModel(
                    'hubco_dictionary/model'
                );
            }

            $this->setData('model', $model);
        }

        return $this->getData('model');
    }
}