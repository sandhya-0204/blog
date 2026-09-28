<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */
namespace Sprinix\Blogs\Block\Index;

use Magento\Framework\View\Element\Template;
use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory;
use Sprinix\Blogs\Helper\CategoryStatusChecker;

/**
 * Class NextPrev
 * @package Sprinix\Blogs\Block\Index
 */
class NextPrev extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var CategoryStatusChecker
     */
    protected $categoryStatusChecker;

    /**
     * NextPrev constructor.
     * @param Template\Context $context
     * @param CollectionFactory $postCollectionFactory
     * @param CategoryStatusChecker $categoryStatusChecker
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $postCollectionFactory,
        CategoryStatusChecker $categoryStatusChecker,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->postCollectionFactory = $postCollectionFactory;
        $this->categoryStatusChecker = $categoryStatusChecker;
    }

    /**
     * Get current blog post from parent Blog block.
     *
     * @return \Sprinix\Blogs\Model\Post|null
     */
    public function getCurrentPost()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock && method_exists($parentBlock, 'getPost')) {
            return $parentBlock->getPost();
        }
        return null;
    }

    /**
     * Get previous post.
     *
     * @return \Sprinix\Blogs\Model\Post|null
     */
    public function getPreviousPost()
    {
        $currentPost = $this->getCurrentPost();
        if (!$currentPost || !$currentPost->getId()) {
            return null;
        }

        $categoryIds = $this->categoryStatusChecker->getEnabledCategoryIds();
        if (empty($categoryIds)) {
            return null;
        }
        $collection = $this->postCollectionFactory->create();
        $collection
            ->addFieldToFilter('status', ['eq' => 'Enabled'])
            ->addFieldToFilter('post_id', ['neq' => $currentPost->getId()])
            ->addFieldToFilter(
                ['category', 'category'],
                [
                    ['in' => $categoryIds],
                    ['like' => '%' . implode(',', $categoryIds) . '%']
                ]
            )
            ->addFieldToFilter(
                'created_at',
                ['lt' => $currentPost->getCreatedAt()]
            )
            ->setOrder('created_at', 'DESC')
            ->setPageSize(1);

        return $collection->getFirstItem()->getId() ? $collection->getFirstItem() : null;
    }

    /**
     * Get next post.
     *
     * @return \Sprinix\Blogs\Model\Post|null
     */
    public function getNextPost()
    {
        $currentPost = $this->getCurrentPost();
        if (!$currentPost || !$currentPost->getId()) {
            return null;
        }
        $categoryIds = $this->categoryStatusChecker->getEnabledCategoryIds();
        if (empty($categoryIds)) {
            return null;
        }

        $collection = $this->postCollectionFactory->create();

        $collection
            ->addFieldToFilter('status', ['eq' => 'Enabled'])
            ->addFieldToFilter('post_id', ['neq' => $currentPost->getId()])
            ->addFieldToFilter(
                ['category', 'category'],
                [
                    ['in' => $categoryIds],
                    ['like' => '%' . implode(',', $categoryIds) . '%']
                ]
            )
            ->addFieldToFilter(
                'created_at',
                ['gt' => $currentPost->getCreatedAt()]
            )
            ->setOrder('created_at', 'ASC')
            ->setPageSize(1);

        return $collection->getFirstItem()->getId()
            ? $collection->getFirstItem()
            : null;
    }
}
