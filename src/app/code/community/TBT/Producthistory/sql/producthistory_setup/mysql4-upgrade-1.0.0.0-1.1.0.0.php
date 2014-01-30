<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE `{$this->getTable('producthistory_revision')}` ADD COLUMN `admin_user_id` int(11) NULL AFTER `data_hash`;

");

$installer->endSetup();



