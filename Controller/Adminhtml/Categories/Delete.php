<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Sprinix\Blogs\Controller\Adminhtml\Posts\CustomUrl;
use Sprinix\Blogs\Helper\DeletePostCategoryId;
use Sprinix\Blogs\Model\CategoriesFactory;

/**
 * Class Delete
 * @package Sprinix\Blogs\Controller\Adminhtml\Categories
 */
class Delete extends Action
{
    /**
     * @var CategoriesFactory
     */
    protected $_categoriesFactory;

    /**
     * @var CustomUrl
     */
    protected $_customRewriteUrl;

    /**
     * @var DeletePostCategoryId
     */
    protected $helper;

    /**
     * Delete constructor.
     * @param Context $context
     * @param CategoriesFactory $categoriesFactory
     * @param CustomUrl $customRewriteUrl
     * @param DeletePostCategoryId $helper
     */
    public function __construct(
        Context $context,
        CategoriesFactory $categoriesFactory,
        CustomUrl $customRewriteUrl,
        DeletePostCategoryId $helper
    ){
        parent::__construct($context);
        $this->_categoriesFactory = $categoriesFactory;
        $this->_customRewriteUrl = $customRewriteUrl;
        $this->helper = $helper;
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('category_id');
            $category = $this->_categoriesFactory->create()->load($id);
            $urlKey = $category['category_url_key'];
            $category->delete();
            if(!empty($urlKey)) {
                $this->_customRewriteUrl->deleteCategoryRewriteUrl($urlKey);
            }
            $this->helper->deleteCategoryId($id);
            $this->messageManager->addSuccessMessage(__('Deleted Successfully'));
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $this->_redirect('blogs/categories/index');
    }
}