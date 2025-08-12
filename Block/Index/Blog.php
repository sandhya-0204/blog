<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Block\Index;

use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Sprinix\Blogs\Helper\CategoryStatusChecker;
use Sprinix\Blogs\Model\ResourceModel\Comment\CollectionFactory as CommentCollectionFactory;
use Sprinix\Blogs\Model\ResourceModel\CommentReply\CollectionFactory as CommentReplyCollectionFactory;
use Sprinix\Blogs\Model\ResourceModel\Post\Collection;
use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory;

/**
 * Class Blog
 * @package Sprinix\Blogs\Block\Index
 */
class Blog extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var CommentCollectionFactory
     */
    protected $commentCollectionFactory;

    /**
     * @var CommentReplyCollectionFactory
     */
    protected $commentReplyCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var CategoryStatusChecker
     */
    protected $categoryStatusChecker;

    /**
     * Blog constructor.
     * @param CategoryStatusChecker $categoryStatusChecker
     * @param CollectionFactory $postCollectionFactory
     * @param CommentCollectionFactory $commentCollectionFactory
     * @param CommentReplyCollectionFactory $commentReplyCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        CategoryStatusChecker $categoryStatusChecker,
        CollectionFactory $postCollectionFactory,
        CommentCollectionFactory $commentCollectionFactory,
        CommentReplyCollectionFactory $commentReplyCollectionFactory,
        StoreManagerInterface $storeManager,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryStatusChecker = $categoryStatusChecker;
        $this->commentCollectionFactory = $commentCollectionFactory;
        $this->commentReplyCollectionFactory = $commentReplyCollectionFactory;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return mixed
     */
    public function getMedia() {
        try {
            return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Get Media Url : ' . $e->getMessage()));
        }
    }


    /**
     * @return string
     */
    public function getCommentFormAction() {
        return $this->getUrl('blogs/index/comment', ['_secure' => true]);
    }

    /**
     * @return string
     */
    public function getReplyFormAction() {
        return $this->getUrl('blogs/index/reply',  ['_secure' => true]);
    }

    /**
     * @return DataObject|null
     */
    public function getPost()
    {
        $post = null;
        try {
            $categoryIds = $this->categoryStatusChecker->getEnabledCategoryIds();
            $currentUrl  = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
            $url_key = explode('/blog/', $currentUrl);

            if(isset($url_key[1]) && !empty($categoryIds)) {
                $postCollection = $this->postCollectionFactory->create();
                $post = $postCollection
                    ->addFieldToFilter('status', ['eq' => 'Enabled'])
                    ->addFieldToFilter(
                        ['category', 'category'],
                        [
                            ['in' => $categoryIds],
                            ['like' => "%" . implode(',', $categoryIds) . "%"]
                        ]
                    )
                    ->getItemByColumnValue('url_key', $url_key[1]);
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Get Post : ' . $e->getMessage()));
        }
        return $post;
    }


    /**
     * @return Collection|null
     */
    public function getRecentPost() {
        $posts = null;
        try {
            $categoryIds = $this->categoryStatusChecker->getEnabledCategoryIds();
            if(!empty($categoryIds)) {
                $postCollection = $this->postCollectionFactory->create();
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
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Get Recent Post : ' . $e->getMessage()));
        }
        return $posts;
    }

    /**
     * @param $postId
     * @return array|null
     */
    public function getCommentbyPostId($postId) {
        $comment = null;
        try {
            $commentCollection = $this->commentCollectionFactory->create();
            $comment = $commentCollection->getItemsByColumnValue('commented_on', $postId);
        }catch(\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Get Comment : ' . $e->getMessage()));
        }
        return $comment;
    }


    /**
     * @param $postId
     * @return array|null
     */
    public function getAllCommentsReply($postId) {
        $messages = null;
        try {
            $commentReplyCollection = $this->commentReplyCollectionFactory->create();
            $messages = $commentReplyCollection->getItemsByColumnValue('post_id', $postId);
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Get Replies : ' . $e->getMessage()));
        }
        return $messages;
    }

    /**
     * @return $this|Template
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->_addBreadCrumbs();
        return $this;
    }

    /**
     * Add BreadCrumbs
     */
    public function _addBreadCrumbs() {
        try {
            $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
            $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
            $post = $this->getPost();
            $title = isset($post) ? $post->getTitle() : null;
            $urlKey = isset($post) ? $post->getUrlKey() : null;
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
                    'blog',
                    [
                        'label' => __('Blogs'),
                        'title' => __('Blogs'),
                        'link' => $baseUrl .'blog'
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    $title,
                    [
                        'label' => __($title),
                        'title' => __($title),
                        'link' => $baseUrl .'blog/'.$urlKey
                    ]
                );
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }
}
