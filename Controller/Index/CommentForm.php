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
 * Class CommentForm
 * @package Sprinix\Blogs\Controller\Index
 */
class CommentForm extends Action
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
     * CommentForm constructor.
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
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return Page
     */
    public function execute()
    {
        try {
            $isModuleEnabled = $this->_helperData->getGeneralConfig('enable');
            if($isModuleEnabled) {
                return $this->pageFactory->create();
            }else {
                $this->_redirect('/');
            }
        }catch (\Exception $e){
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }
}