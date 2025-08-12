<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Tags;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Sprinix\Blogs\Controller\Adminhtml\Posts\CustomUrl;
use Sprinix\Blogs\Helper\DeletePostTagId;
use Sprinix\Blogs\Model\TagsFactory;
use Sprinix\Blogs\Model\ResourceModel\Tags\CollectionFactory;

/**
 * Class MassDelete
 * @package Sprinix\Blogs\Controller\Adminhtml\Tags
 */
class MassDelete extends Action
{
    /**
     * @var Filter
     */
    protected $_filter;

    /**
     * @var TagsFactory
     */
    protected $_tagsModelFactory;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var CustomUrl
     */
    protected $_customRewriteUrl;

    /**
     * @var DeletePostTagId
     */
    protected $helper;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param TagsFactory $tagsFactory
     * @param CollectionFactory $collectionFactory
     * @param CustomUrl $customRewriteUrl
     * @param DeletePostTagId $helper
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        TagsFactory $tagsFactory,
        CollectionFactory $collectionFactory,
        CustomUrl $customRewriteUrl,
        DeletePostTagId $helper,
        Filter $filter
    ){
        parent::__construct($context);
        $this->_tagsModelFactory = $tagsFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_customRewriteUrl = $customRewriteUrl;
        $this->_filter = $filter;
        $this->helper = $helper;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        try {
            $model = $this->_tagsModelFactory->create();
            $collection = $this->_filter->getCollection($this->_collectionFactory->create());
            $data = $collection->getItems();
            $recordDeleted = 0;
            foreach ($data as $item) {
                $tag = $model->load($item->getTagId());
                $urlKey = $tag['tag_url_key'];
                $tag->delete();
                if(!empty($urlKey)) {
                    $this->_customRewriteUrl->deleteTagRewriteUrl($urlKey);
                }
                $this->helper->deleteTagId($item->getTagId());
                $recordDeleted++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $recordDeleted));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Delete : ' . $e->getMessage()));
        }
    }
}