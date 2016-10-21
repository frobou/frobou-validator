<?php

namespace Frobou\Validator;

class FrobouValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function testValidate()
    {
        $data['min'] = [];
        $data['max'] = [];
        $data['struct'] = [];

        $dtmin = new \stdClass();
        $dtmin->start = 1;
        $dtmin->qtty = 1;

        array_push($data['min'], $dtmin, ['start' => 1, 'qtty' => 1]);
        array_push($data['max'], clone $dtmin, ['start' => 1, 'qtty' => 1]);
        array_push($data['struct'], clone $dtmin, ['start', 'qtty']);

        $validator = new FrobouValidator();
        $this->assertTrue($validator->validate(['struct','min','max'], $data));
    }

}