<?php
/**
 * Created by PhpStorm.
 * User: sprinix
 * Date: 13/11/24
 * Time: 11:21 AM
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Reply;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Sprinix\Blogs\Helper\Data;

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
            if($isModuleEnabled) {
                $resultPage = $this->pageFactory->create();
                $id = $this->getRequest()->getParam('reply_id');
                if (isset($id)) {
                    $resultPage->getConfig()->getTitle()->prepend(__('Edit Reply'));
                }
                return $resultPage;
            }
            return $this->_redirect("admin/dashboard");
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }
}