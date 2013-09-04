<?php

/**
 * Helper Data
 *
 * @category   TBT
 * @package    TBT_Rewards
 * @author     WDCA Sweet Tooth Team <contact@wdca.ca>
 */
class TBT_Producthistory_Helper_Data extends Mage_Core_Helper_Abstract {

    public function isEnabled() {
        return true;
    }
    
    public function resetRevisionLoadRequest() {
        $this->_getRequest()->setParam('load_revision', null);
        return $this;
    }

}
