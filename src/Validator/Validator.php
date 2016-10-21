<?php

namespace Frobou\Validator;

class Validator extends FrobouValidator
{
    private $data;

    public function __construct(array $data)
    {
        if (!is_array($data)) {
            return 'Input data must be an array';
        }
        $this->data = $data;
    }

    public function validate()
    {
        $error_list = [];
        foreach ($this->data['type'] as $value) {
            switch ($value) {
                case 'min':
                    $min = $this->validateMinimumValue($this->data['min']['data'], $this->data['min']['value_list']);
                    if ($min !== true) {
                        array_push($error_list, $min);
                    }
                    break;
                case 'max':
                    $max = $this->validateMaximunValue($this->data['max']['data'], $this->data['max']['value_list']);
                    if ($max !== true) {
                        array_push($error_list, $max);
                    }
                    break;
            }
        }
        if (count($error_list) > 0) {
            return $error_list;
        }
        return true;
    }


}