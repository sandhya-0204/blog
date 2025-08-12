<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Thumbnail
 * @package Sprinix\Blogs\Ui\Component\Listing\Column
 */
class Thumbnail extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Thumbnail constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        try {
            if (isset($dataSource['data']['items'])) {
                $fieldName = $this->getData('name');
                foreach ($dataSource['data']['items'] as &$data) {

                    if ($data['post_image']) {
                        $imageUrl = $this->urlBuilder->getBaseUrl() . 'pub/media/Sprinix/Blogs/' . $data['post_image'];
                        $data[$fieldName . '_src'] = $imageUrl;
                        $data[$fieldName . '_alt'] = $data['post_image'];

                        $data[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                            'blogs/post/add',
                            ['post_id' => $data['post_id']]);
                        $data[$fieldName . '_orig_src'] = $imageUrl;
                    }
                }
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Thumbnail Error: ' . $e->getMessage()));
        }
        return $dataSource;
    }
}