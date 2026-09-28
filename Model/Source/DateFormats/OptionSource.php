<?php
/**
 * Created by PhpStorm.
 * User: sprinix
 * Date: 22/11/24
 * Time: 3:49 PM
 */

namespace Sprinix\Blogs\Model\Source\DateFormats;


use Magento\Framework\Data\OptionSourceInterface;

class OptionSource implements OptionSourceInterface
{


    public function toOptionArray()
    {
        return [
            [
                "label" => "dd-MM-yyyy",
                "value" => "dd-MM-yyyy"
            ],
            [
                "label" => "yyyy-MM-dd",
                "value" => "yyyy-MM-dd"
            ],
            [
                "label" => "yyyy/MM/dd",
                "value" => "yyyy/MM/dd"
            ],
            [
                "label" => "MM/dd/yyyy",
                "value" => "MM/dd/yyyy"
            ],
            [
                "label" => "dd/MM/yyyy",
                "value" => "dd/MM/yyyy"
            ],
            [
                "label" => "yyyy/MM/dd",
                "value" => "yyyy/MM/dd"
            ],
            [
                "label" => "MMM d, yyyy",
                "value" => "MMM d, yyyy"
            ],
            [
                "label" => "d MMM, yyyy",
                "value" => "d MMM, yyyy"
            ],
            [
                "label" => "M/d/yyyy h:mm a",
                "value" => "M/d/yyyy h:mm a"
            ],
            [
                "label" => "Relative Date/Time",
                "value" => "Relative Date"
            ],
        ];
    }
}