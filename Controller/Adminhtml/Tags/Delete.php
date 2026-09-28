<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Tags;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Sprinix\Blogs\Controller\Adminhtml\Posts\CustomUrl;
use Sprinix\Blogs\Helper\DeletePostTagId;
use Sprinix\Blogs\Model\TagsFactory;
/**
 * Class Delete
 * @package Sprinix\Blogs\Controller\Adminhtml\Tags
 */
class Delete extends Action
{
    /**
     * @var CategoriesFactory
     */
    protected $_tagsFactory;

    /**
     * @var CustomUrl
     */
    protected $_customRewriteUrl;

    /**
     * @var DeletePostTagId
     */
    protected $helper;

    /**
     * Delete constructor.
     * @param Context $context
     * @param CustomUrl $customRewriteUrl
     * @param DeletePostTagId $helper
     * @param TagsFactory $tagsFactory
     */
    public function __construct(
        Context $context,
        CustomUrl $customRewriteUrl,
        DeletePostTagId $helper,
        TagsFactory $tagsFactory
    ){
        parent::__construct($context);
        $this->_customRewriteUrl = $customRewriteUrl;
        $this->helper = $helper;
        $this->_tagsFactory = $tagsFactory;
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('tag_id');
            $tag = $this->_tagsFactory->create()->load($id);
            $urlKey = $tag['tag_url_key'];
            $tag->delete();
            if(!empty($urlKey)) {
                $this->_customRewriteUrl->deleteTagRewriteUrl($urlKey);
            }
            $this->helper->deleteTagId($id);
            $this->messageManager->addSuccessMessage(__('Deleted Successfully'));
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Failed To Delete : ' . $e->getMessage()));
        }
        return $this->_redirect('blogs/tags/index');
    }
}