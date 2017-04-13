<?php
class HubCo_Dictionary_Adminhtml_AbbreviationController
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
        $abbreviationBlock = $this->getLayout()
            ->createBlock('hubco_dictionary_adminhtml/abbreviation');

        // Add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($abbreviationBlock)
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
        $abbreviation = Mage::getModel('hubco_dictionary/abbreviation');
        if ($abbreviationId = $this->getRequest()->getParam('id', false)) {
            $abbreviation->load($abbreviationId);

            if ($abbreviation->getId() < 1) {
                $this->_getSession()->addError(
                    $this->__('This abbreviation no longer exists.')
                );
                return $this->_redirect(
                    'hubco_dictionary_admin/abbreviation/index'
                );
            }
        }

        // process $_POST data if the form was submitted
        if ($postData = $this->getRequest()->getPost('abbreviationData')) {
            try {
                foreach ($postData as $key => $value)
                {
                  if (is_array($value) && !isset($_FILES['$key']))
                  {
                    $postData[$key] = implode(',',$value);
                  }
                }
                $abbreviation->addData($postData);
                $abbreviation->save();
                Mage::dispatchEvent('save_abbreviation_event', $abbreviation);
                $this->_getSession()->addSuccess(
                    $this->__('The abbreviation has been saved.')
                );

                // redirect to remove $_POST data from the request
                return $this->_redirect(
                    'hubco_dictionary_admin/abbreviation/edit',
                    array('id' => $abbreviation->getId())
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
        Mage::register('current_abbreviation', $abbreviation);

        // Instantiate the form container.
        $abbreviationEditBlock = $this->getLayout()->createBlock(
            'hubco_dictionary_adminhtml/abbreviation_edit'
        );

        // Add the form container as the only item on this page.
        $this->loadLayout()
            ->_addContent($abbreviationEditBlock)
            ->renderLayout();
    }

    public function deleteAction()
    {
        $abbreviation = Mage::getModel('hubco_dictionary/abbreviation');

        if ($abbreviationId = $this->getRequest()->getParam('id', false)) {
            $abbreviation->load($abbreviationId);
        }

         if ($abbreviation->getId() < 1) {
            $this->_getSession()->addError(
                $this->__('This abbreviation no longer exists.')
            );
            return $this->_redirect(
                'hubco_dictionary_admin/abbreviation/index'
            );
        }

        try {
            $abbreviation->delete();

            $this->_getSession()->addSuccess(
                $this->__('The abbreviation has been deleted.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect(
            'hubco_dictionary_admin/abbreviation/index'
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
                    ->isAllowed('hubco_dictionary/abbreviation');
                break;
        }

        return $isAllowed;
    }
}