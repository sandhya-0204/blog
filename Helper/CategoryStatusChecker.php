<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Sprinix\Blogs\Model\ResourceModel\Categories\CollectionFactory as CategoriesCollectionFactory;

/**
 * Class CategoryStatusChecker
 * @package Sprinix\Blogs\Helper
 */
class CategoryStatusChecker extends AbstractHelper
{
    /**
     * @var CategoriesCollectionFactory
     */
    protected $categoriesCollectionFactory;

    /**
     * CategoryStatusChecker constructor.
     * @param CategoriesCollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        CategoriesCollectionFactory $collectionFactory,
        Context $context
    ) {
        parent::__construct($context);
        $this->categoriesCollectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function getEnabledCategoryIds() {
        try {
            $categoryCollection = $this->categoriesCollectionFactory->create();
            $categories =  $categoryCollection
                ->addFieldToSelect(['category_id'])
                ->addFieldToFilter('category_status', ['eq' => 'Enabled'])
                ->getItems();
            $ids = [];
            foreach ($categories as $category) {
                array_push($ids, (int) $category->getCategoryId());
            }
            return $ids;
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
    }

    /**
     * @param $categoryId
     * @return DataObject[]
     */
    public function getEnabledCategoryById($categoryId) {
        try {
            $categoryCollection = $this->categoriesCollectionFactory->create();
            $category =  $categoryCollection
                ->addFieldToSelect(['category_id'])
                ->addFieldToFilter('category_id', ['eq' => $categoryId])
                ->addFieldToFilter('category_status', ['eq' => 'Enabled'])
                ->getItems();

            return $category;
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }
}