<?php

class TBT_Producthistory_Model_Mysql4_Revision_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
	
	public function _construct() {
		$this->_init ( 'producthistory/revision' );
	}
	
	public function _initSelect() {
		//die("<PRE>".$this->getSelect()->__toString(). "</PRE>");
		return parent::_initSelect ();
	}
	
}