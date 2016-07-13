<?php
class HubCo_Dictionary_Adminhtml_ModelController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Instantiate our grid container block and add to the page content.
     * When accessing this admin index page, we will see a grid of all
     * brands currently available in our Magento instance, along with
     * a button to add a new one if we wish.
     */
    public function indexAction()
    {
        // instantiate the grid container
        $modelBlock = $this->getLayout()
            ->createBlock('hubco_dictionary_adminhtml/model');

        // Add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($modelBlock)
            ->renderLayout();
    }

    /**
     * This action handles both viewing and editing existing brands.
     */
    public function editAction()
    {
        /**
         * Retrieve existing brand data if an ID was specified.
         * If not, we will have an empty brand entity ready to be populated.
         */
        $model = Mage::getModel('hubco_dictionary/model');
        if ($modelId = $this->getRequest()->getParam('id', false)) {
            $model->load($modelId);

            if ($model->getId() < 1) {
                $this->_getSession()->addError(
                    $this->__('This model no longer exists.')
                );
                return $this->_redirect(
                    'hubco_dictionary_admin/model/index'
                );
            }
        }

        // process $_POST data if the form was submitted
        if ($postData = $this->getRequest()->getPost('modelData')) {
            try {
                foreach ($postData as $key => $value)
                {
                  if (is_array($value) && !isset($_FILES['$key']))
                  {
                    $postData[$key] = implode(',',$value);
                  }
                }
                $model->addData($postData);
                $model->save();

                $this->_getSession()->addSuccess(
                    $this->__('The model has been saved.')
                );

                // redirect to remove $_POST data from the request
                return $this->_redirect(
                    'hubco_dictionary_admin/model/edit',
                    array('id' => $model->getId())
                );
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }

            /**
             * If we get to here, then something went wrong. Continue to
             * render the page as before, the difference this time being
             * that the submitted $_POST data is available.
             */
        }

        // Make the current brand object available to blocks.
        Mage::register('current_model', $model);

        // Instantiate the form container.
        $modelEditBlock = $this->getLayout()->createBlock(
            'hubco_dictionary_adminhtml/model_edit'
        );

        // Add the form container as the only item on this page.
        $this->loadLayout()
            ->_addContent($modelEditBlock)
            ->renderLayout();
    }

    public function deleteAction()
    {
        $model = Mage::getModel('hubco_dictionary/model');

        if ($modelId = $this->getRequest()->getParam('id', false)) {
            $model->load($modelId);
        }

         if ($model->getId() < 1) {
            $this->_getSession()->addError(
                $this->__('This model no longer exists.')
            );
            return $this->_redirect(
                'hubco_dictionary_admin/model/index'
            );
        }

        try {
            $model->delete();

            $this->_getSession()->addSuccess(
                $this->__('The model has been deleted.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect(
            'hubco_dictionary_admin/model/index'
        );
    }

    /**
     * Thanks to Ben for pointing out this method was missing. Without
     * this method the ACL rules configured in adminhtml.xml are ignored.
     */
    protected function _isAllowed()
    {
        /**
         * we include this switch to demonstrate that you can add action
         * level restrictions in your ACL rules. The isAllowed() method will
         * use the ACL rule we have configured in our adminhtml.xml file:
         * - acl
         * - - resources
         * - - - admin
         * - - - - children
         * - - - - - smashingmagazine_branddirectory
         * - - - - - - children
         * - - - - - - - brand
         *
         * eg. you could add more rules inside brand for edit and delete.
         */
        $actionName = $this->getRequest()->getActionName();
        switch ($actionName) {
            case 'index':
            case 'edit':
            case 'delete':
                // intentionally no break
            default:
                $adminSession = Mage::getSingleton('admin/session');
                $isAllowed = $adminSession
                    ->isAllowed('hubco_dictionary/model');
                break;
        }

        return $isAllowed;
    }
}