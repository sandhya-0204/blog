<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs pvt Ltd.. (https://www.sprinix.com)
 */

namespace Sprinix\Blogs\Model\Source\AdminUsers;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;
use Magento\Framework\Message\ManagerInterface;
/**
 * Class OptionSource
 * @package Sprinix\Blogs\Model\Source\AdminUsers
 */
class OptionSource implements OptionSourceInterface
{
    protected $usersCollectionFactory;
    protected $messageManager;
    /**
     * OptionSource constructor.
     * @param UserCollectionFactory $usersCollectionFactory
     */
    public function __construct(
        UserCollectionFactory $usersCollectionFactory,
        ManagerInterface $messageManager
    ) {
        $this->usersCollectionFactory = $usersCollectionFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        try {
            $usersCollection = $this->usersCollectionFactory->create();
            $users = $usersCollection
                ->addFieldToSelect('*')
                ->addFieldToFilter('is_active', ['eq' => 1]);
            foreach ($users as $user) {
                array_push(
                    $options,
                    [
                        'label' => $user->getUsername(),
                        'value' => $user->getUsername()
                    ]
                );
            }
        }catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
        }
        return $options;
    }
}