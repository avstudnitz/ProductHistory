<?php
/**
 * Product page observer
 *
 * @category   TBT
 * @package    TBT_Producthistory
 * @author     Anders Rasmussen <anders@crius.dk>
 */
class TBT_Producthistory_Model_Product_Observer
{
    /**
     * Add history tab to product edit page
     *
     * @param  Varien_Event_Observer $observer
     * @return TBT_Producthistory_Model_Product_Observer
     */
    public function addHistoryTab($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
            if (Mage::app()->getRequest()->getActionName() == 'edit' || Mage::app()->getRequest()->getParam('type')) {
                $block->addTab('product_history', array(
                    'label'     => Mage::helper('producthistory')->__('Product History'),
                    'url'       => Mage::helper('adminhtml')->getUrl('producthistoryadmin/adminhtml_index/historyAjax', array('_current' => true)),
                    'class'     => 'ajax',
                ));
            }
        }
        return $this;
    }
}