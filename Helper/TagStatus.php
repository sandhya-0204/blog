<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Sprinix\Blogs\Model\ResourceModel\Tags\CollectionFactory as TagsCollectionFactory;

/**
 * Class TagStatus
 * @package Sprinix\Blogs\Helper
 */
class TagStatus extends AbstractHelper
{
    /**
     * @var TagsCollectionFactory
     */
    protected $tagsCollectionFactory;

    /**
     * TagStatus constructor.
     * @param TagsCollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        TagsCollectionFactory $collectionFactory,
        Context $context
    ) {
        parent::__construct($context);
        $this->tagsCollectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function getEnabledTagsIds() {
        try {
            $tagsCollection = $this->tagsCollectionFactory->create();
            $tags =  $tagsCollection
                ->addFieldToSelect(['tag_id'])
                ->addFieldToFilter('tag_status', ['eq' => 'Enabled'])
                ->getItems();
            $ids = [];
            foreach ($tags as $tag) {
                array_push($ids, (int) $tag->getTagId());
            }
            return $ids;
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
    }

    /**
     * @param $tagId
     * @return DataObject[]
     */
    public function getEnabledTagById($tagId) {
        try {
            $tagCollection = $this->tagsCollectionFactory->create();
            $tag =  $tagCollection
                ->addFieldToSelect(['tag_id'])
                ->addFieldToFilter('tag_id', ['eq' => $tagId])
                ->addFieldToFilter('tag_status', ['eq' => 'Enabled'])
                ->getItems();

            return $tag;
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
    }
}