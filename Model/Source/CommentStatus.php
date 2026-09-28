<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */
namespace Sprinix\Blogs\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class CommentStatus
 * @package Sprinix\Blogs\Model\Source
 */
class CommentStatus implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'pending',
                'label' => __('Pending')
            ],
            [
                'value' => 'approved',
                'label' => __('Approved')
            ],
            [
                'value' => 'not_approved',
                'label' => __('Not Approved')
            ],
        ];
    }
}
