<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Sprinix\Blogs\Model\CommentFactory;

/**
 * Class Comment
 * @package Sprinix\Blogs\Controller\Index
 */
class Comment extends Action
{
    /**
     * @var CommentFactory
     */
    protected $modelFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * Comment constructor.
     * @param Context $context
     * @param ResultFactory $resultFactory
     * @param CommentFactory $modelFactory
     */
    public function __construct(
        Context $context,
        CommentFactory $modelFactory,
        ResultFactory $resultFactory
    ){
        parent::__construct($context);
        $this->modelFactory = $modelFactory;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $model = $this->modelFactory->create();
            $data = (array)$this->getRequest()->getPost();
            $model->setData($data);
            $model->save();
            $this->messageManager->addSuccessMessage(__("Your Comment Saved Successfully."));
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}