<?php
namespace buildok\file_transmitter\base;

use buildok\file_transmitter\base\exceptions\FileTransmitterException;
use buildok\file_transmitter\base\interfaces\FileTransmitterInterface;

/**
* HttpTransmitter class
* Received file from URL and send it to filesystem
*/
class HttpTransmitter implements FileTransmitterInterface
{
    /**
    * Receive file from URL
    * @param string $from file URL
    * @throws FileTransmitterException
    * @return array data structure of file    
    * @see FileTransmitterInterface
    */
    public function receiveFile($from)
    {
        $fileData['CONTENT'] = @file_get_contents($from);

        if($fileData['CONTENT'] === false) {
            throw new FileTransmitterException('Failure to receive content: ' . $from);  
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        list($fileData['INFO']['TYPE'], $fileData['INFO']['FORMAT']) = explode('/', $finfo->buffer($fileData['CONTENT']));

        return $fileData;
    }

    /**
    * Send file to filesystem
    * @param array $fileData data structure of file
    * @param string $to directory name
    * @throws FileTransmitterException
    * @return boolean    
    * @see FileTransmitterInterface
    */
    public function sendFile($fileData, $to)
    {
        if(is_dir($to)) {
            
            $filename = md5($fileData['CONTENT']) . '.' . $fileData['INFO']['FORMAT'];
            $filePath = $to . DIRECTORY_SEPARATOR . $filename;
            
            $ret = !(@file_put_contents($filePath, $fileData['CONTENT']) === false);

            if($ret == false) {
                throw new FileTransmitterException('Failure when sending a file to the: ' . $to);  
            }

            return $ret;
        } 
        
        throw new FileTransmitterException('Directory does not exist: ' . $to);
    }

}
