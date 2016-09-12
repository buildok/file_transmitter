<?php
namespace buildok\file_transmitter;

use buildok\file_transmitter\base\FileTransmitter;

/**
* PictureTransmitter
* Sets rules to transmit image file
*
* @inheritdoc
*/
class PictureTransmitter extends FileTransmitter
{
    public function rules()
    {
        return [
            'type' => ['image'],
            'format' => ['jpeg', 'png', 'gif']
        ];
    }
}