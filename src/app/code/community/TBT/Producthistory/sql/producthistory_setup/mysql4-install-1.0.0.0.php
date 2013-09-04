<?php

$installer = $this;

$installer->startSetup();

// Another way to get the version number but we wont do this here for the example to make sense :)
$install_version = Mage::getConfig ()->getNode ( 'modules/TBT_Producthistory/version' );


$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('producthistory_revision')}` (
  `producthistory_revision_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `revision_date` datetime DEFAULT NULL,
  `data_hash` text,
  PRIMARY KEY (`producthistory_revision_id`),
  UNIQUE KEY `producthistory_revision_id` (`producthistory_revision_id`)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8;


");


$installer->run("
INSERT INTO `{$this->getTable('producthistory_revision')}`
(
  `store_id`,
  `product_id`,
  `revision_date`,
  `data_hash`
) 
VALUE (
  0,
  1,
  now(),
  'test data'
);

");

$message = Mage::getModel('adminnotification/inbox');
$message->setSeverity(Mage_AdminNotification_Model_Inbox::SEVERITY_NOTICE);
$message->setDateAdded(date("c", time()+2));
$message->setTitle("Product History Extension by G10 has been installed!");
$message->setDescription("Product History Extension by G10 has been installed!");
$message->setUrl("http://www.magentocommerce.com");
$message->save();


$installer->endSetup();



