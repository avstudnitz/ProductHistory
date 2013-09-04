<?php


class TBT_Producthistory_Model_Revision extends Mage_Core_Model_Abstract {
	
	public function _construct() {
		parent::_construct ();
		$this->_init ( 'producthistory/revision' );
	}
	
	
}