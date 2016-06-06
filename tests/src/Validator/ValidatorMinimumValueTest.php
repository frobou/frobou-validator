<?php
namespace Frobou\Validator;

class ValidatorMinimumValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator;
     */
    private $validator;
    private $obj;

    public function setUp()
    {
        $this->validator = new Validator();
        $this->obj = new \stdClass();
        $this->obj->valor4 = 4;
        $this->obj->valor300 = 300;
    }

    public function testValidateMinimumValueComParametroIncorreto()
    {
        $ret = $this->validator->validateMinimumValue([],[]);
        $this->assertFalse($ret);
        $this->assertEquals('Incorrect param type', $this->validator->getMinimumValueMessage());
    }

    public function testValidateMinimumValueCorreto(){
        $ret = $this->validator->validateMinimumValue($this->obj,['valor4' => 4, 'valor300' => 299]);
        $this->assertTrue($ret);
        $this->assertEquals('OK', $this->validator->getMinimumValueMessage());
    }

    public function testValidateMinimumValueErrado(){
        $ret = $this->validator->validateMinimumValue($this->obj,['valor4' => 3, 'valor300' => 301]);
        $this->assertFalse($ret);
        $this->assertRegExp("/Minimum value error on field/", $this->validator->getMinimumValueMessage());
    }
}