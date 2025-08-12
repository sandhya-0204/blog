<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model;

use Magento\Framework\Model\AbstractModel;
use Sprinix\Blogs\Model\ResourceModel\Categories as CategoriesResource;

/**
 * Class Categories
 * @package Sprinix\Blogs\Model
 */
class Categories extends AbstractModel
{
    /**
     * Resource Intialization
     */
    protected function _construct()
    {
        $this->_init(CategoriesResource::class);
    }
}
