<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Adminhtml\Posts;

use Magento\UrlRewrite\Model\UrlRewriteFactory;

/**
 * Class CustomUrl
 * @package Sprinix\Blogs\Controller\Adminhtml\Posts
 */
class CustomUrl
{
    /**
     * @var UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * CustomUrl constructor.
     * @param UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        UrlRewriteFactory $urlRewriteFactory
    ){
        $this->urlRewriteFactory = $urlRewriteFactory;
    }

    /**
     * @param $postId
     * @param $urlKey
     */
    public function createPostUrlRewrite($postId, $urlKey)
    {
        if(!empty($postId) && !empty($urlKey)) {
            $this->createRewriteUrl(
                "blogs/index/blog/id/$postId",
                "blog/$urlKey"
            );
        }
    }

    /**
     * @param $categoryId
     * @param $urlKey
     */
    public function createCategoryUrlRewrite($categoryId, $urlKey) {
         if(!empty($categoryId) && !empty($urlKey)) {
             $this->createRewriteUrl(
                 "blogs/index/index/category/$categoryId",
                 "blog/category/$urlKey"
             );
         }
    }

    /**
     * @param $tagId
     * @param $urlKey
     */
    public function createTagUrlRewrite($tagId, $urlKey) {
        if(!empty($tagId) && !empty($urlKey)) {
            $this->createRewriteUrl(
                "blogs/index/index/tags/$tagId",
                "blog/tags/$urlKey"
            );
        }
    }

    /**
     * @param $postId
     * @param $prevUrlKey
     * @param $newUrlKey
     */
    public function updatePostUrlRewrite($postId, $prevUrlKey, $newUrlKey) {
        if(!empty($prevUrlKey) && !empty($newUrlKey)) {
            $this->updateRewriteUrl(
                "blog/$prevUrlKey",
                "blogs/index/blog/id/$postId",
                "blog/$newUrlKey"
            );
        }
    }

    /**
     * @param $categoryId
     * @param $prevUrlKey
     * @param $newUrlKey
     */
    public function updateCategoryUrlRewrite($categoryId, $prevUrlKey, $newUrlKey) {

        if(!empty($categoryId) && !empty($prevUrlKey) && !empty($newUrlKey)) {
            $this->updateRewriteUrl(
                "blog/category/$prevUrlKey",
                "blogs/index/index/category/$categoryId",
                "blog/category/$newUrlKey"
            );
        }
    }

    /**
     * @param $tagId
     * @param $prevUrlKey
     * @param $newUrlKey
     */
    public function updateTagUrlRewrite($tagId, $prevUrlKey, $newUrlKey) {

        if(!empty($tagId) && !empty($prevUrlKey) && !empty($newUrlKey)) {
            $this->updateRewriteUrl(
                "blog/tags/$prevUrlKey",
                "blogs/index/index/tags/$tagId",
                "blog/tags/$newUrlKey"
            );
        }
    }

    /**
     * @param $urlKey
     * @throws \Exception
     */
    public function deletePostRewriteUrl($urlKey) {
        $url = "blog/$urlKey";
        $this->deleteRewriteUrl($url);
    }

    /**
     * @param $urlKey
     * @throws \Exception
     */
    public function deleteCategoryRewriteUrl($urlKey) {
        $url = "blog/category/$urlKey";
        $this->deleteRewriteUrl($url);
    }

    /**
     * @param $urlKey
     * @throws \Exception
     */
    public function deleteTagRewriteUrl($urlKey)
    {
        $url = "blog/tags/$urlKey";
        $this->deleteRewriteUrl($url);
    }

    /**
     * @param $targetUrl
     * @param $requestUrl
     */
    public function createRewriteUrl($targetUrl, $requestUrl) {
        try {
            $urlRewrite = $this->urlRewriteFactory->create();
            $urlRewrite->setEntityType('custom');
            $urlRewrite->setStoreId(1);
            $urlRewrite->setIsSystem(0);
            $urlRewrite->setIdPath(rand(1, 100000));
            $urlRewrite->setTargetPath($targetUrl);
            $urlRewrite->setRequestPath($requestUrl);
            $urlRewrite->save();
        }catch(\Exception $e) {}
    }

    /**
     * @param $prevRequestUrl
     * @param $updatedTargetUrl
     * @param $updatedRequestUrl
     */
    public function updateRewriteUrl($prevRequestUrl, $updatedTargetUrl, $updatedRequestUrl) {
        try {
            $urlRewrite = $this->urlRewriteFactory->create();
            $model = $urlRewrite->load($prevRequestUrl, 'request_path');

            if(!empty($model->getData())) {
                $model->setTargetPath($updatedTargetUrl);
                $model->setRequestPath($updatedRequestUrl);
                $model->save();
            }
        }catch (\Exception $e) {}
    }

    /**
     * @param $urlKey
     */
    public function deleteRewriteUrl($urlKey) {
        try {
            $urlRewrite = $this->urlRewriteFactory->create();
            $model = $urlRewrite->load($urlKey, 'request_path');
            if(!empty($model->getData())) {
                $model->delete();
            }
        }catch (\Exception $e) {}
    }
}