<?php
namespace buildok\file_transmitter\base;

use buildok\file_transmitter\base\exceptions\FileValidatorException;
use buildok\file_transmitter\base\interfaces\FileValidatorInterface;

/**
* FileValidator class
* 
*/
class FileValidator implements FileValidatorInterface
{  
    /**
    * Validate file
    * @param string $metod Validation metod
    * @param array $fileInfo file information
    * @param array $rule Validation rules
    * @throws FileValidatorException
    * @return boolean
    *@see FileValidatorInterface
    */
    public function validate($metod, $fileInfo, $rule)
    {
        $this->fileInfo = $fileInfo;
        $func = 'check' . ucfirst($metod);

        if(!method_exists($this, $func)) {
            throw new FileValidatorException('Undefined validator: ' . $metod);  
        } 
        
        return call_user_func_array([$this, $func], [$rule, $fileInfo]);
    }

    /**
    * Check type of file
    * @param array $rule validation rule
    * @param array $fileInfo file information
    * @return boolean
    */
    protected function checkType($rule, $fileInfo)
    {
        return in_array($fileInfo['TYPE'], $rule);
    }

    /**
    * Check extension of file
    * @param array $rule validation rule
    * @param array $fileInfo file information
    * @return boolean
    */
    protected function checkFormat($rule, $fileInfo)
    {
        return in_array($fileInfo['FORMAT'], $rule);
    }
}