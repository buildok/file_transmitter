# File transmitter

This composer-package allows you to receive pictures from a remote host and save the file system.

Example implementation with applying **SOLID**.


## Install

Via Composer

``` bash
$ composer require buildok/file_transmitter
```

## Usage

``` php
require_once "vendor/autoload.php";

use buildok\file_transmitter\base\exceptions\FileTransmitterException;
use buildok\file_transmitter\base\HttpTransmitter;
use buildok\file_transmitter\base\FileValidator;
use buildok\file_transmitter\PictureTransmitter;

$pt = new PictureTransmitter(new HttpTransmitter, new FileValidator);

try {
    if($pt->transmit('https://avatars2.githubusercontent.com/u/6459721?v=3&s=460', '/var/www/html')) {
        echo 'transmit OK';
    } else {
        print_r($pt->getErrors());
    }
    
} catch(FileTransmitterException $e) {

    echo $e->getMessage();
}
```