<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\ResourceModel\Categories;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Sprinix\Blogs\Model\Categories;

/**
 * Class Collection
 * @package Sprinix\Blogs\Model\ResourceModel\Categories
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'category_id';

    /**
     * Resource Initialization
     */
    protected function _construct()
    {
        $this->_init(Categories::class, \Sprinix\Blogs\Model\ResourceModel\Categories::class);
    }
}