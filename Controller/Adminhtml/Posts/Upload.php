<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Posts;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Sprinix\Blogs\Model\ImageUploader;

/**
 * Class Upload
 * @package Sprinix\Blogs\Controller\Adminhtml\Posts
 */
class Upload extends Action
{
    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * Upload constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        ImageUploader $imageUploader
    ){
        $this->imageUploader = $imageUploader;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    /**
     * @return Json
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        try {
            $result = $this->imageUploader->saveFileToTmpDir('post_image');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $resultJson->setData($result);
    }
}