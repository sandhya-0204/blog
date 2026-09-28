<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Sprinix\Blogs\Model\CommentFactory;
use Sprinix\Blogs\Model\ResourceModel\Comment\CollectionFactory;
use Sprinix\Blogs\Helper\Data;

/**
 * Class Comment
 * @package Sprinix\Blogs\Controller\Index
 */
class Comment extends Action
{
    /**
     * @var CommentFactory
     */
    protected $modelFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var Data
     */
    protected $_helperData;
    protected $commentCollectionFactory;
    /**
     * Comment constructor.
     * @param Context $context
     * @param CommentFactory $modelFactory
     * @param ResultFactory $resultFactory
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        CommentFactory $modelFactory,
        CollectionFactory $commentCollectionFactory,
        ResultFactory $resultFactory,
        Data $helperData,
    ){
        parent::__construct($context);
        $this->modelFactory = $modelFactory;
        $this->commentCollectionFactory = $commentCollectionFactory;
        $this->resultFactory = $resultFactory;
        $this->_helperData = $helperData;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $model = $this->modelFactory->create();
            $commentStatus = $this->_helperData->getCommentSetting('new_comment_status');
            $maxCommentNum = $this->_helperData->getCommentSetting('max_comments_per_email');
            $data = (array)$this->getRequest()->getPost();
            $email = trim((string) ($data['comment_email'] ?? ''));
            $postId = (int) ($data['commented_on'] ?? 0);
            if (!$postId) {
                $this->messageManager->addErrorMessage(__('Unable to identify the blog post.'));
                return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            }
            if($maxCommentNum > 0 && !empty($email) && $postId){
                $commentCount = $this->commentCollectionFactory->create()
                    ->addFieldToFilter('comment_email', $email)
                    ->addFieldToFilter('commented_on', $postId)
                    ->getSize();
                if ($commentCount >= $maxCommentNum) {
                    $this->messageManager->addErrorMessage(__('Sorry! You can submit maximum of %1 comments on this blog post.', $maxCommentNum));
                    return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                }
            }
            $model->setData($data);
            $model->setData('comment_status', $commentStatus);
            $model->save();
            $this->messageManager->addSuccessMessage(__("Your Comment Saved Successfully."));
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error : ' . $e->getMessage()));
        }
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
