<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Comment
 * @package Sprinix\Blogs\Model\ResourceModel
 */
class Comment extends AbstractDb
{
    /**
     * Resource Initialization
     */
    protected function _construct()
    {
        $this->_init('post_comment', 'comment_id');
    }
}