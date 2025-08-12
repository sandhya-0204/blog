<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Block\Adminhtml\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Back
 * @package Sprinix\Blogs\Block\Adminhtml\Button
 */
class Back implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * Back constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ){
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        $refererUrl = $this->context->getRequest()->getServer('HTTP_REFERER');
        if(!$refererUrl) {
            $refererUrl =  $this->context->getUrlBuilder()->getUrl("*/*/");
        }
        return $refererUrl;
    }
}