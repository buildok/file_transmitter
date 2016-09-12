<?php
namespace buildok\file_transmitter\base\interfaces;

/** 
* FileValidatorInterface interface
* 
*  
* Interface expect the following data structure of file information:
* $fileInfo = [
*   'TYPE' =>   .. string file type
*   'FORMAT' => .. string file format
* ]
* @see more information of media types and formats: http://www.iana.org/assignments/media-types/media-types.xhtml
*/
interface FileValidatorInterface
{
    /**
    * Validate file
    * @param string $metod Validation metod
    * @param array $fileInfo file information
    * @param array $rule Validation rules
    * @throws FileValidatorException
    * @return boolean
    */
    public function validate($metod, $fileInfo, $rule);
}