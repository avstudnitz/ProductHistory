<?php

$installer = $this;

$installer->startSetup();

// Another way to get the version number but we wont do this here for the example to make sense :)
$install_version = Mage::getConfig ()->getNode ( 'modules/TBT_Producthistory/version' );


$message = Mage::getModel('adminnotification/inbox');
$message->setSeverity(Mage_AdminNotification_Model_Inbox::SEVERITY_NOTICE);
$message->setDateAdded(date("c", time()+2));
$message->setTitle("Product History Extension by G10 has been upraded to version 1.1.0.0!");
$message->setDescription("Product History Extension by G10 has been upraded!");
$message->setUrl("http://www.magentocommerce.com");
$message->save();


$installer->endSetup();



