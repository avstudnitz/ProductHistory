<?php
/**
 * Revision grid
 *
 * @category   TBT
 * @package    TBT_Producthistory
 * @author     Anders Rasmussen <anders@crius.dk>
 */
class TBT_Producthistory_Block_Adminhtml_Catalog_Product_Edit_Tab_Revision_Grid extends Mage_Adminhtml_Block_Widget_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Construct grid
     */
	public function __construct()
    {
        parent::__construct();
        $this->setId('revisionGrid');
        $this->setDefaultSort('producthistory_revision_id');
        $this->setDefaultDir('desc');
		$this->setUseAjax(true);
    }

    /**
     * Prepare collection
     *
     * @return TBT_Producthistory_Block_Adminhtml_Catalog_Product_Edit_Tab_Revision_Grid
     */
    protected function _prepareCollection()
    {
        // Load all revisions for product
        $collection = Mage::getModel('producthistory/revision')->getCollection();
		$collection->addFieldToFilter('product_id', Mage::registry('product')->getId());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return TBT_Producthistory_Block_Adminhtml_Catalog_Product_Edit_Tab_Revision_Grid
     */
    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();

        // Revision ID
		$this->addColumn('producthistory_revision_id', array(
            'header'    => Mage::helper('producthistory')->__('Revision No.'),
            'align'     => 'left',
            'index'     => 'producthistory_revision_id',
			'width'     => '80px',
        ));
        
        // Revision date
        $this->addColumn('revision_date', array(
            'header'    => Mage::helper('producthistory')->__('Date'),
            'align'     => 'left',
            'index'     => 'revision_date',
            'type'      => 'datetime'
        ));
        
        // Admin user
        $this->addColumn('admin_user_id', array(
            'header'    => Mage::helper('producthistory')->__('Admin User'),
            'align'     => 'left',
            'index'     => 'admin_user_id',
            'type'      => 'options',
            'options'   => $this->_getAdminUserNames()
        ));

        // Data
        /*$this->addColumn('data_hash', array(
            'header'    => Mage::helper('producthistory')->__('Data'),
            'align'     => 'left',
            'index'     => 'data_hast',
            'renderer'  => 'producthistory/adminhtml_widget_grid_column_renderer_revisiondata',
            'filter'    => false,
            'sortable'  => false,
            'width'     => '350px'
        ));*/
        
        // Link to product edit page with revision parameter
        $this->addColumn('action', array(
            'header'    => Mage::helper('producthistory')->__('Load Revision'),
            'align'     => 'left',
            'type'      => 'action',
            'getter'    => 'getProducthistoryRevisionId',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('producthistory')->__('Load'),
                    'url'     => array(
                        'base'=>'adminhtml/catalog_product/edit',
                        'params'=>array('_current'=>true)
                    ),
                    'field'   => 'load_revision'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'width'     => '80px'
        ));

        return parent::_prepareColumns();
    }

	/**
	 * Retrieve the label used for the tab relating to this block
	 *
	 * @return string
	 */
    public function getTabLabel()
    {
    	return $this->__('Product History');
    }
    
    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
    	return $this->__('Product History');
    }

	/**
	 * Forces the tab to display
	 *
	 * @return true
	 */
    public function canShowTab()
    {
    	return true;
    }
    
    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
    	return false;
    }

    /**
     * @return Mage_Admin_Model_Resource_User_Collection
     */
    protected function _getAdminUserNames()
    {
        $adminUserNames = array();
        foreach (Mage::getResourceModel('admin/user_collection') as $adminUser) {
            /** @var Mage_Admin_Model_User $adminUser */
            $adminUserNames[$adminUser->getId()] = $adminUser->getUsername() . ' [' . $adminUser->getFirstname() . ' ' . $adminUser->getLastname() . ']';
        }
        return $adminUserNames;
    }
}