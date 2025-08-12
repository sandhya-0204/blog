<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Reply;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Sprinix\Blogs\Model\CommentReplyFactory;

/**
 * Class Save
 * @package Sprinix\Blogs\Controller\Adminhtml\Comments
 */
class Save extends Action
{
    /**
     * @var CommentReplyFactory
     */
    protected $modelFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param CommentReplyFactory $modelFactory
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        Action\Context $context,
        CommentReplyFactory $modelFactory,
        ResultFactory $resultFactory
    ){
        parent::__construct($context);
        $this->modelFactory = $modelFactory;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('reply_id');
            if(!empty($id)) {
                $model = $this->modelFactory->create()->load($id);
                $data = (array)$this->getRequest()->getPost();
                $model->setData($data);
                $model->save();
                $this->messageManager->addSuccessMessage(__("Your Data Saved Successfully."));
            }else {
                $this->messageManager->addErrorMessage(__("Error"));
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__("Error"));
        }
        return $this->_redirect('*/*');
    }
}