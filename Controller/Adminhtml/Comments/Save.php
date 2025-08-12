<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Comments;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Sprinix\Blogs\Model\CommentFactory;

/**
 * Class Save
 * @package Sprinix\Blogs\Controller\Adminhtml\Comments
 */
class Save extends Action
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
        Action\Context $context,
        CommentFactory $modelFactory,
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
            $id = $this->getRequest()->getParam('comment_id');
            if(!empty($id)) {
                $model = $this->modelFactory->create()->load($id);
                $data = (array)$this->getRequest()->getPost();
                $model->setData($data);
                $model->save();
                $this->messageManager->addSuccessMessage(__("Your Comment Saved Successfully."));
            }else {
                $this->messageManager->addErrorMessage(__("Error"));
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Save : ' . $e->getMessage()));
        }
        return $this->_redirect('*/*');
    }
}