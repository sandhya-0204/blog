<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Comments;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Sprinix\Blogs\Model\CommentFactory;
use Sprinix\Blogs\Model\CommentReplyFactory;

/**
 * Class Delete
 * @package Sprinix\Blogs\Controller\Adminhtml\Comments
 */
class Delete extends Action
{
    /**
     * @var CommentFactory
     */
    protected $commentFactory;

    /**
     * @var CommentReplyFactory
     */
    protected $commentReplyFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param CommentFactory $commentFactory
     * @param CommentReplyFactory $commentReplyFactory
     */
    public function __construct(
        Context $context,
        CommentFactory $commentFactory,
        CommentReplyFactory $commentReplyFactory
    ){
        parent::__construct($context);
        $this->commentFactory = $commentFactory;
        $this->commentReplyFactory = $commentReplyFactory;
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('comment_id');
            $comment = $this->commentFactory->create()->load($id);
            $comment->delete();
            $this->messageManager->addSuccessMessage(__('Deleted Successfully'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Delete : ' . $e->getMessage()));
        }
        return $this->_redirect('blogs/comments/index');
    }
}