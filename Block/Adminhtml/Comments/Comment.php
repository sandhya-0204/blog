<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs Pvt Ltd. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Block\Adminhtml\Comments;

use Magento\Framework\View\Element\Template;
use Sprinix\Blogs\Model\ResourceModel\CommentReply\CollectionFactory as ReplyCollectionFactory;

/**
 * Class Comment
 * @package Sprinix\Blogs\Block\Adminhtml\Comments
 */
class Comment extends Template
{
    /**
     * @var ReplyCollectionFactory
     */
    protected $replyCollectionFactory;


    /**
     * Comment constructor.
     * @param ReplyCollectionFactory $replyCollectionFactory
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        ReplyCollectionFactory $replyCollectionFactory,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->replyCollectionFactory = $replyCollectionFactory;
    }

    /**
     * @param $commentId
     * @return array
     */
    public function getAllReplies($commentId)
    {
        $replies = [];
        try {
            $replyCollection = $this->replyCollectionFactory->create();
            $replies = $replyCollection->getItemsByColumnValue('replied_to', $commentId);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Get Reply : ' . $e->getMessage()));
        }
        return $replies;
    }
}
