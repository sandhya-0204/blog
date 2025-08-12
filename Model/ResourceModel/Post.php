<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Post
 * @package Sprinix\Blogs\Model\ResourceModel
 */
class Post extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('posts', 'post_id');
    }
}
