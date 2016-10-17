<?php

use RHMVC\AbstractModel;

class DefaultModel extends AbstractModel
{

    public function getSampleData()
    {
        return [
            [
                'key'   => 'Key 1',
                'value' => 'Value 1'
            ],
            [
                'key'   => 'Key 2',
                'value' => 'Value 3'
            ],
            [
                'key'   => 'Key 3',
                'value' => 'Value 3'
            ]
        ];
    }

}
