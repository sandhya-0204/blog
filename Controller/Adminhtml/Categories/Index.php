<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use \Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Sprinix\Blogs\Helper\Data;

/**
 * Class Index
 * @package Sprinix\Blogs\Controller\Adminhtml\Categories
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * Index constructor.
     * @param Context $context
     * @param Data $helperData
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Data $helperData,
        PageFactory $resultPageFactory
    ){
        parent::__construct($context);
        $this->_helperData = $helperData;
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * @return ResponseInterface|Page
     */
    public function execute()
    {
        try {
            $isModuleEnabled = $this->_helperData->getGeneralConfig('enable');
            if($isModuleEnabled) {
                $resultPage = $this->_resultPageFactory->create();
                $resultPage->getConfig()->getTitle()->prepend(__('Categories'));
                return $resultPage;
            }
        }catch(\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $this->_redirect("admin/dashboard");
    }
}