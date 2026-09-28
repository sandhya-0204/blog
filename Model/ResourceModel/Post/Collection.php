<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\ResourceModel\Post;

/**
 * Class Collection
 * @package Sprinix\Blogs\Model\ResourceModel\Post
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'post_id';

    /**
     * Resource Initialization
     */
    public function _construct()
    {
        $this->_init(
            \Sprinix\Blogs\Model\Post::class,
            \Sprinix\Blogs\Model\ResourceModel\Post::class
        );
    }
}
