<?php


class TBT_Producthistory_Model_Mysql4_Revision extends Mage_Core_Model_Mysql4_Abstract {
    
	public function _construct() {
		$this->_init ( 'producthistory/revision', 'producthistory_revision_id' );
	}
}