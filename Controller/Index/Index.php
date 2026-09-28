<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */
namespace Sprinix\Blogs\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Sprinix\Blogs\Helper\Data;

/**
 * Class Index
 * @package Sprinix\Blogs\Controller\Index
 */
class Index extends Action
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
        $this->pageFactory = $pageFactory;
        $this->_helperData = $helperData;
    }

    /**
     * @return Page
     */
    public function execute()
    {
        try {
            $isModuleEnabled = $this->_helperData->getGeneralConfig('enable');
            $resultPage = $this->pageFactory->create();
            if($isModuleEnabled) {

                $Title = $this->_helperData->getBlogListPageTitle();
                $metaTitle = $this->_helperData->getBlogListMetaTitle();
                $metaDescription = $this->_helperData->getBlogListMetaDescription();
                $metaKeywords = $this->_helperData->getBlogListMetaKeywords();
                if ($Title) {
                    $resultPage->getConfig()->getTitle()->set($Title);
                }
                if ($metaTitle !== '') {
                    $resultPage->getConfig()->setMetadata('title', $metaTitle);
                }
                if ($metaDescription !== '') {
                    $resultPage->getConfig()->setDescription($metaDescription);
                }
                if ($metaKeywords !== '') {
                    $resultPage->getConfig()->setKeywords($metaKeywords);
                }
                return $resultPage;
            }else {
                $this->_redirect('/');
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }
}
