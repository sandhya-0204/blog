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
 * Class CategoryAction
 * @package Sprinix\Blogs\Ui\Component\Listing\Column
 */
class CategoryAction extends Column
{
    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    const ROW_EDIT_URL = 'blogs/categories/form';
    const ROW_DELETE_URL = 'blogs/categories/delete';

    /**
     * Resource Initialization
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
    ){
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_urlBuilder = $urlBuilder;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        try {
            if(isset($dataSource['data']['items'])) {
                foreach ($dataSource['data']['items'] as &$item) {
                    $name= $this->getData('name');
                    if(isset($item['category_id'])) {
                        $item[$name]['edit'] = [
                            'href' => $this->_urlBuilder->getUrl(
                                self::ROW_EDIT_URL,
                                ['category_id' => $item['category_id']]
                            ),
                            'label' => __('Edit')
                        ];
                        $item[$name]['delete'] = [
                            'href' => $this->_urlBuilder->getUrl(
                                self::ROW_DELETE_URL,
                                ['category_id' => $item['category_id']]
                            ),
                            'label' => __('Delete')
                        ];
                    }
                }
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
        return $dataSource;
    }
}