<?php
namespace buildok\file_transmitter\tests\base;

use PHPUnit_Framework_TestCase as TestCase;
use buildok\file_transmitter\base\HttpTransmitter;


class HttpTransmitterTest extends TestCase
{
    private $transmitter;
    private $url;

    public function setUp()
    {
        $this->transmitter = new HttpTransmitter;
        $this->url = 'https://scotch.io/wp-content/uploads/2015/03/solid-object-oriented-design.jpg';
    }

    /**
     * @expectedException buildok\file_transmitter\base\exceptions\FileTransmitterException
    */
    public function testReceiveFileFromBadURL()
    {
        $fileData = $this->transmitter->receiveFile('');
    }
    
    public function testReceiveFile()
    {
        $fileData = $this->transmitter->receiveFile($this->url);
        $this->assertFalse(empty($fileData));

        return $fileData;
    }

    /**
    * @depends testReceiveFile
    */
    public function testContentReceivedFile(array $fileData)
    {
        $this->assertTrue(isset($fileData['CONTENT']));
    }

    /**
    * @depends testReceiveFile
    */
    public function testInfoReceivedFile(array $fileData)
    {
        $this->assertTrue(isset($fileData['INFO']));
    }

    /**
    * @depends testReceiveFile
    */
    public function testInfoTypeReceivedFile(array $fileData)
    {
        $this->assertTrue(isset($fileData['INFO']['TYPE']));
    }

    /**
    * @depends testReceiveFile
    */
    public function testInfoFormatReceivedFile(array $fileData)
    {
        $this->assertTrue(isset($fileData['INFO']['FORMAT']));
    }

    /**
    * @depends testReceiveFile
    */
    public function testSendFile(array $fileData)
    {
        mkdir('testDir');

        $this->assertTrue($this->transmitter->sendFile($fileData, 'testDir'));

        array_map('unlink', glob('testDir/*'));
        rmdir('testDir');
    }

    /**
    * @depends testReceiveFile
    * @expectedException buildok\file_transmitter\base\exceptions\FileTransmitterException
    */
    public function testSendFileToNonexistentDir(array $fileData)
    {
        $this->transmitter->sendFile($fileData, 'NonExistentDir');
    }    

    /**
    * @depends testReceiveFile
    * @expectedException buildok\file_transmitter\base\exceptions\FileTransmitterException
    */
    public function testSendFileWithoutAccessright(array $fileData)
    {
        if(!is_dir('tests/base/TestDir_PermissionDenied')) {
            mkdir('tests/base/TestDir_PermissionDenied', 040664);
        }
        
        $this->transmitter->sendFile($fileData, 'tests/base/TestDir_PermissionDenied');
    }    

}