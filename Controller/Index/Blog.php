<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory;
use Sprinix\Blogs\Model\ResourceModel\Post as PostResource;
use Sprinix\Blogs\Helper\Data;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class Blog
 * @package Sprinix\Blogs\Controller\Index
 */
class Blog extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var Data
     */
    protected $_helperData;
    /**
     * @var CollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var PostResource
     */
    protected $postResource;
    /**
     * @var SessionManagerInterface
     */
    protected SessionManagerInterface $session;

    /**
     * Blog constructor.
     * @param Context $context
     * @param Data $helperData
     * @param PageFactory $pageFactory
     * @param CollectionFactory $postCollectionFactory
     * @param PostResource $postResource
     * @param SessionManagerInterface $session
     */
    public function __construct(
        Context $context,
        Data $helperData,
        PageFactory $pageFactory,
        CollectionFactory $postCollectionFactory,
        PostResource $postResource,
        SessionManagerInterface $session
    ){
        parent::__construct($context);
        $this->_helperData = $helperData;
        $this->pageFactory = $pageFactory;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->postResource = $postResource;
        $this->session = $session;
    }

    /**
     * @return Page
     */
    public function execute()
    {
        try {
            if (!$this->_helperData->getGeneralConfig('enable')) {
                return $this->_redirect('/');
            }
            $this->incrementViewCount();
            return $this->pageFactory->create();
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
    }

    private function incrementViewCount(): void
    {
        try {
            $currentUrl = $this->_url->getCurrentUrl();
            $urlKey = explode('/blog/', $currentUrl);

            if (!isset($urlKey[1])) {
                return;
            }

            $post = $this->postCollectionFactory
                ->create()
                ->getItemByColumnValue('url_key', $urlKey[1]);

            if (!$post || !$post->getId()) {
                return;
            }

            $viewedPosts = $this->session->getViewedPosts() ?? [];

            if (!in_array($post->getId(), $viewedPosts)) {
                $post->setViewCount((int)$post->getViewCount() + 1);
                $this->postResource->save($post);

                $viewedPosts[] = $post->getId();
                $this->session->setViewedPosts($viewedPosts);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: %1', $e->getMessage()));
        }
    }
}
