<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Reply;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Sprinix\Blogs\Model\ResourceModel\CommentReply\CollectionFactory;

/**
 * Class MassStatus
 * @package Sprinix\Blogs\Controller\Adminhtml\Reply
 */
class MassStatus extends Action
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
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ){
        parent::__construct($context);
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        try {
            // pending / approved / not_approved
            $statusValue = $this->getRequest()->getParam('status');
            $collection = $this->_filter->getCollection(
                $this->_collectionFactory->create()
            );
            $recordUpdated = 0;
            foreach ($collection as $record) {
                $record->setReplyStatus($statusValue);
                $record->save();
                $recordUpdated++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated.', $recordUpdated));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Update Status: ' . $e->getMessage()));
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
