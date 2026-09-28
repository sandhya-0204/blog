<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Sprinix\Blogs\Model\ResourceModel\Tags\CollectionFactory as TagsCollectionFactory;

/**
 * Class TagsOptions
 * @package Sprinix\Blogs\Model\Source
 */
class TagsOptions implements OptionSourceInterface
{
    /**
     * @var TagsCollectionFactory
     */
    protected $tagsCollectionFactory;

    /**
     * TagsOptions constructor.
     * @param TagsCollectionFactory $tagsCollectionFactory
     */
    public function __construct(
        TagsCollectionFactory $tagsCollectionFactory
    ) {
        $this->tagsCollectionFactory = $tagsCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        try {
            $collections = $this->tagsCollectionFactory->create();
            $tags = $collections->getItems();
            foreach ($tags as $tag) {
                array_push($options, [
                    'value' => $tag->getTagId(),
                    'label' => $tag->getTagName(),
                    'optgroup' => false
                ]);
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
        return $options;
    }
}
