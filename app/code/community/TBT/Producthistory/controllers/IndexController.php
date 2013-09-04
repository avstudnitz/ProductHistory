<?php

class TBT_Producthistory_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        die(Mage::helper('producthistory')->__("If you're seeing this page it confirms that the Product History Extension is installed and the API is ready for use."));

        return $this;
    }

    /**
     * Test controller... really just tests loading a revision model
     */
    public function testModelAction() {
        
        $rev = Mage::getModel('producthistory/revision')->load(1);
        
        print_r($rev->getData());
        return $this;
    }
    
}