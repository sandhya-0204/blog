<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Plugin;

use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\UrlInterface;
use Sprinix\Blogs\Helper\Data;

/**
 * Class Topmenu
 * @package Sprinix\Blogs\Plugin
 */
class Topmenu
{
    /**
     * @var NodeFactory
     */
    protected $nodeFactory;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * Topmenu constructor.
     * @param Data $helperData
     * @param NodeFactory $nodeFactory
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Data $helperData,
        NodeFactory $nodeFactory,
        UrlInterface $urlBuilder

    ){
        $this->_helperData = $helperData;
        $this->nodeFactory = $nodeFactory;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return null
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ){
        try {
            $isModuleEnabled = $this->_helperData->getGeneralConfig('enable');
            if($isModuleEnabled) {
                $menuNode = $this->nodeFactory->create([
                    'data' => $this->getNodeAsArray('Blogs', 'sprinix_blog'),
                    'idField' => 'id',
                    'tree' => $subject->getMenu()->getTree()
                ]);
                $subject->getMenu()->addChild($menuNode);
            }else {
                return null;
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Top Menu Error: ' . $e->getMessage()));
        }
    }

    /**
     * @param $menu
     * @param $id
     * @return array
     */
    protected function getNodeAsArray($menu, $id)
    {
        $url = $this->urlBuilder->getUrl("blog");
        return [
            'name' => __($menu),
            'id' => $id,
            'url' => $url,
            'has_active' => false,
            'is_active' => false,
        ];
    }
}