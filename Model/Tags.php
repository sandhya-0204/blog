<?php
/**
 * Created by PhpStorm.
 * User: sprinix
 * Date: 18/11/24
 * Time: 5:29 PM
 */

namespace Sprinix\Blogs\Model;


use Magento\Framework\Model\AbstractModel;
use \Sprinix\Blogs\Model\ResourceModel\Tags as ResourceTags;

class Tags extends AbstractModel
{
    /**
     * Resource Initialization
     */
    protected function _construct()
    {
        $this->_init(ResourceTags::class);
    }
}