<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\ResourceModel\CommentReply;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Sprinix\Blogs\Model\CommentReply;

/**
 * Class Collection
 * @package Sprinix\Blogs\Model\ResourceModel\CommentReply
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'reply_id';

    /**
     * Resource Initialization
     */
    protected function _construct()
    {
        $this->_init(CommentReply::class, \Sprinix\Blogs\Model\ResourceModel\CommentReply::class);
    }

    /**
     * Joining two or more tables
     */
    protected function _renderFiltersBefore()
    {
        $posts = $this->getTable('posts');
        $comment = $this->getTable('post_comment');
        $this->getSelect()
            ->joinLeft(['posts' => $posts], 'main_table.post_id = posts.post_id', ['title'])
            ->joinLeft(['comment' => $comment], 'main_table.replied_to = comment.comment_id', ['comment_created_at' => 'created_at', 'comment_updated_at' => 'updated_at']);

        parent::_renderFiltersBefore();
    }
}
