<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Sprinix\Blogs\Model\ResourceModel\Post\CollectionFactory;

/**
 * Class DataProvider
 * @package Sprinix\Blogs\Model
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

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * DataProvider constructor.
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
        try {
            if (empty($this->_loadedData)) {
                $posts = $this->collection->getItems();
                foreach ($posts as $post) {
                    $data = $post->getData();
                    if (isset($data['post_image'])) {
                        $mediaUrl = $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
                        $imageName = $data['post_image'];
                        $data['post_image'] = [
                            [
                                'name' => $imageName,
                                'url' => $mediaUrl . 'Sprinix/Blogs/' . $imageName
                            ]
                        ];
                    }
                    if(!empty($data['tags'])) {
                        $data['tags'] = $data['tags'] ? explode(',', $data['tags']): [];
                    }
                    if(!empty($data['category'])) {
                        $data['category'] = $data['category'] ? explode(',', $data['category']): [];
                    }
                    if($data['author_type'] === '1') {
                        $data['custom_author'] = $data['author'];
                    }
                    $this->_loadedData[$post->getPostId()] = $data;
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
        return $this->_loadedData;
    }
}
