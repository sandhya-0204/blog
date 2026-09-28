<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\Categories;

use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Sprinix\Blogs\Model\ResourceModel\Categories\CollectionFactory;

/**
 * Class DataProvider
 * @package Sprinix\Blogs\Model\Categories
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $_loadedData = [];

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    protected $collectionFactory;

    /**
     * DataProvider constructor.
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        UrlInterface $urlBuilder,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ){
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->urlBuilder = $urlBuilder;
        $this->collection = $collectionFactory->create();
    }

    /**
     * @return array
     */
    public function getData()
    {
        try {
            if(empty($this->_loadedData)) {
                $users = $this->collection->getItems();
                foreach ($users as $user) {
                    $data = $user->getData();
                    $this->_loadedData[$user->getCategoryId()] = $data;
                }
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
        return $this->_loadedData;
    }
}
