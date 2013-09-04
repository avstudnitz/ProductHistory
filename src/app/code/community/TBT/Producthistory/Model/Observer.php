<?php

/**
 * @category   TBT
 * @package    TBT_Producthistory
 * @author     Andreas von Studnitz <avs@avs-webentwicklung.de>
 */

class TBT_Producthistory_Model_Observer
{
    const TEXTAREA_LENGTH = 50;

    /**
     * Save product attributes as revision on product saving
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function onProductSaveAfter($observer) {

        $product = $observer->getProduct();

        $revisionDataArray = $this->_getRevisionDataArray($product);
        $revisionDataHash = Zend_Json::encode($revisionDataArray);

        $lastRevisionDataHash = $this->_getLastRevisionDataHash($product->getId());

        // return if nothing has changed
        if ($revisionDataHash == $lastRevisionDataHash) return;

        $revisionDate = new Zend_Date();
        $revisionData = array(
            'store_id' => $product->getStoreId(),
            'product_id' => $product->getId(),
            'data_hash' => $revisionDataHash,
            'revision_date' => $revisionDate->get(Zend_Date::ISO_8601),
        );

        $revision = Mage::getModel('producthistory/revision');

        $revision->setData($revisionData)->save();
        
        
    }

    /**
     * Create array of supported product attributes
     *
     * @param Mage_Catalog_Model_Product $product
     * @return array
     */
    protected function _getRevisionDataArray($product)
    {
        $revisionDataArray = array();

        $attributes = Mage::getModel('eav/entity_attribute')->getCollection()
            ->setAttributeSetFilter($product->getAttributeSetId())
            ->load();

        foreach($attributes as $attribute)  {
            if (!in_array($attribute->getFrontendInput(), $this->_getAllowedAttributeFrontendInputTypes())) continue;

            $attributeCode = $attribute->getAttributeCode();
            if ($attributeCode == 'updated_at') continue;
            if ($attributeCode == 'created_at') continue;
            if ($attributeCode == 'tier_price') continue;

            $revisionDataArray[$attributeCode] = $this->_getAttributeValue($product, $attribute);
        }

        Mage::log($revisionDataArray);

        return $revisionDataArray;
    }

    /**
     * Get last revision of this product and return data hash
     *
     * @param int $productId
     * @return string
     */
    protected function _getLastRevisionDataHash($productId)
    {
        $lastRevision = Mage::getModel('producthistory/revision')->getCollection()
                ->addFieldToFilter('product_id', $productId)
                ->setOrder('revision_date', 'DESC')
                ->getFirstItem();

        if ($lastRevision->getId()) {
            return $lastRevision->getDataHash();
        }

        return '';
    }

    /**
     * Get array of allowed frontend input types, which should be compared
     *
     * @return array
     */
    protected function _getAllowedAttributeFrontendInputTypes()
    {
        return array(
            'text',
            'textarea',
            'select',
            'multiselect',
            'price',
            'date',
        );
    }

    /**
     * Get readable attribute value from raw attribute value depending on frontend input type
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return string
     */
    protected function _getAttributeValue($product, $attribute)
    {
        $rawAttributeValue = $product->getData($attribute->getAttributeCode());

        switch ($attribute->getFrontendInput()) {

            case 'select':
                return $product->getAttributeText($attribute->getAttributeCode());

            case 'multiselect':
                return implode(', ', $product->getAttributeText($attribute->getAttributeCode()));

            case 'price':
                return Mage::helper('core')->formatPrice($rawAttributeValue, false);

            case 'textarea':
                if (!$rawAttributeValue) return '';
                if (strlen(strip_tags($rawAttributeValue)) > self::TEXTAREA_LENGTH) {
                    // shorten value and add hash in order to be able to track changes
                    return sprintf('%s... (Hash %s)', substr(strip_tags($rawAttributeValue), 0, self::TEXTAREA_LENGTH), md5($rawAttributeValue));
                } else {
                    // don't shorten if it is short enough
                    return strip_tags($rawAttributeValue);
                }

            case 'date':
                if (!$rawAttributeValue) return '';
                $date = new Zend_Date();
                $date->set($rawAttributeValue, Zend_Date::ISO_8601);
                return Mage::helper('core')->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, false);

            case 'text':
                return $rawAttributeValue;
        }
    }
}