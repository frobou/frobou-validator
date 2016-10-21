<?php

namespace Frobou\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function testValidate()
    {
        $data = [];
        $data['type'] = ['min', 'max'];

        $dtmin = new \stdClass();
        $dtmin->start = 0;
        $dtmin->qtty = 1;
        $data['min']['data'] = $dtmin;
        $data['min']['value_list'] = ['start' => 1, 'qtty' => 1];

        $dtmax = new \stdClass();
        $dtmax->start = 2;
        $dtmax->qtty = 1;
        $data['max']['data'] = $dtmax;
        $data['max']['value_list'] = ['start' => 1, 'qtty' => 1];

        $validate = new Validator($data);
        $this->assertTrue($validate->validate());
    }

}