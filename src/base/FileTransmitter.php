<?php
namespace buildok\file_transmitter\base;

use buildok\file_transmitter\base\interfaces\FileTransmitterInterface;
use buildok\file_transmitter\base\interfaces\FileValidatorInterface;

use buildok\file_transmitter\base\exceptions\FileTransmitterException;
use buildok\file_transmitter\base\exceptions\FileValidatorException;


/**
* FileTransmitter base class
*/
abstract class FileTransmitter
{
    /**
    * @var FileTransmitterInterface object
    */
    private $Transmitter; 

    /**
    * @var FileValidatorInterface object
    */
    private $Validator;   

    /**
    * @var array Array of validation errors 
    */
    private $errors;        

    /** 
    * Constructor 
    * @param FileTransmitterInterface $transmitter object for transmitting files
    * @param FileValidatorInterface $validator object for validation files
    */
    public function __construct(FileTransmitterInterface $transmitter, FileValidatorInterface $validator)
    {
        $this->Transmitter = $transmitter;
        $this->Validator = $validator;
    }

    /**
    * Get array of validation rules. 
    * This method must be overridden in child classes
    *
    * It is expected the following structure:
    * return [
    *    'validation metod name' => [array of validation rules],
    *    ...        
    * ]
    * @return array
    */
    abstract public function rules();

    /**
    * Get this class name
    * @return string
    */
    static public function className()
    {
        $namespace = explode('\\', get_called_class());
        return end($namespace);
    }

    /**
    * Get validation errors
    * @return array FileTransmitter::errors
    */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
    * Transmit file
    * @param string $from source
    * @param string $to destination
    * @uses FileTransmitterInterface::receiveFile()
    * @uses FileTransmitterInterface::sendFile()
    * @throws FileTransmitterException
    * @return boolean
    */
    public function transmit($from, $to)
    {
        $ret = false;

        try {
            $fileData = $this->Transmitter->receiveFile($from);

            if($ret = $this->validate($fileData['INFO'])) {
                $this->Transmitter->sendFile($fileData, $to);
            } 
        } catch (FileTransmitterException $e) {
            throw new FileTransmitterException(self::className() . ' - ' . $e->getMessage());
        }

        return $ret;
    }

    /**
    * Validate file and fill array FileTransmitter::errors
    * @param array $fileInfo
    * @uses FileValidatorInterface::validate()
    *  
    * It is expect the following data structure of file information:
    * $fileInfo = [
    *   'TYPE' =>   .. string file type
    *   'FORMAT' => .. string file format
    * ]
    * @return boolean
    */
    private function validate($fileInfo)
    {
        $rules = $this->rules();

        if(!empty($rules)) {
            try {
                foreach ($rules as $metod => $rule) {
                    if(!$this->Validator->validate($metod, $fileInfo, $rule)) {
                        $this->setError($metod);
                    }   
                }
            } catch(FileValidatorException $e) {

                die(self::className() . ' - ' . $e->getMessage());
            }
        }
       
        return !count($this->getErrors());
    }

    /**
    * Add new value to FileTransmitter::errors
    * @param string $key validation metod name
    */
    private function setError($key)
    {
        $rules = $this->rules();
        $this->errors[$key][] = 'Wrong value, ['.implode(', ', $rules[$key]).'] expected';
    }
}