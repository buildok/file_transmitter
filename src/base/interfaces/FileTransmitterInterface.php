<?php
namespace buildok\file_transmitter\base\interfaces;

/** 
* FileTransmitterInterface interface
* 
*  
* Interface expect the following data structure of file:
* $fileData = [
*   'CONTENT' =>    .. string file content
*   'INFO' => [
*       'TYPE' =>   .. string file type
*       'FORMAT' => .. string file format
*   ] 
* ]
* @see more information of media types and formats: http://www.iana.org/assignments/media-types/media-types.xhtml
*/
interface FileTransmitterInterface
{
    /**
    * Receive file from the source
    * @param string $from file URI
    * @throws FileTransmitterException
    * @return array data structure of file
    */
    public function receiveFile($from);

    /**
    * Send file to destination
    * @param array $fileData data structure of file
    * @param string $to destination
    * @throws FileTransmitterException
    * @return boolean
    */
    public function sendFile($fileData, $to);
}