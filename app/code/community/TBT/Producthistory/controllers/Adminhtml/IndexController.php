<?php
/**
 * Product history admin controller
 *
 * @category   TBT
 * @package    TBT_Producthistory
 * @author     Anders Rasmussen <anders@crius.dk>
 */
class TBT_Producthistory_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Load revisions and render as grid
     */
    public function historyAjaxAction()
    {
        // Init product and put into registry
	    $productId  = (int) $this->getRequest()->getParam('id');
        $product    = Mage::getModel('catalog/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));
        if ($productId) {
            try {
                $product->load($productId);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        Mage::register('product', $product);
        
        // Load layout and render
        $this->loadLayout();
        $this->renderLayout();
    }
}