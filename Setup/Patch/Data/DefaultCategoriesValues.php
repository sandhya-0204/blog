<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Sprinix\Blogs\Model\CategoriesFactory;
use Sprinix\Blogs\Controller\Adminhtml\Posts\CustomUrl as CustomUrlRewrite;

/**
 * Class DefaultCategoriesValues
 * @package Sprinix\Blogs\Setup\Patch\Data
 */
class DefaultCategoriesValues implements DataPatchInterface
{
    /**
     * @var CategoriesFactory
     */
    protected $categoriesFactory;

    /**
     * @var CustomUrlRewrite
     */
    protected $urlRewrite;

    /**
     * DefaultCategoriesValues constructor.
     * @param CategoriesFactory $categoriesFactory
     * @param CustomUrlRewrite $customUrlRewrite
     */
    public function __construct(
        CategoriesFactory $categoriesFactory,
        CustomUrlRewrite $customUrlRewrite
    ) {
        $this->categoriesFactory = $categoriesFactory;
        $this->urlRewrite = $customUrlRewrite;
    }

    /**
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Adding Default Categories In Categories Table
     */
    public function apply()
    {
        $categories = [];
        foreach ($categories as $category) {
            try {
                $model = $this->categoriesFactory->create();
                $model->setData('category', $category['category']);
                $model->setData('category_url_key', $category['url_key']);
                $model->setData('category_status', 'Enabled');
                $newData = $model->save();

                $this->urlRewrite->createCategoryUrlRewrite(
                    $newData->getCategoryId(),
                    $category['url_key']
                );
            }catch (\Exception $e){}

        }
    }
}
