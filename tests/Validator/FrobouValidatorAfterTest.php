<?php

namespace Frobou\Validator;

class FrobouValidatorAfterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FrobouValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new FrobouValidator();
    }

    public function testValidateError()
    {
        $data = [ValidatorTypes::ERROR => null];
        $this->assertTrue($this->validator->validateAfter($data));

        $d = [1 => "0001", 2 => "Error on prepare to select - Query: select pool_name from radippools group by pool_name"];
        $data = [ValidatorTypes::ERROR => $d];
        $this->assertArrayHasKey('error', $this->validator->validateAfter($data));
    }

    public function testValidateNotfound()
    {
        $data = [ValidatorTypes::NOTFOUND => ['data'=>'value']];
        $this->assertTrue($this->validator->validateAfter($data));

        $d = [];
        $data = [ValidatorTypes::NOTFOUND => $d];
        $this->assertArrayHasKey('error', $this->validator->validateAfter($data));
    }

    public function testValidateExists()
    {
        $data = [ValidatorTypes::EXISTS => []];
        $this->assertTrue($this->validator->validateAfter($data));

        $d = ['data'=>'value'];
        $data = [ValidatorTypes::EXISTS => $d];
        $this->assertArrayHasKey('error', $this->validator->validateAfter($data));
    }

    public function testValidateIsnull()
    {
        $data = [ValidatorTypes::ISNULL => []];
        $this->assertTrue($this->validator->validateAfter($data));

        $d = ['data'=>'value'];
        $data = [ValidatorTypes::ISNULL => $d];
        $this->assertArrayHasKey('error', $this->validator->validateAfter($data));
    }

}