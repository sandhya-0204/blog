<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
use Sprinix\Blogs\Model\CommentReplyFactory;
use Sprinix\Blogs\Helper\Data;

/**
 * Class Reply
 * @package Sprinix\Blogs\Controller\Index
 */
class Reply extends Action
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
     * @var Data
     */
    protected $_helperData;

    /**
     * Reply constructor.
     * @param Context $context
     * @param CommentReplyFactory $modelFactory
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        Context $context,
        CommentReplyFactory $modelFactory,
        ResultFactory $resultFactory,
        Data $helperData,
    ){
        parent::__construct($context);
        $this->modelFactory = $modelFactory;
        $this->resultFactory = $resultFactory;
        $this->_helperData = $helperData;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $commentReplyStatus = $this->_helperData->getCommentSetting('new_reply_status');
            $model = $this->modelFactory->create();
            $data = (array)$this->getRequest()->getPost();
            $model->setData($data);
            $model->setData('reply_status', $commentReplyStatus);
            $model->save();
            $this->messageManager->addSuccessMessage(__("Your Reply Saved Successfully."));
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__("Error : " . $e->getMessage()));
        }
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
