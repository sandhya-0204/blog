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
use Sprinix\Blogs\Model\ResourceModel\Comment\CollectionFactory as CommentCollectionFactory;
use Sprinix\Blogs\Model\ResourceModel\CommentReply\CollectionFactory as CommentReplyCollectionFactory;
use Sprinix\Blogs\Model\ResourceModel\Post\Collection;
use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Message\ManagerInterface;

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
     * @var FilterProvider
     */
    protected $filterProvider;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $messageManager;

    /**
     * Blog constructor.
     * @param FilterProvider $filterProvider
     * @param CategoryStatusChecker $categoryStatusChecker
     * @param CollectionFactory $postCollectionFactory
     * @param CommentCollectionFactory $commentCollectionFactory
     * @param CommentReplyCollectionFactory $commentReplyCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param Template\Context $context
     * @param ManagerInterface $messageManager
     * @param array $data
     */
    public function __construct(
        FilterProvider $filterProvider,
        CategoryStatusChecker $categoryStatusChecker,
        CollectionFactory $postCollectionFactory,
        CommentCollectionFactory $commentCollectionFactory,
        CommentReplyCollectionFactory $commentReplyCollectionFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        Template\Context $context,
        ManagerInterface $messageManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryStatusChecker = $categoryStatusChecker;
        $this->commentCollectionFactory = $commentCollectionFactory;
        $this->commentReplyCollectionFactory = $commentReplyCollectionFactory;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->filterProvider = $filterProvider;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
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
            $currentUrl  = $this->getUrl('*/*/*', ['_use_rewrite' => true]);
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
            $commentCollection->addFieldToFilter('commented_on', $postId);
            $commentCollection->addFieldToFilter('comment_status', 'approved');

            $comment = $commentCollection->getItems();
        }catch(\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Get Comment : ' . $e->getMessage()));
        }
        return $comment;
    }

    /**
     * @param $postId
     * @return array|null
     */
    public function getAllCommentsReply($postId)
    {
        $messages = null;

        try {
            $commentReplyCollection = $this->commentReplyCollectionFactory->create();

            $commentReplyCollection
                ->addFieldToFilter('main_table.post_id', $postId)
                ->addFieldToFilter('main_table.reply_status', 'approved')
                ->addFieldToFilter('comment.comment_status', 'approved');

            $messages = $commentReplyCollection->getItems();

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Failed To Get Replies: ' . $e->getMessage())
            );
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
        $post = $this->getPost();
        if ($post && $post->getId()) {
            $pageConfig = $this->pageConfig;
            if ($post->getTitle()) {
                $pageConfig->getTitle()->set($post->getTitle());
            }
            if ($post->getMetaTitle()) {
                $pageConfig->setMetaTitle($post->getMetaTitle());
            }
            if ($post->getMetaDesc()) {
                $pageConfig->setDescription($post->getMetaDesc());
            }
            if ($post->getMetaKeyword()) {
                $pageConfig->setKeywords($post->getMetaKeyword());
            }
        }
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
            $this->logger->error('Content parsing failed: ' . $e->getMessage());
            return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        }
    }
}
