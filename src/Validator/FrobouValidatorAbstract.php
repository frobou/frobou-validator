<?php

namespace Frobou\Validator;

abstract class FrobouValidatorAbstract
{
    protected $error_list = [];

    private function validateHeader($data, $name)
    {
        if (!$data[0] instanceof \stdClass || !is_array($data[1])) {
            throw new \Exception("Incorrect input param type for {$name}");
        }
    }

    public function struct($data, $debug = false)
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
            $out = 'Incorrect data structure: ';
            if ($debug === true) {
                $allow = '';
                $found = '';
                foreach ($a as $key => $value) {
                    if ($key === 'not_found') {
                        foreach ($value as $v) {
                            $found .= "{$v}, ";
                        }
                    } else if ($key === 'not_allowed') {
                        foreach ($value as $v) {
                            $allow .= "{$v}, ";
                        }
                    }
                }
                if ($found !== '') {
                    $out .= 'field(s) ' . substr($found, 0, strlen($found) - 2) . ' not found - ';
                }
                if ($allow !== '') {
                    $out .= 'field(s) ' . substr($allow, 0, strlen($allow) - 2) . ' not allowed - ';
                }
                $this->error_list['struct'] = substr($out, 0, strlen($out) - 3);
            } else {
                $this->error_list['struct'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function required(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateRequired');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1]) && $value === '') {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            $out = 'Empty value error: ';
            if ($debug === true) {
                $out .= 'field(s) ';
                foreach ($a as $value) {
                    $out .= "{$value}, ";
                }
                $this->error_list['required'] = substr($out, 0, strlen($out) - 2) . ' can not be empty';
            } else {
                $this->error_list['required'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function integer(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateInteger');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1]) && FrobouValidation::validateNumbersOnly($value, true) === false) {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            $out = 'Integer value error: ';
            if ($debug === true) {
                $out .= 'field(s) ';
                foreach ($a as $value) {
                    $out .= "{$value}, ";
                }
                $this->error_list['integer'] = substr($out, 0, strlen($out) - 2) . ' must be integer';
            } else {
                $this->error_list['integer'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function email(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateEmail');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1]) && FrobouValidation::validateEmail($value) === false) {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            $out = 'Email value error: ';
            if ($debug === true) {
                $out .= 'field(s) ';
                foreach ($a as $value) {
                    $out .= "{$value}, ";
                }
                $this->error_list['email'] = substr($out, 0, strlen($out) - 2) . ' must be an email';
            } else {
                $this->error_list['email'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function values(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateValues');
        if (count($data[1]) !== 1) {
            $this->error_list['values'] = 'Only one value per time';
            return false;
        }
        if (!in_array($data[0]->{$data[1][0]}, $data[2])) {
            $out = 'Expected value error: ';
            if ($debug === true) {
                $a = '';
                foreach ($data[2] as $value) {
                    $a .= "{$value}, ";
                }
                $this->error_list['values'] = $out . $data[1][0] . ' value must be [' . substr($a, 0, strlen($a) - 2) . ']';
            } else {
                $this->error_list['values'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    protected function min(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateMinimumValue');
        $error_list = [];
        foreach ($data[0] as $key => $value) {
            if (key_exists($key, $data[1]) && (intval($value) < intval($data[1][$key]))) {
                array_push($error_list, [$key => "exp: {$data[1][$key]} - rec: {$value}"]);
            }
        }
        if (count($error_list) > 0) {
            $out = 'Minimum value error: ';
            if ($debug === true) {
                foreach ($error_list as $value) {
                    foreach ($value as $key => $value) {
                        $out .= "{$key} => {$value} | ";
                    }
                }
                $this->error_list['min'] = substr($out, 0, strlen($out) - 3);
            } else {
                $this->error_list['min'] = substr($out, 0, strlen($out) - 2);
            }
            return false;
        }
        return true;
    }

    protected function max(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateMaximunValue');
        $error_list = [];
        foreach ($data[0] as $key => $value) {
            if (key_exists($key, $data[1]) && (intval($value) > intval($data[1][$key]))) {
                array_push($error_list, [$key => "exp: {$data[1][$key]} - rec: {$value}"]);
            }
        }
        if (count($error_list) > 0) {
            $out = 'Maximun value error:';
            if ($debug === true) {
                foreach ($error_list as $value) {
                    foreach ($value as $key => $value) {
                        $out .= "{$key} => {$value} | ";
                    }
                }
                $this->error_list['max'] = substr($out, 0, strlen($out) - 3);
            } else {
                $this->error_list['max'] = substr($out, 0, strlen($out) - 2);
            }
            return false;
        }
        return true;
    }

    public function maxlen(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateMaxLen');
        $a = '';
        $has = false;
        foreach ($data[0] as $key => $value) {
            if (key_exists($key, $data[1]) && (strlen($value) > $data[1][$key])) {
                $has = true;
                $a .= $key . ' exp: ' . $data[1][$key] . ' - rec: ' . strlen($value) . ', ';
            }
        }
        if ($has === true) {
            $out = 'Max length value error: ';
            if ($debug === true) {
                $this->error_list['maxlen'] = $out . substr($a, 0, strlen($a) - 2);
            } else {
                $this->error_list['maxlen'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function minlen(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateMinLen');
        $a = '';
        $has = false;
        foreach ($data[0] as $key => $value) {
            if (key_exists($key, $data[1]) && (strlen($value) < $data[1][$key])) {
                $has = true;
                $a .= $key . ' exp: ' . $data[1][$key] . ' - rec: ' . strlen($value) . ', ';
            }
        }
        if ($has === true) {
            $out = 'Min length value error: ';
            if ($debug === true) {
                $this->error_list['minlen'] = $out . substr($a, 0, strlen($a) - 2);
            } else {
                $this->error_list['minlen'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function ip(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateIp');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1])) {
                if (FrobouValidation::validateIpAddress($value, 4, true) !== true) {
                    array_push($a, [$key => $value]);
                }
            }
        }
        if (count($a) > 0) {
            $out = 'Incorrect Ip: ';
            if ($debug === true) {
                $out .= 'Field(s) [';
                foreach ($a as $v) {
                    $out .= key($v) . ', ';
                }
                $this->error_list['ip'] = substr($out, 0, strlen($out) - 2) . '] not contains a valid IP';
            } else {
                $this->error_list['ip'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function date_en(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateDateEn');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1]) && strtotime($value) === false) {
                array_push($a, [$key => $value]);
            }
            if (count($a) > 0) {
                $out = 'Incorrect Date: ';
                if ($debug === true) {
                    $out .= 'Field(s) [';
                    foreach ($a as $v) {
                        $out .= key($v) . ', ';
                    }
                    $this->error_list['date_en'] = substr($out, 0, strlen($out) - 2) . '] not contains a valid date';
                } else {
                    $this->error_list['date_en'] = substr($out, 0, strlen($out) - 1);
                }
                return false;
            }
            return true;
        }
    }

    /* validator de after */
    public function validateError($data)
    {
        if (isset($data)) {
            $this->error_list['error'] = ['Type' => 'ValidateError', 'ErrorCode' => $data[1], 'Message' => $data[2]];
        }
        return true;
    }

    public function validateNotfound($data)
    {
        if (isset($data) && count($data) == 0) {
            $this->error_list['error'] = ['Type' => 'ValidateError', 'ErrorCode' => 1101, 'Message' => 'No data found'];
        }
        return true;
    }

    public function validateExists($data)
    {
        if (isset($data) && (is_array($data) && count($data) > 0)) {
            $this->error_list['error'] = ['Type' => 'ValidateError', 'ErrorCode' => 1102, 'Message' => 'A set with these values already exists'];
        }
        return true;
    }

    public function validateIsnull($data)
    {
        if (isset($data) && !is_null($data) && (is_array($data) && (count($data) > 0))) {
            $this->error_list['error'] = ['Type' => 'ValidateError', 'ErrorCode' => 1103, 'Message' => 'Data found'];
        }
        return true;
    }

}