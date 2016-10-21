<?php

namespace Frobou\Validator;

abstract class FrobouValidator
{
    /**
     * $min_struct = $this->validateMinimumValue($data, ['start' => 1, 'qtty' => 1]);
     * @param stdClass $data
     * @param array $value_list
     * @return bool|string
     */
    public function validateMinimumValue(\stdClass $data, array $value_list)
    {
        if (!$data instanceof \stdClass || !is_array($value_list)){
            return 'Incorrect input param type';
        }
        $error_list = [];
        foreach ($data as $key => $value) {
            if (key_exists($key, $value_list) && (intval($value) < intval($value_list[$key]))) {
                array_push($error_list, [
                    $key => "exp: {$value_list[$key]} - rec: {$value}"
                ]);
            }
        }
        if (count($error_list) > 0) {
            $out = 'Minimum value error on field(s) ';
            foreach ($error_list as $v) {
                foreach ($v as $key => $value) {
                    $out .= "{$key} => {$value} | ";
                }
            }
            return substr($out, 0, strlen($out) - 3);
        }
        return true;
    }


    /**
     * $max_struct = $this->jsonValidateMax($data, ['start' => 1, 'qtde' => 1]);
     * @param stdClass $data
     * @param array $value_list
     * @return bool|string
     */
    public function validateMaximunValue(\stdClass $data, array $value_list)
    {
        if (!$data instanceof \stdClass || !is_array($value_list)){
            return 'Incorrect input param type';
        }
        $error_list = [];
        foreach ($data as $key => $value) {
            if (key_exists($key, $value_list) && (intval($value) > intval($value_list[$key]))) {
                array_push($error_list, [
                    $key => "exp: {$value_list[$key]} - rec: {$value}"
                ]);
            }
        }
        if (count($error_list) > 0) {
            $out = 'Maximun value error on field(s) ';
            foreach ($error_list as $v) {
                foreach ($v as $key => $value) {
                    $out .= "{$key} => {$value} | ";
                }
            }
            return substr($out, 0, strlen($out) - 3);
        }
        return true;
    }

}