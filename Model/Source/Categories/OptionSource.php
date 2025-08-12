<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\Source\Categories;

use Magento\Framework\Data\OptionSourceInterface;
use Sprinix\Blogs\Block\Index\Index;

/**
 * Class OptionSource
 * @package Sprinix\Blogs\Model\Source\Categories
 */
class OptionSource implements OptionSourceInterface
{
    /**
     * @var Index
     */
    protected $categories;

    /**
     * OptionSource constructor.
     * @param Index $categories
     */
    public function __construct(
        Index $categories
    ) {
        $this->categories = $categories;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        try {
            $categories = $this->categories->getAllCategories();
            foreach ($categories as $category) {
                array_push($options, ['label' => $category['category'], 'value' => $category['category_id']]);
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
        return $options;
    }
}