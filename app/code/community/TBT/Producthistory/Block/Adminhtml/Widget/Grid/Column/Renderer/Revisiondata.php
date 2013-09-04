<?php
class TBT_Producthistory_Block_Adminhtml_Widget_Grid_Column_Renderer_Revisiondata extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function _getValue(Varien_Object $row)
    {
        $data = Zend_Json::decode($row->getDataHash());
        $html = '<a href="#" onclick="$(\'revision-preview-table-'.$row->getProducthistoryRevisionId() . '\').toggle(); return false">' . Mage::helper('producthistory')->__('Preview') . '</a>';
        $html .= '<table id="revision-preview-table-'.$row->getProducthistoryRevisionId() . '" style="display: none;">';
        foreach ($data as $key => $value) {
            $html .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
        }
        $html .= '</table>';
        return $html;
    }
}