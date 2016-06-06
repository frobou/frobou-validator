<?php

namespace Frobou\Validator;

trait FrobouMessages
{
    private $minimum_value;

    protected function minimumValue($error_found)
    {
        $out = 'Minimum value error on field ';
        foreach ($error_found as $v) {
            foreach ($v as $key => $value) {
                $out .= "{$key} => {$value} | ";
            }
        }
        $this->minimum_value = substr($out, 0, strlen($out) - 3);
    }
}