<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

/**
 * Class CommentReply
 * @package Sprinix\Blogs\Model\ResourceModel
 */
class CommentReply extends AbstractDb
{
    /**
     * Resource Initialization
     */
    protected function _construct()
    {
        $this->_init('comment_reply', 'reply_id');
    }
}
