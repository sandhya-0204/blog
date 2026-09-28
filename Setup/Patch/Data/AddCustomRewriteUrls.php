<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Class AddCustomRewriteUrls
 * @package Sprinix\Blogs\Setup\Patch\Data
 */
class AddCustomRewriteUrls implements DataPatchInterface
{
    /**
     * @var \Magento\UrlRewrite\Model\UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * AddCustomRewriteUrls constructor.
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
    ){
        $this->urlRewriteFactory = $urlRewriteFactory;
    }

    /**
     * @return array
     */
    public static function getDependencies()
    {
       return [];
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Generate Rewrite Url
     */
    public function apply()
    {
        $urls = [
            [
                "target_url" => "blogs/index/index",
                "request_url" => "blog",
            ],
            [
                "target_url" => "blogs/index/index/search",
                "request_url" => "blog/search",
            ]
        ];
        try {
            foreach ($urls as $url) {
                $urlRewrite = $this->urlRewriteFactory->create();
                $urlRewrite->setEntityType('custom');
                $urlRewrite->setStoreId(1);
                $urlRewrite->setIsSystem(0);
                $urlRewrite->setIdPath(rand(1, 100000));
                $urlRewrite->setTargetPath($url['target_url']);
                $urlRewrite->setRequestPath($url['request_url']);
                $urlRewrite->save();
            }
        }catch (\Exception $e) {

        }
    }
}
