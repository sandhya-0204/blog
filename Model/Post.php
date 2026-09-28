<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model;

use Magento\Framework\Model\AbstractModel;
use Sprinix\Blogs\Model\ResourceModel\Post as PostResource;

/**
 * Class Post
 * @package Sprinix\Blogs\Model
 */
class Post extends AbstractModel
{
    /**
     * Resource Initialization
     */
    protected function _construct()
    {
        $this->_init(PostResource::class);
    }
}
