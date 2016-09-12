<?php
namespace buildok\file_transmitter\tests\base;

use PHPUnit_Framework_TestCase as TestCase;
use buildok\file_transmitter\base\FileValidator;

class FileValidatorTest extends TestCase
{
    private $validator;
    private $fileInfo;

    public function setUp()
    {
        $this->validator = new FileValidator;

        $this->fileInfo = [
            'TYPE' =>  'image',
            'FORMAT' => 'png'
        ];

        $this->rule = [
            'type' => ['image'],
            'format' => ['jpeg', 'png', 'gif']
        ];
    }

    public function testValidateMetodType()
    {
        $res = $this->validator->validate('type', $this->fileInfo, $this->rule['type']);
        $this->assertTrue($res);
    }

    public function testValidateMetodFormat()
    {
        $res = $this->validator->validate('format', $this->fileInfo, $this->rule['format']);
        $this->assertTrue($res);
    }

    /**
     * @expectedException buildok\file_transmitter\base\exceptions\FileValidatorException
    */
    public function testValidateMetodIncorrect()
    {
        $this->validator->validate('incorrect', $this->fileInfo, $this->rule);
    }

    public function testValidateTypeInvalid()
    {
        $invalidFileType = [
            'TYPE' =>  'incorrect',
            'FORMAT' => 'png'
        ];

        $res = $this->validator->validate('type', $invalidFileType, $this->rule['type']);
        $this->assertFalse($res);
    }

    public function testValidateFormatInvalid()
    {
        $invalidFileFormat = [
            'TYPE' =>  'image',
            'FORMAT' => 'incorrect'
        ];

        $res = $this->validator->validate('format', $invalidFileFormat, $this->rule['format']);
        $this->assertFalse($res);
    }
}