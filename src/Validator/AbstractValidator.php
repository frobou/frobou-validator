<?php

namespace Frobou\Validator;

use Ispti\Utils\Functions;

abstract class AbstractValidator {

    /* ---------------------------------------------------------------------- */
    /* GENERIC VALIDATORS                                                     */
    /* ---------------------------------------------------------------------- */

    /**
     * jsonValidateStructure(jsonObject, ['username', 'firstname'])
     * @param type $json
     * @param array $header
     * @return boolean|array
     */
    public function jsonValidateStructure($json, $header, $optional = [])
    {
        $a['not_found'] = [];
        $a['not_allowed'] = [];
        $has = false;
        foreach ($header as $value) {
            if (!isset($json->{$value})) {
                $has = true;
                array_push($a['not_found'], $value);
            }
        }
        foreach ($json as $key => $value) {
            if (!in_array($key, $header)) {
                if (!in_array($key, $optional)) {
                    $has = true;
                    array_push($a['not_allowed'], $key);
                }
            }
        }
        if ($has === true) {
            return $a;
        }
        return true;
    }

    /**
     * jsonValidateRequired(jsonObject, ['username', 'firstname'])
     * @param type $json
     * @param array $header
     * @return boolean|array
     */
    public function jsonValidateRequired($json, $header)
    {
        $a = [];
        foreach ($json as $key => $value) {
            if (in_array($key, $header) && $value === '') {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    /**
     * 
     * @param type $json
     * @param array $header ['key']
     * @return boolean|array
     */
    public function jsonValidateInteger($json, $header)
    {
        $a = [];
        foreach ($json as $key => $value) {
            if (in_array($key, $header) && Functions::validateNumbersOnly($value, true) === false) {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    public function jsonValidateEmail($json, $header)
    {
        $a = [];
        foreach ($json as $key => $value) {
            if (in_array($key, $header) && Functions::validateEmail($value) === false) {
                array_push($a, $key);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    public function jsonValidateArray($json)
    {
        if (is_array($json) === false) {
            return false;
        }
        return true;
    }

    public function jsonValidateValues($json, $header, $values)
    {
        if (!in_array($json, $values)) {
            return [$json => $header];
        }
        return true;
    }

    /**
     * jsonValidateMaxLength(jsonObject, ['username' => 10, 'firstname' => 8])
     * @param type $json
     * @param array $header
     * @return boolean|array
     */
    public function jsonValidateMaxLength($json, $header)
    {
        $a = [];
        foreach ($json as $key => $value) {
            if (key_exists($key, $header) && (strlen($value) > $header[$key])) {
                $e = strlen($value);
                $r = $header[$key];
                array_push($a, [$key => "exp: {$r} - rec: {$e}"]);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    /**
     * jsonValidateMinLength(jsonObject, ['username' => 5, 'firstname' => 8])
     * @param type $json
     * @param array $header
     * @return boolean|array
     */
    public function jsonValidateMinLength($json, $header)
    {
        $a = [];
        foreach ($json as $key => $value) {
            if (key_exists($key, $header) && (strlen($value) < $header[$key])) {
                $e = strlen($value);
                $r = $header[$key];
                array_push($a, [$key => "exp: {$r} - rec: {$e}"]);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    /**
     * jsonValidateMin(jsonObject, ['username' => 5, 'firstname' => 8])
     * @param type $json
     * @param type $header
     * @return boolean|array
     */
    public function jsonValidateMin($json, $header)
    {
        $a = [];
        foreach ($json as $key => $value) {
            if (key_exists($key, $header) && (intval($value) < intval($header[$key]))) {
                $e = $value;
                $r = $header[$key];
                array_push($a, [$key => "exp: {$r} - rec: {$e}"]);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    /**
     * jsonValidateMax(jsonObject, ['username' => 5, 'firstname' => 8])
     * @param type $json
     * @param type $header
     * @return boolean|array
     */
    public function jsonValidateMax($json, $header)
    {
        $a = [];
        foreach ($json as $key => $value) {
            if (key_exists($key, $header) && (intval($value) > intval($header[$key]))) {
                $e = $value;
                $r = $header[$key];
                array_push($a, [$key => "exp: {$r} - rec: {$e}"]);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    /* ---------------------------------------------------------------------- */
    /* SPECIAL VALIDATORS                                                     */
    /* ---------------------------------------------------------------------- */

    public function jsonValidateIp($json, $header)
    {
        $a = [];
        foreach ($json as $key => $value) {
            if (in_array($key, $header)) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        if (validaIp($v, 4, false) !== true) {
                            array_push($a, [$key => $v]);
                        }
                    }
                } else {
                    if (Functions::validateIpAddress($value, 4, false) !== true) {
                        array_push($a, [$key => $value]);
                    }
                }
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }
    
    public function jsonValidateDate($json, $header){
        foreach ($json as $key => $value) {
            if (in_array($key, $header) && strtotime($value) === false) {
                return false;
            }
        }
        return true;
    }

    /* ---------------------------------------------------------------------- */
    /* ERROR MESSAGES                                                         */
    /* ---------------------------------------------------------------------- */

    public function incorrectJsonStructureMessage()
    {
        return 'Incorrect json structure';
    }
    
    public function incorrectDateErrorMessage()
    {
        return 'Incorrect Date';
    }

    public function incorrectJsonParamErrorMessage($param)
    {
        return "Incorrect parameter received on {$param}";
    }

    public function emptyValueErrorMessage($errors_found)
    {
        $out = 'Empty value error: field(s) ';
        foreach ($errors_found as $value) {
            $out .= "{$value}, ";
        }
        return substr($out, 0, strlen($out) - 2) . ' can not be empty';
    }

    public function numericTypeErrorMessage($errors_found)
    {
        $out = 'Numeric type error: field(s) ';
        foreach ($errors_found as $value) {
            $out .= "{$value}, ";
        }
        return substr($out, 0, strlen($out) - 2) . ' must be numeric';
    }

    public function emailErrorMessage($errors_found)
    {
        $out = 'Email error: field(s) ';
        foreach ($errors_found as $value) {
            $out .= "{$value}, ";
        }
        return substr($out, 0, strlen($out) - 2) . ' must be a valid email';
    }

    public function valuesErrorMessage($errors_found)
    {
        foreach ($errors_found as $key => $value) {
            return "Value {$key} is not allowed in field {$value}";
        }
    }

    public function maxLengthErrorMessage($error_found)
    {
        $out = 'Max length error on field ';
        foreach ($error_found as $v) {
            foreach ($v as $key => $value) {
                $out .= "{$key} => {$value} | ";
            }
        }
        return substr($out, 0, strlen($out) - 3);
    }

    public function minLengthErrorMessage($error_found)
    {
        $out = 'Min length error on field ';
        foreach ($error_found as $v) {
            foreach ($v as $key => $value) {
                $out .= "{$key} => {$value} | ";
            }
        }
        return substr($out, 0, strlen($out) - 3);
    }

    public function minErrorMessage($error_found)
    {
        $out = 'Min error on field ';
        foreach ($error_found as $v) {
            foreach ($v as $key => $value) {
                $out .= "{$key} => {$value} | ";
            }
        }
        return substr($out, 0, strlen($out) - 3);
    }

    public function maxErrorMessage($error_found)
    {
        $out = 'Max error on field ';
        foreach ($error_found as $v) {
            foreach ($v as $key => $value) {
                $out .= "{$key} => {$value} | ";
            }
        }
        return substr($out, 0, strlen($out) - 3);
    }

    public function numericRangeErrorMessage($more = '')
    {
        $out = 'Numeric range error';
        if ($more !== '') {
            $out .= ' - ' . $more;
        }
        return $out;
    }

    public function nullErrorMessage()
    {
        return 'There is no data to be returned';
    }

    public function existentValueErrorMessage()
    {
        return 'A set with these values already exists';
    }
    
    public function recusedValueErrorMessage()
    {
        return 'Can not insert this data';
    }

    public function inexistentValueErrorMessage()
    {
        return 'This set off data not exists';
    }

    public function existentHeaderErrorMessage()
    {
        return 'Header can not have value';
    }

    public function jsonHeaderErrorMessage($errors_found)
    {
        $out = 'Json header incorrect: ';
        if (CHANNEL !== 'DEV') {
            return substr($out, 0, strlen($out) - 2);
        }
        $allow = '';
        $found = '';
        foreach ($errors_found as $key => $value) {
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
        return substr($out, 0, strlen($out) - 3);
    }

    public function dbFalseErrorMessage()
    {
        return 'Data could not be inserted';
    }

    public function nothingChangedMessage()
    {
        return 'Nothing has changed';
    }

    public function incorrectIpErrorMessage($error_found)
    {
        $out = 'Incorrect IP(s) found: ';
        if (is_array($error_found)) {
            foreach ($error_found as $v) {
                foreach ($v as $key => $value) {
                    $out .= "{$value} | ";
                }
            }
            return substr($out, 0, strlen($out) - 3);
        } else {
            return $out .= $error_found;
        }
    }

    public function invalidArrayErrorMessage($error_found)
    {
        return "Incorrect Array(s) found on field {$error_found}";
    }

    public function accessToResourceWasDenied()
    {
        return 'Access to resource was denied';
    }

}
