<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Tags;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Sprinix\Blogs\Model\ResourceModel\Tags\CollectionFactory;

/**
 * Class MassStatus
 * @package Sprinix\Blogs\Controller\Adminhtml\Tags
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
     * MassStatus constructor.
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->_collectionFactory = $collectionFactory;
        $this->_filter = $filter;
    }

    /**
     * Update tag status
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $statusValue = $this->getRequest()->getParam('status');

            $collection = $this->_filter->getCollection(
                $this->_collectionFactory->create()
            );

            $recordUpdated = 0;

            foreach ($collection as $record) {
                $record->setTagStatus($statusValue);
                $record->save();
                $recordUpdated++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated.', $recordUpdated));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Unable to update the tag status: %1', $e->getMessage()));
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
}
