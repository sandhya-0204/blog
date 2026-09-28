<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Sprinix\Blogs\Model\ResourceModel\CommentReply\CollectionFactory;

/**
 * Class CommentReplyDataProvider
 * @package Sprinix\Blogs\Model
 */
class CommentReplyDataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $_loadedData = [];

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * CommentReplyDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->collection = $collectionFactory->create();
    }

    /**
     * @return array
     */
    public function getData()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/tablerate1.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info("testing-----");
        try {
            if (empty($this->_loadedData))
            {
                $replies = $this->collection->getItems();
                foreach ($replies as $reply) {
                    $data = $reply->getData();
                    $this->_loadedData[$reply->getReplyId()] = $data;
                }
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
        $logger->info("testing-----".print_r($this->_loadedData,true));

        return $this->_loadedData;
    }
}
