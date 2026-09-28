<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Helper;

use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory;

/**
 * Class DeletePostTagId
 * @package Sprinix\Blogs\Helper
 */
class DeletePostTagId
{
    /**
     * @var CollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * DeletePostTagId constructor.
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
    public function deleteTagId($id)
    {
        try {
            $collection = $this->postCollectionFactory->create();
            $posts = $collection->addFieldToSelect(['tags', 'post_id'])
                ->addFieldToFilter('tags', ['like' => "%{$id}%"])->getItems();
            foreach ($posts as $post) {
                $tags = $post->getTags();
                if (!empty($tags)) {
                    $tagArr = explode(',', $tags);
                    $filteredTagArr = array_filter($tagArr, function ($tagId) use ($id) {
                        return (int)$tagId !== (int)$id;
                    });
                    $newTagStr = implode(',', $filteredTagArr);
                    $post->setData('tags', $newTagStr)->save();
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
    }
}