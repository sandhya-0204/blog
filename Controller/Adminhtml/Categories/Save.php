<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;
use Sprinix\Blogs\Controller\Adminhtml\Posts\CustomUrl;
use Sprinix\Blogs\Model\CategoriesFactory;
use Sprinix\Blogs\Model\ResourceModel\Categories\CollectionFactory as CategoriesCollectionFactory;

/**
 * Class Save
 * @package Sprinix\Blogs\Controller\Adminhtml\Categories
 */
class Save extends Action
{
    /**
     * @var CategoriesFactory
     */
    protected $categoriesFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var CategoriesCollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * @var CustomUrl
     */
    protected $urlRewrite;

    /**
     * Save constructor.
     * @param Context $context
     * @param CategoriesCollectionFactory $categoryCollectionFactory
     * @param CategoriesFactory $categoriesFactory
     * @param CustomUrl $customUrl
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Context $context,
        CategoriesCollectionFactory $categoryCollectionFactory,
        CategoriesFactory $categoriesFactory,
        CustomUrl $customUrl,
        ManagerInterface $messageManager
    )
    {
        parent::__construct($context);
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoriesFactory = $categoriesFactory;
        $this->messageManager = $messageManager;
        $this->urlRewrite = $customUrl;
    }

    /**
     * Save Category
     */
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('category_id');
            $data = (array)$this->getRequest()->getPost();
            $model = $this->categoriesFactory->create();
            $categoryCollection = $this->_categoryCollectionFactory->create();
            $data['category_url_key'] = isset($data['category_url_key'])
                ? str_replace(' ', '-', $data['category_url_key'])
                : null;
            $size = 0;
            if (isset($data['category_url_key'])) {
                $size = $categoryCollection
                    ->addFieldToSelect('category_url_key')
                    ->addFieldToFilter('category_url_key', ['eq' => $data['category_url_key']])
                    ->getSize();
            }
            if (!empty($id) && !empty($data)) {
                $category = $model->load($id);
                $currCategoryUrlKey = $category->getCategoryUrlKey();
                if ($size > 0 && $currCategoryUrlKey !== $data['category_url_key']) {
                    $data['category_url_key'] = $this->generateNewRewriteUrl($data['category_url_key'], $size);
                }
                $category->setData($data)->save();
                if ($category->getCategoryUrlKey() !== $currCategoryUrlKey) {
                    $this->urlRewrite->updateCategoryUrlRewrite($category->getCategoryId(), $currCategoryUrlKey, $category->getCategoryUrlKey());
                }
            } elseif (!empty($data)) {
                unset($data['category_id']);

                if ($size > 0) {
                    $data['category_url_key'] = $this->generateNewRewriteUrl($data['category_url_key'], $size);
                }
                $model->setData($data);
                $newData = $model->save();
                $this->urlRewrite->createCategoryUrlRewrite($newData->getCategoryId(), $newData->getCategoryUrlKey());
            } else {
                $this->messageManager->addErrorMessage(__('Data was not saved. Please fill in all required fields.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        $this->_redirect('blogs/categories/index');
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