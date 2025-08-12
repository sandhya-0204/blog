<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Sprinix\Blogs\Helper
 */
class Data extends AbstractHelper
{
    const XML_PATH_CONFIG = 'sprinix_blogs/';

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null) {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $code
     * @param null $storeId
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId=null) {
        return $this->getConfigValue(self::XML_PATH_CONFIG . 'general/' .$code, $storeId);
    }

    /**
     * @param $code
     * @param null $storeId
     * @return mixed
     */
    public function getLayoutConfig($code, $storeId=null) {
        return $this->getConfigValue(self::XML_PATH_CONFIG . 'layout/' .$code, $storeId);
    }

    public function getDateFormat($code, $storeId=null) {
        return $this->getConfigValue(self::XML_PATH_CONFIG . 'date_format/' .$code, $storeId);
    }
}