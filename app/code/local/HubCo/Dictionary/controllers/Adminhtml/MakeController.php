<?php
class HubCo_Dictionary_Adminhtml_MakeController
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
        $makeBlock = $this->getLayout()
            ->createBlock('hubco_dictionary_adminhtml/make');

        // Add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($makeBlock)
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
        $make = Mage::getModel('hubco_dictionary/make');
        if ($makeId = $this->getRequest()->getParam('id', false)) {
            $make->load($makeId);

            if ($make->getId() < 1) {
                $this->_getSession()->addError(
                    $this->__('This make no longer exists.')
                );
                return $this->_redirect(
                    'hubco_dictionary_admin/make/index'
                );
            }
        }

        // process $_POST data if the form was submitted
        if ($postData = $this->getRequest()->getPost('makeData')) {
            try {
                foreach ($postData as $key => $value)
                {
                  if (is_array($value) && !isset($_FILES['$key']))
                  {
                    $postData[$key] = implode(',',$value);
                  }
                }
                $make->addData($postData);
                $make->save();

                $this->_getSession()->addSuccess(
                    $this->__('The make has been saved.')
                );

                // redirect to remove $_POST data from the request
                return $this->_redirect(
                    'hubco_dictionary_admin/make/edit',
                    array('id' => $make->getId())
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
        Mage::register('current_make', $make);

        // Instantiate the form container.
        $makeEditBlock = $this->getLayout()->createBlock(
            'hubco_dictionary_adminhtml/make_edit'
        );

        // Add the form container as the only item on this page.
        $this->loadLayout()
            ->_addContent($makeEditBlock)
            ->renderLayout();
    }

    public function deleteAction()
    {
        $make = Mage::getModel('hubco_dictionary/make');

        if ($makeId = $this->getRequest()->getParam('id', false)) {
            $make->load($makeId);
        }

         if ($make->getId() < 1) {
            $this->_getSession()->addError(
                $this->__('This make no longer exists.')
            );
            return $this->_redirect(
                'hubco_dictionary_admin/make/index'
            );
        }

        try {
            $make->delete();

            $this->_getSession()->addSuccess(
                $this->__('The make has been deleted.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect(
            'hubco_dictionary_admin/make/index'
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
                    ->isAllowed('hubco_dictionary/make');
                break;
        }

        return $isAllowed;
    }
}