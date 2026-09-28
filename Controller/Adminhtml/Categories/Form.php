<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use \Magento\Framework\App\ResponseInterface;
use \Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Sprinix\Blogs\Helper\Data;

/**
 * Class Form
 * @package Sprinix\Blogs\Controller\Adminhtml\Categories
 */
class Form extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * Form constructor.
     * @param Action\Context $context
     * @param Data $helperData
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Action\Context $context,
        Data $helperData,
        PageFactory $pageFactory
    ){
        parent::__construct($context);
        $this->_helperData = $helperData;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return ResponseInterface|Page
     */
    public function execute()
    {
        try {
            $isModuleEnabled = $this->_helperData->getGeneralConfig('enable');
            if($isModuleEnabled) {
                $resultPage = $this->pageFactory->create();
                $id = $this->getRequest()->getParam('category_id');
                if (isset($id)) {
                    $resultPage->getConfig()->getTitle()->prepend(__('Edit Category'));
                } else {
                    $resultPage->getConfig()->getTitle()->prepend(__('Add New Category'));
                }
                return $resultPage;
            }
            return $this->_redirect("admin/dashboard");
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }
}