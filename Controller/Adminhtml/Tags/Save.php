<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Tags;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;
use Sprinix\Blogs\Controller\Adminhtml\Posts\CustomUrl;
use Sprinix\Blogs\Model\TagsFactory;
use Sprinix\Blogs\Model\ResourceModel\Tags\CollectionFactory as TagsCollectionFactory;

/**
 * Class Save
 * @package Sprinix\Blogs\Controller\Adminhtml\Tags
 */
class Save extends Action
{
    /**
     * @var TagsFactory
     */
    protected $tagsFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var TagsCollectionFactory
     */
    protected $_tagsCollectionFactory;

    /**
     * @var CustomUrl
     */
    protected $urlRewrite;

    /**
     * Save constructor.
     * @param Context $context
     * @param TagsCollectionFactory $tagsCollectionFactory
     * @param TagsFactory $tagsFactory
     * @param CustomUrl $customUrl
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Context $context,
        TagsCollectionFactory $tagsCollectionFactory,
        TagsFactory $tagsFactory,
        CustomUrl $customUrl,
        ManagerInterface $messageManager
    )
    {
        parent::__construct($context);
        $this->_tagsCollectionFactory = $tagsCollectionFactory;
        $this->tagsFactory = $tagsFactory;
        $this->messageManager = $messageManager;
        $this->urlRewrite = $customUrl;
    }

    /**
     * Save Blog Tag
     */
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('tag_id');
            $data = (array)$this->getRequest()->getPost();
            $model = $this->tagsFactory->create();
            $tagsCollection = $this->_tagsCollectionFactory->create();
            $data['tag_url_key'] = isset($data['tag_url_key'])
                ? str_replace(' ', '-', $data['tag_url_key'])
                : null;
            $size = 0;
            if (isset($data['tag_url_key'])) {
                $size = $tagsCollection
                    ->addFieldToSelect('tag_url_key')
                    ->addFieldToFilter('tag_url_key', ['eq' => $data['tag_url_key']])
                    ->getSize();
            }
            if (!empty($id) && !empty($data)) {
                $tag = $model->load($id);
                $currTagUrlKey = $tag->getTagUrlKey();
                if ($size > 0 && $currTagUrlKey !== $data['tag_url_key']) {
                    $data['tag_url_key'] = $this->generateNewRewriteUrl($data['tag_url_key'], $size);
                }
                $tag->setData($data)->save();
                if ($tag->getTagUrlKey() !== $currTagUrlKey) {
                    $this->urlRewrite->updateTagUrlRewrite($tag->getTagId(), $currTagUrlKey, $tag->getTagUrlKey());
                }
            } elseif (!empty($data)) {
                unset($data['tag_id']);

                if ($size > 0) {
                    $data['tag_url_key'] = $this->generateNewRewriteUrl($data['tag_url_key'], $size);
                }
                $model->setData($data);
                $newData = $model->save();
                $this->urlRewrite->createTagUrlRewrite($newData->getTagId(), $newData->getTagUrlKey());
            } else {
                $this->messageManager->addErrorMessage(__('Data was not saved. Please fill in all required fields.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        $this->_redirect('blogs/tags/index');
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