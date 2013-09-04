<?php

/**
 * @category   AvS
 * @package    AvS_Yoochoose
 * @author     Andreas von Studnitz <avs@avs-webentwicklung.de>
 */

class TBT_Producthistory_Model_Load_Observer extends Varien_Object
{

    /**
     * 
     * @param unknown_type $observer
     */
    public function beforeProductFormHtml($observer) {
        $block = $observer->getEvent()->getBlock();
        if( $block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes) {
            $request = $block->getRequest();
            $this->setRequest($request);
            
            $this->_checkLoadRev($block);
            $this->_replaceWithHistory();
            
        }
        return $this;
    }
    
    

    /**
     * 
     * @param unknown_type $obj
     */
    public function afterFormToHtml($obj) {
		$block = $obj->getEvent ()->getBlock ();
		$transport = $obj->getEvent ()->getTransport ();
    
        
		// Magento 1.3 and lower dont have this transport, so we can't do autointegration : (
		if(empty($transport)) {
			return $this;
		}
		
    
        if( !($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit))  {
            return $this;
        }
		
        $html = $transport->getHtml();
        $html = str_replace("load_revision/", "previously_loaded_revision/", $html);
        $transport->setHtml($html);
        
    
        return $this;
    }
    
    /**
     * 
     * @param Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes $block
     */
    protected function _checkLoadRev($block ) {
        $revision_id = $block->getRequest()->getParam('load_revision', null);
        
        // No revision exists!
        if(!$revision_id) {
            //die("Got to a revision history, but ID was null.");
            return $this; 
        }
        
        $revision = Mage::getModel('producthistory/revision')->load($revision_id);
        
        if(!$revision->getId()) {
            die("Got a revision ID, but the revision ID doesn't seem to exist.");
            return $this;
        }
        
        // TODO also load Id, etc.
        
        $this->setRevision($revision);
        
        return $this;
    }
    
    /**
     * replace Registered Product With History
     */
    protected function _replaceWithHistory() {
        
        if(!Mage::registry('product')) {
            die("no product found in the registry");
            return $this;
        }
        
        
        $revision = $this->getRevision();
        
        if(!$revision) {
            return $this;
        }
        
        if(!$revision->getId()) {
            return $this;
        }
        
        $data_array = (array)json_decode($revision->getDataHash());
        
        Mage::registry('product')->addData($data_array);
        
            
        
        return $this;
        
    }
    
}