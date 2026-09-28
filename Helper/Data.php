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
    private const XML_PATH_SEO_PAGE_TITLE = 'sprinix_blogs/blog_list_seo/page_title';
    private const XML_PATH_SEO_META_TITLE = 'sprinix_blogs/blog_list_seo/meta_title';
    private const XML_PATH_SEO_META_DESCRIPTION = 'sprinix_blogs/blog_list_seo/meta_description';
    private const XML_PATH_SEO_META_KEYWORDS = 'sprinix_blogs/blog_list_seo/meta_keywords';

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

    /**
     * @param $code
     * @param null $storeId
     * @return mixed
     */
    public function getCommentSetting($code, $storeId=null) {
        return $this->getConfigValue(self::XML_PATH_CONFIG . 'comment_reply_settings/' .$code, $storeId);
    }

    /**
     * @param null $storeId
     * @return int
     */
    public function getPrevNextBtn($storeId=null) {
        return (int) $this->getConfigValue(self::XML_PATH_CONFIG . 'previous_next_post/display_btn', $storeId);
    }

    /**
     * @param null $storeId
     * @return int
     */
    public function getCommentCharacterLimit($storeId = null): int
    {
        return (int) $this->getCommentSetting('comment_character_limit', $storeId);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isCommentsEnabled($storeId = null): bool
    {
        return (bool)$this->getCommentSetting('comments', $storeId);
    }
    /**
     * @param null $storeId
     * @return bool
     */
    public function isViewCountEnabled($storeId = null): bool
    {
        return (bool)$this->getLayoutConfig('show_view_count', $storeId);
    }

    /**
     * @param $code
     * @param null $storeId
     * @return mixed
     */
    public function getDateFormat($code, $storeId=null) {
        return $this->getConfigValue(self::XML_PATH_CONFIG . 'date_format/' .$code, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getBlogListPageTitle(?int $storeId = null): string
    {
        return (string) $this->getConfigValue(self::XML_PATH_SEO_PAGE_TITLE, $storeId);
    }
    /**
     * @param int|null $storeId
     * @return string
     */
    public function getBlogListMetaTitle(?int $storeId = null): string
    {
        return (string) $this->getConfigValue(self::XML_PATH_SEO_META_TITLE, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getBlogListMetaDescription(?int $storeId = null): string
    {
        return (string) $this->getConfigValue(self::XML_PATH_SEO_META_DESCRIPTION, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getBlogListMetaKeywords(?int $storeId = null): string
    {
        return (string) $this->getConfigValue(self::XML_PATH_SEO_META_KEYWORDS, $storeId);
    }
}
