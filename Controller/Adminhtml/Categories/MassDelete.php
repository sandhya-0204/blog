<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Sprinix\Blogs\Controller\Adminhtml\Posts\CustomUrl;
use Sprinix\Blogs\Helper\DeletePostCategoryId;
use Sprinix\Blogs\Model\CategoriesFactory;
use Sprinix\Blogs\Model\ResourceModel\Categories\CollectionFactory;

/**
 * Class MassDelete
 * @package Sprinix\Blogs\Controller\Adminhtml\Categories
 */
class MassDelete extends Action
{
    /**
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CategoriesFactory
     */
    protected $_categoryModelFactory;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var CustomUrl
     */
    protected $_customRewriteUrl;

    /**
     * @var DeletePostCategoryId
     */
    protected $helper;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param CategoriesFactory $categoryFactory
     * @param CollectionFactory $collectionFactory
     * @param CustomUrl $customRewriteUrl
     * @param DeletePostCategoryId $helper
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        CategoriesFactory $categoryFactory,
        CollectionFactory $collectionFactory,
        CustomUrl $customRewriteUrl,
        DeletePostCategoryId $helper,
        Filter $filter
    ){
        parent::__construct($context);
        $this->_categoryModelFactory = $categoryFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_customRewriteUrl = $customRewriteUrl;
        $this->helper = $helper;
        $this->_filter = $filter;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        try {
            $model = $this->_categoryModelFactory->create();
            $collection = $this->_filter->getCollection($this->_collectionFactory->create());
            $data = $collection->getItems();
            $recordDeleted = 0;
            foreach ($data as $item) {
                $category = $model->load($item->getCategoryId());
                $urlKey = $category['category_url_key'];
                $category->delete();
                if(!empty($urlKey)) {
                    $this->_customRewriteUrl->deleteCategoryRewriteUrl($urlKey);
                }
                $this->helper->deleteCategoryId($item->getCategoryId());
                $recordDeleted++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $recordDeleted));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Delete : ' . $e->getMessage()));
        }
    }
}