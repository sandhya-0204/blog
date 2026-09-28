<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Posts;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Sprinix\Blogs\Model\PostFactory;

/**
 * Class Delete
 * @package Sprinix\Blogs\Controller\Adminhtml\Posts
 */
class Delete extends Action
{
    /**
     * @var PostFactory
     */
    protected $_postFactory;

    /**
     * @var CustomUrl
     */
    protected $_customRewriteUrl;

    /**
     * Delete constructor.
     * @param Context $context
     * @param CustomUrl $customRewriteUrl
     * @param PostFactory $postFactory
     */
    public function __construct(
        Context $context,
        CustomUrl $customRewriteUrl,
        PostFactory $postFactory
    ){
        parent::__construct($context);
        $this->_postFactory = $postFactory;
        $this->_customRewriteUrl = $customRewriteUrl;
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('post_id');
            $post = $this->_postFactory->create()->load($id);
            $urlKey = $post['url_key'];
            $post->delete();
            if(!empty($urlKey)) {
                $this->_customRewriteUrl->deletePostRewriteUrl($urlKey);
            }
            $this->messageManager->addSuccessMessage(__('Deleted Successfully'));
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Delete : ' . $e->getMessage()));
        }
        return $this->_redirect('blogs/posts/index');
    }
}