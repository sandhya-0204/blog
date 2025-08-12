<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Reply;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Sprinix\Blogs\Model\CommentReplyFactory;

/**
 * Class Delete
 * @package Sprinix\Blogs\Controller\Adminhtml\Reply
 */
class Delete extends Action
{

    /**
     * @var CommentReplyFactory
     */
    protected $commentReplyFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param CommentReplyFactory $commentReplyFactory
     */
    public function __construct(
        Context $context,
        CommentReplyFactory $commentReplyFactory
    ){
        parent::__construct($context);
        $this->commentReplyFactory = $commentReplyFactory;
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('reply_id');
        try {
            if(!empty($id)) {
                $commentReply = $this->commentReplyFactory->create()->load($id);
                $commentReply->delete();
            }
            $this->messageManager->addSuccessMessage(__('Deleted Successfully'));
        }catch(\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Delete : ' . $e->getMessage()));
        }
        return $this->_redirect('blogs/reply/index');
    }
}