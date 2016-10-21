<?php

namespace Frobou\Validator;

abstract class FrobouValidatorAbstract
{
    protected $error_list = [];

    public function struct($data)
    {
        $this->validateHeader($data, 'validateStructure');
        $a['not_found'] = [];
        $a['not_allowed'] = [];
        $has = false;
        foreach ($data[1] as $value) {
            if (!isset($data[0]->{$value})) {
                $has = true;
                array_push($a['not_found'], $value);
            }
        }
        if (!isset($data[2])) {
            $data[2] = [];
        }
        foreach ($data[0] as $key => $value) {
            if (!in_array($key, $data[1])) {
                if (!in_array($key, $data[2])) {
                    $has = true;
                    array_push($a['not_allowed'], $key);
                }
            }
        }
        if ($has === true) {
            if (count($a['not_found']) > 0) {
                $this->error_list['not_found'] = $a['not_found'];
            }
            if (count($a['not_allowed']) > 0) {
                $this->error_list['not_allowed'] = $a['not_allowed'];
            }
        }
    }

    public function jsonValidateRequired($json, $header)
    {

    }

    public function jsonValidateInteger($json, $header)
    {

    }

    public function jsonValidateEmail($json, $header)
    {

    }

    public function jsonValidateArray($json)
    {

    }

    public function jsonValidateValues($json, $header, $values)
    {

    }

    public function jsonValidateMaxLength($json, $header)
    {

    }

    public function jsonValidateMinLength($json, $header)
    {

    }

    public function jsonValidateIp($json, $header)
    {

    }

    public function jsonValidateDate($json, $header)
    {

    }

    protected function min(array $data)
    {
        $this->validateHeader($data, 'validateMinimumValue');
        $error_list = [];
        foreach ($data[0] as $key => $value) {
            if (key_exists($key, $data[1]) && (intval($value) < intval($data[1][$key]))) {
                array_push($error_list, [$key => "exp: {$data[1][$key]} - rec: {$value}"]);
            }
        }
        if (count($error_list) > 0) {
            $out = 'Minimum value error on field(s) ';
            foreach ($error_list as $value) {
                foreach ($value as $key => $value) {
                    $out .= "{$key} => {$value} | ";
                }
            }
            $this->error_list = substr($out, 0, strlen($out) - 3);
        }
    }


    protected function max(array $data)
    {
        $this->validateHeader($data, 'validateMaximunValue');
        $error_list = [];
        foreach ($data as $key => $value) {
            if (key_exists($key, $data[1]) && (intval($value) > intval($data[1][$key]))) {
                array_push($error_list, [$key => "exp: {$data[1][$key]} - rec: {$value}"]);
            }
        }
        if (count($error_list) > 0) {
            $out = 'Maximun value error on field(s) ';
            foreach ($error_list as $value) {
                foreach ($value as $key => $value) {
                    $out .= "{$key} => {$value} | ";
                }
            }
            $this->error_list = substr($out, 0, strlen($out) - 3);
        }
    }

    private function validateHeader($data, $name)
    {
        if (!$data[0] instanceof \stdClass || !is_array($data[1])) {
            throw new \Exception("Incorrect input param type for {$name}");
        }
    }

}