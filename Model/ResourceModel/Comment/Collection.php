<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\ResourceModel\Comment;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Sprinix\Blogs\Model\Comment;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'comment_id';

    /**
     * Resource Initialization
     */
    protected function _construct()
    {
        $this->_init(Comment::class, \Sprinix\Blogs\Model\ResourceModel\Comment::class);
    }

    /**
     * Joining two or more than two tables
     */
    protected function _renderFiltersBefore()
    {
       try {
           $posts = $this->getTable('posts');
           $reply = $this->getTable('comment_reply');
           $this->getSelect()
               ->joinLeft(['posts'=>$posts], "main_table.commented_on = posts.post_id", ["title"])
               ->joinLeft(['reply'=>$reply], "main_table.comment_id = reply.replied_to", [])
               ->columns(['reply_count' => new \Zend_Db_Expr('COUNT(reply.reply_id)')])
               ->group('main_table.comment_id');
       }catch (\Exception $e) {
           $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
       }
       parent::_renderFiltersBefore();
    }
}