<?php
/**
 * @package     Sprinix_Blogs
 * @copyright   Copyright (c) 2024 Sprinix Technolabs Pvt Ltd.
 */

namespace Sprinix\Blogs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Sprinix\Blogs\Helper\Data;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Psr\Log\LoggerInterface;

class CustomDateTime extends AbstractHelper
{
    protected $helper;
    protected $logger;
    protected $timezone;

    public function __construct(
        Context $context,
        Data $helper,
        LoggerInterface $logger,
        TimezoneInterface $timezone
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->logger = $logger;
        $this->timezone = $timezone;
    }

    public function getTimeDiff($date)
    {
        try {
            $currentDateTime = new \DateTime();
            $inputDateTime = new \DateTime($date);
            $interval = $currentDateTime->diff($inputDateTime);

            if (!empty($interval)) {
                if ($interval->y > 0) {
                    return $interval->y > 1 ? $interval->y . ' years ago' : $interval->y . ' year ago';
                } elseif ($interval->m > 0) {
                    return $interval->m > 1 ? $interval->m . ' months ago' : $interval->m . ' month ago';
                } elseif ($interval->d > 0) {
                    return $interval->d > 1 ? $interval->d . ' days ago' : $interval->d . ' day ago';
                } elseif ($interval->h > 0) {
                    return $interval->h . 'h ago';
                } elseif ($interval->i > 0) {
                    return $interval->i . 'm ago';
                } else {
                    return $interval->s . 's ago';
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('CustomDateTime Error: ' . $e->getMessage());
            return null;
        }
    }

    public function getFormattedDate($date)
    {
        try {
            $format = $this->helper->getDateFormat('format');
            $inputDate = new \DateTime($date);

            switch ($format) {
                case 'dd-MM-yyyy':
                    return $this->timezone->formatDate($inputDate, \IntlDateFormatter::MEDIUM, false); // Will map to d-M-y pattern
                case 'yyyy-MM-dd':
                    return $this->timezone->formatDateTime($inputDate, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'yyyy-MM-dd');
                case 'MM/dd/yyyy':
                    return $this->timezone->formatDateTime($inputDate, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'MM/dd/yyyy');
                case 'dd/MM/yyyy':
                    return $this->timezone->formatDateTime($inputDate, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'dd/MM/yyyy');
                case 'yyyy/MM/dd':
                    return $this->timezone->formatDateTime($inputDate, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'yyyy/MM/dd');
                case 'MMM d, yyyy':
                    return $this->timezone->formatDateTime($inputDate, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'MMM d, yyyy');
                case 'd MMM, yyyy':
                    return $this->timezone->formatDateTime($inputDate, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'd MMM, yyyy');
                case 'M/d/yyyy h:mm a':
                    return $this->timezone->formatDateTime($inputDate, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'M/d/yyyy h:mm a');
                case 'Relative Date':
                    return $this->getTimeDiff($date);
                default:
                    return $this->timezone->formatDateTime($inputDate, \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
            }
        } catch (\Exception $e) {
            $this->logger->error('Date Format Error: ' . $e->getMessage());
            return null;
        }
    }
}
