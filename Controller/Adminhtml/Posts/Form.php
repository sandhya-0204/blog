<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Posts;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Sprinix\Blogs\Helper\Data;

/**
 * Class Form
 * @package Sprinix\Blogs\Controller\Adminhtml\Posts
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
     * @param PageFactory $pageFactory
     * @param Data $helperData
     */
    public function __construct(
        Action\Context $context,
        Data $helperData,
        PageFactory $pageFactory
    ){
        $this->pageFactory = $pageFactory;
        $this->_helperData = $helperData;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Page
     */
    public function execute()
    {
        try {
            $isModuleEnabled = $this->_helperData->getGeneralConfig('enable');
            if ($isModuleEnabled) {
                $resultPage = $this->pageFactory->create();
                $id = $this->getRequest()->getParam('post_id');
                if (isset($id)) {
                    $resultPage->getConfig()->getTitle()->prepend(__('Edit Post'));
                } else {
                    $resultPage->getConfig()->getTitle()->prepend(__('Add New Post'));
                }
                return $resultPage;
            }
            return $this->_redirect("admin/dashboard");
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }
}