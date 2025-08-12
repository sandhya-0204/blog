<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Posts;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory;

/**
 * Class MassDelete
 * @package Sprinix\Blogs\Controller\Adminhtml\Posts
 */
class MassDelete extends Action
{
    /**
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var CustomUrl
     */
    protected $_customRewriteUrl;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param CustomUrl $customRewriteUrl
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        CustomUrl $customRewriteUrl
    ){
        parent::__construct($context);
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->_customRewriteUrl = $customRewriteUrl;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        try {
            $collection = $this->_filter->getCollection(
                $this->_collectionFactory->create()
            );
            $data = $collection->getItems();
            $recordDeleted = 0;
            foreach ($data as $record) {
                $urlKey = $record->getUrlKey();
                $record->setId($record->getPostId());
                $record->delete();
                if(!empty($urlKey)) {
                    $this->_customRewriteUrl->deletePostRewriteUrl($urlKey);
                }
                $recordDeleted++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $recordDeleted));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Delete : ' . $e->getMessage()));
        }
    }
}