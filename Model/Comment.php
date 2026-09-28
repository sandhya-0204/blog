<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model;


use Magento\Framework\Model\AbstractModel;

/**
 * Class Comment
 * @package Sprinix\Blogs\Model
 */
class Comment extends AbstractModel
{
    /**
     * Resource Initialization
     */
    protected function _construct()
    {
        $this->_init(\Sprinix\Blogs\Model\ResourceModel\Comment::class);
    }
}