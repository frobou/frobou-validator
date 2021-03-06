<?php

namespace Frobou\Validator;

use Monolog\Logger;

abstract class FrobouValidatorAbstract
{
    protected $error_list = [];
    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(Logger $logger = null)
    {
        $this->logger = $logger;
    }

    private function validateHeader($data, $name)
    {
        if (isset($data[0]) && (!$data[0] instanceof \stdClass || !is_array($data[1]))) {
            $mes = "Incorrect input param type for {$name}";
            if (isset($this->logger)) {
                $this->logger->warning('ACCESS', ['Cause' => $mes]);
            }
            throw new \Exception($mes);
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


    public function hour(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateHour');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1]) && FrobouValidation::validateHour($value) === false) {
                array_push($a, [$key => $value]);
            }
            if (count($a) > 0) {
                $out = 'Incorrect Hour: ';
                if ($debug === true) {
                    $out .= 'Field(s) [';
                    foreach ($a as $v) {
                        $out .= key($v) . ', ';
                    }
                    $this->error_list['hour'] = substr($out, 0, strlen($out) - 2) . '] not contains a valid hour';
                } else {
                    $this->error_list['hour'] = substr($out, 0, strlen($out) - 1);
                }
                return false;
            }
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

    public function string(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateString');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1]) && FrobouValidation::validateString($value, true) === false) {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            $out = 'String value error: ';
            if ($debug === true) {
                $out .= 'field(s) ';
                foreach ($a as $value) {
                    $out .= "{$value}, ";
                }
                $this->error_list['string'] = substr($out, 0, strlen($out) - 2) . ' must be string';
            } else {
                $this->error_list['string'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function boolean(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateBoolean');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1]) && FrobouValidation::validateBoolean($value) === false) {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            $out = 'Boolean value error: ';
            if ($debug === true) {
                $out .= 'field(s) ';
                foreach ($a as $value) {
                    $out .= "{$value}, ";
                }
                $this->error_list['string'] = substr($out, 0, strlen($out) - 2) . ' must be boolean';
            } else {
                $this->error_list['string'] = substr($out, 0, strlen($out) - 1);
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

    public function isObject(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateEmptyObject');
        $a = [];
        foreach ($data[0] as $key => $value) {
            $asdf = is_object($value);
            if (in_array($key, $data[1]) && !is_object($value)) {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            $out = 'isObject value error: ';
            if ($debug === true) {
                $out .= 'field(s) ';
                foreach ($a as $value) {
                    $out .= "{$value}, ";
                }
                $this->error_list['isObject'] = substr($out, 0, strlen($out) - 2) . ' must be an Object';
            } else {
                $this->error_list['isObject'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function emptyObject(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateEmptyObject');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (!empty((array)$value)) {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            $out = 'EmptyObject value error: ';
            if ($debug === true) {
                $out .= 'field(s) ';
                foreach ($a as $value) {
                    $out .= "{$value}, ";
                }
                $this->error_list['emptyObject'] = substr($out, 0, strlen($out) - 2) . ' must be an empty Object';
            } else {
                $this->error_list['emptyObject'] = substr($out, 0, strlen($out) - 1);
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
        $ok = null;
        if (count($data) > 0) {
            $this->validateHeader($data, 'validateValues');
            if (count($data[1]) !== count($data[2])) {
                $this->error_list['values'] = "Input count error";
                return false;
            }
            $a = ($data[0]);
            for ($i = 0; $i <= count($data[1]) - 1; $i++) {
                $field = $data[1][$i];
                $array = $data[2][$i];
                if (isset($data[0]->{$field}) && !in_array(strtoupper($data[0]->{$field}), $array)) {
                    $out = 'Expected value error: ';
                    if ($debug === true) {
                        $a = '';
                        foreach ($array as $value) {
                            $a .= "{$value}, ";
                        }
                        if (!isset($this->error_list['values'])) {
                            $this->error_list['values'] = [$out . $field . ' value must be [' . substr($a, 0,
                                    strlen($a) - 2) . ']'];
                        } else {
                            array_push($this->error_list['values'], $out . $field . ' value must be [' . substr($a, 0,
                                    strlen($a) - 2) . ']');
                        }
                    } else {
                        if (!isset($this->error_list['values'])) {
                            $this->error_list['values'] = [substr($out, 0, strlen($out) - 1)];
                        } else {
                            array_push($this->error_list['values'], substr($out, 0, strlen($out) - 1));
                        }
                    }
                    $ok = false;
                }
            }
            if ($ok === null) {
                $ok = true;
            }
        }
        return $ok;
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

    public function network(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateNetwork');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1])) {
                if (FrobouValidation::validateIpAddress($value, 3, true) !== true) {
                    array_push($a, [$key => $value]);
                }
            }
        }
        if (count($a) > 0) {
            $out = 'Incorrect Network: ';
            if ($debug === true) {
                $out .= 'Field(s) [';
                foreach ($a as $v) {
                    $out .= key($v) . ', ';
                }
                $this->error_list['network'] = substr($out, 0, strlen($out) - 2) . '] not contains a valid Network';
            } else {
                $this->error_list['network'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function isArrayData(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateIsArrayData');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1]) && (!is_array($value) || count($value) < 1)) {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            $out = 'isArrayData value error: ';
            if ($debug === true) {
                $out .= 'field(s) ';
                foreach ($a as $value) {
                    $out .= "{$value}, ";
                }
                $this->error_list['isArrayData'] = substr($out, 0, strlen($out) - 2) . ' must be an Array with data';
            } else {
                $this->error_list['isArrayData'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
    }

    public function domain(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateDomain');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1])) {
                if (FrobouValidation::validateDomain($value) !== true) {
                    array_push($a, [$key => $value]);
                }
            }
        }
        if (count($a) > 0) {
            $out = 'Incorrect Domain: ';
            if ($debug === true) {
                $out .= 'Field(s) [';
                foreach ($a as $v) {
                    $out .= key($v) . ', ';
                }
                $this->error_list['domain'] = substr($out, 0, strlen($out) - 2) . '] not contains a valid Domain';
            } else {
                $this->error_list['domain'] = substr($out, 0, strlen($out) - 1);
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
            if (in_array($key, $data[1])) {
                $dateUS = \DateTime::createFromFormat("Y-m-d", $value);
                if ($dateUS === false) {
                    array_push($a, [$key => $value]);
                }
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
        }
        return true;
    }

    public function date_time_en(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateDateTimeEn');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1])) {
                $dateUS = \DateTime::createFromFormat("Y-m-d h:i:s", $value);
                if ($dateUS === false) {
                    array_push($a, [$key => $value]);
                }
            }
            if (count($a) > 0) {
                $out = 'Incorrect Date: ';
                if ($debug === true) {
                    $out .= 'Field(s) [';
                    foreach ($a as $v) {
                        $out .= key($v) . ', ';
                    }
                    $this->error_list['date_time_en'] = substr($out, 0, strlen($out) - 2) . '] not contains a valid datetime';
                } else {
                    $this->error_list['date_time_en'] = substr($out, 0, strlen($out) - 1);
                }
                return false;
            }
        }
        return true;
    }

    public function date_br(array $data, $debug = false)
    {
        $this->validateHeader($data, 'validateDate');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1])) {
                try {
                    list($dia, $mes, $ano) = explode('/', $value);
                    if (checkdate($mes, $dia, $ano)) {
                        $dateUS = \DateTime::createFromFormat("d/m/Y", $value);
                        if ($dateUS === false) {
                            array_push($a, [$key => $value]);
                        }
                    } else {
                        array_push($a, [$key => $value]);
                    }
                } catch (\Exception $e) {
                    array_push($a, [$key => $value]);
                }
            }
            if (count($a) > 0) {
                $out = 'Incorrect Date: ';
                if ($debug === true) {
                    $out .= 'Field(s) [';
                    foreach ($a as $v) {
                        $out .= key($v) . ', ';
                    }
                    $this->error_list['date_br'] = substr($out, 0, strlen($out) - 2) . '] not contains a valid date';
                } else {
                    $this->error_list['date_br'] = substr($out, 0, strlen($out) - 1);
                }
                return false;
            }
        }
        return true;
    }

    public function void($data, $debug = false)
    {
        $this->validateHeader($data, 'validateVoid');
        $a = [];
        foreach ($data[0] as $key => $value) {
            if (in_array($key, $data[1])) {
                if (is_object($value)) {
                    if (count((array)$value) !== 0) {
                        array_push($a, [$key => $value]);
                    }
                } else if (is_array($value)) {
                    if (count($value) !== 0) {
                        array_push($a, [$key => $value]);
                    }
                } else if (strlen($value) !== 0) {
                    array_push($a, [$key => $value]);
                }
            }
        }
        if (count($a) > 0) {
            $out = 'Incorrect Void: ';
            if ($debug === true) {
                $out .= 'Field(s) [';
                foreach ($a as $v) {
                    $out .= key($v) . ', ';
                }
                $this->error_list['void'] = substr($out, 0, strlen($out) - 2) . '] not contains a valid empty value';
            } else {
                $this->error_list['void'] = substr($out, 0, strlen($out) - 1);
            }
            return false;
        }
        return true;
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
