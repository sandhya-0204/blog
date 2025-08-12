<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Sprinix\Blogs\Model\ResourceModel\Comment\CollectionFactory;

/**
 * Class CommentDataProvider
 * @package Sprinix\Blogs\Model
 */
class CommentDataProvider extends AbstractDataProvider
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
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * CommentDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        UrlInterface $urlBuilder,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return array
     */
    public function getData()
    {
        try {
            if (empty($this->_loadedData))
            {
                $comments = $this->collection->getItems();
                foreach ($comments as $comment) {
                    $data = $comment->getData();
                    $this->_loadedData[$comment->getCommentId()] = $data;
                }
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
        return $this->_loadedData;
    }
}