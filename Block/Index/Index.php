<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Block\Index;

use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Sprinix\Blogs\Helper\CategoryStatusChecker;
use Sprinix\Blogs\Helper\TagStatus;
use Sprinix\Blogs\Model\ResourceModel\Categories\CollectionFactory as CategoryCollectionFactory;
use Sprinix\Blogs\Model\ResourceModel\Post\Collection;
use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory;
use Sprinix\Blogs\Model\ResourceModel\Tags\CollectionFactory as TagsCollectionFactory;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class Index
 * @package Sprinix\Blogs\Block\Index
 */
class Index extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Custom Variable
     */
    protected $collection;

    /**
     * @var TagsCollectionFactory
     */
    protected $tagsCollectionFactory;

    /**
     * @var CategoryStatusChecker
     */
    protected $categoryStatusChecker;

    /**
     * @var TagStatus
     */
    protected $tagStatusChecker;
    /**
     * @var FilterProvider
     */
    protected $filterProvider;
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * Index constructor.
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param CategoryStatusChecker $categoryStatusChecker
     * @param CollectionFactory $postCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param TagsCollectionFactory $tagsCollectionFactory
     * @param TagStatus $tagStatus
     * @param Template\Context $context
     * @param FilterProvider $filterProvider
     * @param ManagerInterface $messageManager
     * @param array $data
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        CategoryStatusChecker $categoryStatusChecker,
        CollectionFactory $postCollectionFactory,
        StoreManagerInterface $storeManager,
        TagsCollectionFactory $tagsCollectionFactory,
        TagStatus $tagStatus,
        Template\Context $context,
        FilterProvider $filterProvider,
        ManagerInterface $messageManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryStatusChecker = $categoryStatusChecker;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->tagsCollectionFactory = $tagsCollectionFactory;
        $this->tagStatusChecker = $tagStatus;
        $this->filterProvider = $filterProvider;
        $this->messageManager = $messageManager;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        try {
            return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        }catch (\Exception $e) {}
    }

    /**
     * @return Collection|null
     */
    public function getAllActivePosts()
    {
        try {
            $posts = null;
            $categoryIds = $this->categoryStatusChecker->getEnabledCategoryIds();
            $tagsIds = $this->tagStatusChecker->getEnabledTagsIds();
            $postCollection = $this->postCollectionFactory->create();
            if(!empty($categoryIds)) {
                $posts = $postCollection
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('status', ['eq' => 'Enabled'])
                    ->addFieldToFilter(
                        ['category', 'category'],
                        [
                            ['in' => $categoryIds],
                            ['like' => "%" . implode(',', $categoryIds) . "%"]
                        ]
                    )
                    ->addFieldToFilter(['tags', 'tags', 'tags', 'tags'], [
                        ['in' => $tagsIds],
                        ['like' => "%" . implode(',', $tagsIds) . "%"],
                        ['null' => true],
                        ['eq' => ""],
                    ]);
            }

            return $posts;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }

    /**
     * @return DataObject[]
     */
    public function getAllCategories()
    {
        try {
            $categoryCollection = $this->categoryCollectionFactory->create();
            $categories = $categoryCollection->addFieldToFilter('category_status', ['eq' => 'Enabled'])->getItems();
            return $categories;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }

    /**
     * @param $id
     * @return Collection|null
     */
    public function filterPostByCategoryId($id)
    {
        try {
            $posts = null;
            $category = $this->categoryStatusChecker->getEnabledCategoryById($id);
            if (!empty($category)) {
                $postCollection = $this->postCollectionFactory->create();
                $posts = $postCollection
                    ->addFieldToFilter('category', ['like' => "%{$id}%"])
                    ->addFieldToFilter('status', ['eq' => 'Enabled']);
            }

            return $posts;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }

    /**
     * @return DataObject[]
     */
    public function getAllActiveTags()
    {
        try {
            $tagsCollection = $this->tagsCollectionFactory->create();
            $tags = $tagsCollection->addFieldToFilter('tag_status', ['eq' => 'Enabled'])->getItems();
            return $tags;
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }

    /**
     * @param $tagId
     * @return Collection|null
     */
    public function filterByTags($tagId)
    {
        $posts = null;
        try {
            $categoryIds = $this->categoryStatusChecker->getEnabledCategoryIds();
            $tag = $this->tagStatusChecker->getEnabledTagById($tagId);
            if(!empty($tag) && !empty($categoryIds)) {
                $postsCollection = $this->postCollectionFactory->create();
                $posts = $postsCollection
                    ->addFieldToFilter(
                        ['category', 'category'],
                        [
                            ['in' => $categoryIds],
                            ['like' => "%" . implode(',', $categoryIds) . "%"]
                        ]
                    )
                    ->addFieldToFilter('tags', ['like' => "%{$tagId}%"])
                    ->addFieldToFilter('status', ['eq' => 'Enabled']);
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $posts;
    }

    /**
     * @param $tag
     * @return array
     */
    public function getTagId($tag)
    {
        $ids = [];
        try {
            $tagsCollection = $this->tagsCollectionFactory->create();
            $tags = $tagsCollection
                ->addFieldToSelect('tag_id')
                ->addFieldToFilter('tag_status', ['eq' => 'Enabled'])
                ->addFieldToFilter('tag_name', ['like' => "%{$tag}%"])
                ->getItems();
            foreach ($tags as $tag) {
                array_push($ids, (int)$tag->getTagId());
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $ids;
    }

    /**
     * @param $query
     * @return Collection|null
     */
    public function searchPosts($query)
    {
        try {
            $postCollection = $this->postCollectionFactory->create();
            $categoryIds = $this->categoryStatusChecker->getEnabledCategoryIds();
            $tag = $this->getTagId($query);
            $posts = null;
            if(!empty($tag) && !empty($categoryIds)) {
                $posts = $postCollection
                    ->addFieldToFilter(
                        ['title', 'tags', 'tags', 'author'],
                        [
                            ["like" => "%{$query}%"],
                            ["in" => $tag],
                            ["like" => "%" . implode(',', $tag) . "%"],
                            ["like" => "%{$query}%"],
                        ])
                    ->addFieldToFilter('status', ['eq' => 'Enabled'])
                    ->addFieldToFilter(
                        ['category', 'category'],
                        [
                            ['in' => $categoryIds],
                            ['like' => "%" . implode(',', $categoryIds) . "%"]
                        ]
                    );
            }else if(!empty($categoryIds)) {
                $posts = $postCollection
                    ->addFieldToFilter(
                        ['title', 'author'],
                        [
                            ["like" => "%{$query}%"],
                            ["like" => "%{$query}%"],
                        ])
                    ->addFieldToFilter('status', ['eq' => 'Enabled'])
                    ->addFieldToFilter(
                        ['category', 'category'],
                        [
                            ['in' => $categoryIds],
                            ['like' => "%" . implode(',', $categoryIds) . "%"]
                        ]
                    );
            }
            return $posts;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }

    /**
     * @return Collection|null
     */
    public function getRecentPost()
    {
        $posts = null;
        try {
            $categoryIds = $this->categoryStatusChecker->getEnabledCategoryIds();
            $postCollection = $this->postCollectionFactory->create();
            if(!empty($categoryIds)) {
            $posts = $postCollection
                ->addFieldToFilter('status', ['eq' => 'Enabled'])
                ->addFieldToFilter(
                    ['category', 'category'],
                    [
                        ['in' => $categoryIds],
                        ['like' => "%" . implode(',', $categoryIds) . "%"]
                    ]
                )
                ->setOrder('main_table.created_at', 'DESC')
                ->setPageSize(5)
                ->setCurPage(1);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $posts;
    }

    /**
     * @return $this|Template
     */
    public function _prepareLayout()
    {
        try {
            parent::_prepareLayout();
            $this->_addBreadCrumbs();
            if ($this->getPostCollection()) {
                $pager = $this->getLayout()->createBlock(
                    'Magento\Theme\Block\Html\Pager',
                    'blog.history.pager'
                )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15])->setShowPerPage(true)->setCollection($this->getPostCollection());
            }
            $this->setChild('pager', $pager);
            $this->getPostCollection()->load();
        } catch (\Exception $e) {}
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return Collection|null
     */
    public function getPostCollection()
    {
        try {
            $category = $this->getRequest()->getParam('category');
            $sort = $this->getRequest()->getParam('sort');
            $query = $this->getRequest()->getParam('s');
            $tags = $this->getRequest()->getParam('tags');
            $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
            $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
            if (!empty($category)) {
                $this->collection = $this->filterPostByCategoryId($category);
            } else if (!empty($query)) {
                $this->collection = $this->searchPosts($query);
            } else if (!empty($tags)) {
                $this->collection = $this->filterByTags($tags);
            } else {
                $this->collection = $this->getAllActivePosts();
            }
            if (!empty($this->collection)) {
                if ($sort === 'asc') {
                    $this->collection->setOrder('main_table.created_at', 'ASC');
                } else {
                    $this->collection->setOrder('main_table.created_at', 'DESC');
                }
                $this->collection->setPageSize($pageSize);
                $this->collection->setCurPage($page);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $this->collection;
    }

    /**
     * Add BreadCrumbs
     */
    public function _addBreadCrumbs()
    {
        try {
            $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
            $baseUrl = $this->_storeManager->getStore()->getBaseUrl();

            if ($breadcrumbsBlock) {
                $breadcrumbsBlock->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'title' => __('Home'),
                        'link' => $baseUrl
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    'Blog',
                    [
                        'label' => __('Blogs'),
                        'title' => __('Blogs'),
                        'link' => $baseUrl . 'blog'
                    ]
                );
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }

     /**
     * Parse Magento content (widgets, page builder, CMS directives)
     *
     * @param string|null $content
     * @return string
     */
    public function parseContent($content)
    {
        try {
            if (empty($content) || !is_string($content)) {
                return '';
            }
            $parsedContent = $this->filterProvider
                ->getPageFilter()
                ->filter($content);
            return $parsedContent ?: '';
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Content parsing failed: ' . $e->getMessage()));
            return trim(
                preg_replace('/\s+/u', ' ', strip_tags($content))
            );
        }
    }
}
