<?php

namespace Frobou\Validator;

class FrobouValidatorTest extends \PHPUnit_Framework_TestCase
{
    private $data;
    private $object;
    private $validator;

    public function setUp()
    {
        $this->data = [];
        $this->object = new \stdClass();
        $this->validator = new FrobouValidator();
    }

    public function tearDown()
    {
        $this->data = [];
    }

    public function testValidateIntegerOk()
    {
        $this->data['integer'] = [];
        $this->object->start = '1';
        $this->object->qtty = '1';
        array_push($this->data['integer'], clone $this->object, ['start', 'qtty']);
        $this->assertTrue($this->validator->validate(['integer'], $this->data, true));
    }

    public function testValidateIntegerFail()
    {
        $this->data['integer'] = [];
        $this->object->start = 'teste';
        $this->object->qtty = '0teste';
        array_push($this->data['integer'], clone $this->object, ['start', 'qtty']);
        $this->assertArrayHasKey('integer', $this->validator->validate(['integer'], $this->data, true));
    }

    public function testValidateStructOk()
    {
        $this->data['struct'] = [];
        $this->object->start = '1';
        $this->object->qtty = '1';
        $this->object->pastel = 'de carne';
        array_push($this->data['struct'], clone $this->object, ['start', 'qtty', 'pastel'], ['pimponeta']);
        $this->assertTrue($this->validator->validate(['struct'], $this->data, true));
    }

    public function testValidateStructFail()
    {
        $this->data['struct'] = [];
        $this->object->start = 'teste';
        $this->object->qtty = '0teste';
        array_push($this->data['struct'], clone $this->object, ['start', 'qtty', 'pastel'], ['pimponeta']);
        $this->assertArrayHasKey('struct', $this->validator->validate(['struct'], $this->data, true));
    }

    public function testValidateRequiredOk()
    {
        $this->data['required'] = [];
        $this->object->start = '1';
        $this->object->qtty = '1';
        $this->object->pastel = 'de carne';
        array_push($this->data['required'], clone $this->object, ['start', 'qtty', 'pastel']);
        $this->assertTrue($this->validator->validate(['required'], $this->data, true));
    }

    public function testValidateRequiredFail()
    {
        $this->data['required'] = [];
        $this->object->start = '1';
        $this->object->qtty = '2';
        $this->object->pastel = '';
        array_push($this->data['required'], clone $this->object, ['start', 'qtty', 'pastel']);
        $this->assertArrayHasKey('required', $this->validator->validate(['required'], $this->data, true));
    }

    public function testValidateValuesOk()
    {
        $this->data['values'] = [];
        $this->object->pastel = 'carne';
        array_push($this->data['values'], clone $this->object, ['pastel'], ['carne', 'queijo', 'ovo']);
        $this->assertTrue($this->validator->validate(['values'], $this->data, true));
    }

    public function testValidateValuesFail()
    {
        $this->data['values'] = [];
        $this->object->pastel = 'azeitona';
        array_push($this->data['values'], clone $this->object, ['pastel'], ['carne', 'queijo', 'ovo']);
        $this->assertArrayHasKey('values', $this->validator->validate(['values'], $this->data, true));
    }

    public function testValidateMaxLenOk()
    {
        $this->data['maxlen'] = [];
        $this->object->start = 'ovo';
        $this->object->pastel = 'palmito';
        array_push($this->data['maxlen'], clone $this->object, ['pastel' => 12, 'start' => 3]);
        $this->assertTrue($this->validator->validate(['maxlen'], $this->data, true));
    }

    public function testValidateMaxLenFail()
    {
        $this->data['maxlen'] = [];
        $this->object->start = 'ovo';
        $this->object->pastel = 'zebra de patins';
        array_push($this->data['maxlen'], clone $this->object, ['pastel' => 12, 'start' => 2]);
        $this->assertArrayHasKey('maxlen', $this->validator->validate(['maxlen'], $this->data, true));
    }

    public function testValidateMinLenOk()
    {
        $this->data['minlen'] = [];
        $this->object->start = 'ovo';
        $this->object->pastel = 'palmito';
        array_push($this->data['minlen'], clone $this->object, ['pastel' => 5, 'start' => 2]);
        $this->assertTrue($this->validator->validate(['minlen'], $this->data, true));
    }

    public function testValidateMinLenFail()
    {
        $this->data['minlen'] = [];
        $this->object->start = 'ovo';
        $this->object->pastel = 'zebra de patins';
        array_push($this->data['minlen'], clone $this->object, ['pastel' => 35, 'start' => 5]);
        $this->assertArrayHasKey('minlen', $this->validator->validate(['minlen'], $this->data, true));
    }

    public function testValidateEmailOk()
    {
        $this->data['email'] = [];
        $this->object->email = 'teste@email.com';
        array_push($this->data['email'], clone $this->object, ['email']);
        $this->assertTrue($this->validator->validate(['email'], $this->data, true));
    }

    public function testValidateEmailFail()
    {
        $this->data['email'] = [];
        $this->object->email = '1';
        array_push($this->data['email'], clone $this->object, ['email']);
        $this->assertArrayHasKey('email', $this->validator->validate(['email'], $this->data, true));
    }

    public function testValidateMinOk()
    {
        $this->data['min'] = [];
        $this->object->start = '1';
        $this->object->qtty = '1';
        array_push($this->data['min'], $this->object, ['start' => 1, 'qtty' => 1]);
        $this->assertTrue($this->validator->validate(['min'], $this->data, true));
    }

    public function testValidateMinFail()
    {
        $this->data['min'] = [];
        $this->object->start = '0';
        $this->object->qtty = '0';
        array_push($this->data['min'], $this->object, ['start' => 1, 'qtty' => 1]);
        $this->assertArrayHasKey('min', $this->validator->validate(['min'], $this->data, true));
    }

    public function testValidateMaxOk()
    {
        $this->data['max'] = [];
        $this->object->start = '1';
        $this->object->qtty = '1';
        array_push($this->data['max'], $this->object, ['start' => 1, 'qtty' => '1']);
        $this->assertTrue($this->validator->validate(['max'], $this->data, true));
    }

    public function testValidateMaxFail()
    {
        $this->data['max'] = [];
        $this->object->start = '31';
        $this->object->qtty = '13';
        array_push($this->data['max'], $this->object, ['start' => 3, 'qtty' => 3]);
        $this->assertArrayHasKey('max', $this->validator->validate(['max'], $this->data, true));
    }

    public function testValidateIpOk()
    {
        $this->data['ip'] = [];
        $this->object->ip_1 = '1.1.1.1';
        $this->object->ip_2 = '1.2.3.4';
        array_push($this->data['ip'], clone $this->object, ['ip_1', 'ip_2']);
        $this->assertTrue($this->validator->validate(['ip'], $this->data, true));
    }

    public function testValidateIpFail()
    {
        $this->data['ip'] = [];
        $this->object->ip_1 = '1';
        $this->object->ip_2 = '1';
        array_push($this->data['ip'], clone $this->object, ['ip_1', 'ip_2']);
        $this->assertArrayHasKey('ip', $this->validator->validate(['ip'], $this->data, true));
    }

    public function testValidateDateEnOk()
    {
        $this->data['date_en'] = [];
        $this->object->dt_1 = '2015-11-22';
        $this->object->dt_2 = '2016-02-09';
        array_push($this->data['date_en'], clone $this->object, ['dt_1', 'dt_2']);
        $this->assertTrue($this->validator->validate(['date_en'], $this->data, true));
    }

    public function testValidateDateEnFail()
    {
        $this->data['date_en'] = [];
        $this->object->dt_1 = '1';
        $this->object->dt_2 = '1';
        array_push($this->data['date_en'], clone $this->object, ['dt_1', 'dt_2']);
        $this->assertArrayHasKey('date_en', $this->validator->validate(['date_en'], $this->data, true));
    }

}