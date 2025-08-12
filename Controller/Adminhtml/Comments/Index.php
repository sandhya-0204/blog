<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Comments;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Sprinix\Blogs\Helper\Data;

/**
 * Class Index
 * @package Sprinix\Blogs\Controller\Adminhtml\Comments
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * Index constructor.
     * @param Context $context
     * @param Data $helperData
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        Data $helperData,
        PageFactory $pageFactory
    ){
        parent::__construct($context);
        $this->_helperData = $helperData;
        $this->resultPageFactory = $pageFactory;
    }

    /**
     * @return ResponseInterface|Page
     */
    public function execute()
    {
        try {
            $isModuleEnabled = $this->_helperData->getGeneralConfig('enable');
            if($isModuleEnabled) {
                $page = $this->resultPageFactory->create();
                $page->getConfig()->getTitle()->prepend('Users Comments');
                return $page;
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $this->_redirect("admin/dashboard");
    }
}