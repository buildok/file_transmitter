<?php
namespace buildok\file_transmitter\tests;

use PHPUnit_Framework_TestCase as TestCase;
use buildok\file_transmitter\PictureTransmitter;

class PictureTransmitterTest extends TestCase
{
    private $t;
    private $v;
    
    public function setUp()
    {
        $this->v = $this->createMock('buildok\file_transmitter\base\interfaces\FileValidatorInterface');
        
        $fileData = [
            'CONTENT' => 'string file content',
            'INFO' => [
                'TYPE' => 'string file type',
                'FORMAT' => 'string file format'
            ]            
        ];
        
        $this->t = $this->createMock('buildok\file_transmitter\base\interfaces\FileTransmitterInterface');
        $this->t->method('receiveFile')->will($this->returnValue($fileData));
    }
    
    public function testTransmitCorrectFileData()
    {
        $this->v->method('validate')->will($this->returnValue(true));
        $PT = new PictureTransmitter($this->t, $this->v);
        
        $this->assertTrue($PT->transmit('', ''));
    }
    
    public function testTransmitWrongFileData()
    {
        $this->v->method('validate')->will($this->returnValue(false));
        $PT = new PictureTransmitter($this->t, $this->v);
        
        $this->assertFalse($PT->transmit('', ''));
        
        return $PT;
    }
    
    /**
    * @depends testTransmitWrongFileData
    */
    public function testGetErrors($PT)
    {        
        $this->assertGreaterThan(0, count($PT->getErrors()));
    }
    
}