<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Posts;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Message\ManagerInterface;
use Sprinix\Blogs\Model\PostFactory;
use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

/**
 * Class Save
 * @package Sprinix\Blogs\Controller\Adminhtml\Posts
 */
class Save extends Action
{
    /**
     * @var PostFactory
     */
    protected $_postFactory;

    /**
     * @var PostCollectionFactory
     */
    protected $_postCollectionFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var CustomUrl
     */
    protected $urlRewrite;

    /**
     * Save constructor.
     * @param Context $context
     * @param CustomUrl $customUrl
     * @param ManagerInterface $messageManager
     * @param PostCollectionFactory $postCollectionFactory
     * @param PostFactory $postFactory
     */
    public function __construct(
        Context $context,
        CustomUrl $customUrl,
        ManagerInterface $messageManager,
        PostCollectionFactory $postCollectionFactory,
        PostFactory $postFactory
    ){
        $this->_postCollectionFactory = $postCollectionFactory;
        $this->_postFactory = $postFactory;
        $this->messageManager = $messageManager;
        $this->urlRewrite = $customUrl;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        try {
            $model = $this->_postFactory->create();
            $id = $this->getRequest()->getParam('post_id');
            $data = (array)$this->getRequest()->getPost();
            $data['author'] = isset($data['author_type']) && (int)$data['author_type'] === 0 ? $data['author'] : $data['custom_author'];
            $data['tags'] = !empty($data['tags']) ? implode(',', $data['tags']): null;
            $data['category'] = !empty($data['category']) ? implode(',', $data['category']): null;
            $data['url_key'] = isset($data['url_key'])
                ? str_replace(' ', '-', $data['url_key'])
                : null;
            $imageData = $this->getRequest()->getPostValue('post_image');
            if (isset($imageData[0]['name']) && !empty($imageData[0]['name'])) {
                $data['post_image'] = $imageData[0]['name'];
            }
            $size = 0;
            if (isset($data['url_key'])) {
                $postCollection = $this->_postCollectionFactory->create();
                $size = $postCollection
                    ->addFieldToSelect('url_key')
                    ->addFieldToFilter('url_key', ['eq' => $data['url_key']])
                    ->getSize();
            }
            if ($id) {
                $postModel = $model->load($id);
                $currUrlKey = $postModel->getUrlKey();
                if ($size > 0 && $currUrlKey !== $data['url_key']) {
                    $data['url_key'] = $this->generateNewRewriteUrl($data['url_key'], $size);
                }
                $postModel->setData($data)->save();
                if ($postModel->getUrlKey() !== $currUrlKey) {
                    $this->urlRewrite->updatePostUrlRewrite($postModel->getPostId(), $currUrlKey, $postModel->getUrlKey());
                }
            } else {
                unset($data['post_id']);
                $model->setData($data);
                $newData = $model->save();
                $this->urlRewrite->createPostUrlRewrite($newData->getPostId(), $newData->getUrlKey());
            }
            $this->messageManager->addSuccessMessage(__("Data Saved Successfully."));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $this->_redirect('blogs/posts/index');
    }

    /**
     * @param $urlKey
     * @param $size
     * @return string
     */
    public function generateNewRewriteUrl($urlKey, $size)
    {
        return $urlKey . '-' . $size;
    }
}
