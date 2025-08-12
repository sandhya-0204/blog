<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Helper;

use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory;

/**
 * Class DeletePostCategoryId
 * @package Sprinix\Blogs\Helper
 */
class DeletePostCategoryId
{
    /**
     * @var CollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * DeletePostCategoryId constructor.
     * @param CollectionFactory $postCollectionFactory
     */
    public function __construct(
        CollectionFactory $postCollectionFactory
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
    }

    /**
     * @param $id
     */
    public function deleteCategoryId($id)
    {
        try {
            $collection = $this->postCollectionFactory->create();
            $posts = $collection->addFieldToSelect(['post_id', 'category'])
                ->addFieldToFilter('category', ['like' => "%{$id}%"])
                ->getItems();

            foreach ($posts as $post) {
                $category = $post->getCategory();
                if (!empty($category)) {
                    $categoryArr = explode(',', $category);
                    $key = array_search($id, $categoryArr);
                    if ($key !== false) {
                        array_splice($categoryArr, $key, 1);
                    }
                    $newCategoryStr = implode(',', $categoryArr);
                    $currentPost = $post;
                    if (!empty($newCategoryStr)) {
                        $currentPost->setData('category', $newCategoryStr);
                    } else {
                        $currentPost->setData('category', '');
                        $currentPost->setData('status', 'Disabled');
                    }
                    $currentPost->save();
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
    }
}