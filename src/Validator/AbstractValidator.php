<?php
namespace Frobou\Validator;

use Frobou\Utils\Functions;
use Frobou\Validator;

/**
 * Class AbstractValidator
 * @package Frobou\Validator
 */
abstract class AbstractValidator
{

    /* ---------------------------------------------------------------------- */
    /* GENERIC VALIDATORS */
    /* ---------------------------------------------------------------------- */

    /**
     * jsonValidateStructure(jsonObject, ['username', 'firstname'])
     *
     * @param type $json
     * @param array $header
     * @param array $optional
     * @return array|bool
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
     *
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
     * @param array $header
     *            ['key']
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
            return [
                $json => $header
            ];
        }
        return true;
    }

    /**
     * jsonValidateMaxLength(jsonObject, ['username' => 10, 'firstname' => 8])
     *
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
                array_push($a, [
                    $key => "exp: {$r} - rec: {$e}"
                ]);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    /**
     * jsonValidateMinLength(jsonObject, ['username' => 5, 'firstname' => 8])
     *
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
                array_push($a, [
                    $key => "exp: {$r} - rec: {$e}"
                ]);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    /**
     * jsonValidateMin(jsonObject, ['username' => 5, 'firstname' => 8])
     *
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
                array_push($a, [
                    $key => "exp: {$r} - rec: {$e}"
                ]);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    /**
     * jsonValidateMax(jsonObject, ['username' => 5, 'firstname' => 8])
     *
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
                array_push($a, [
                    $key => "exp: {$r} - rec: {$e}"
                ]);
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    /* ---------------------------------------------------------------------- */
    /* SPECIAL VALIDATORS */
    /* ---------------------------------------------------------------------- */
    public function jsonValidateIp($json, $header)
    {
        $a = [];
        foreach ($json as $key => $value) {
            if (in_array($key, $header)) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        if (validaIp($v, 4, false) !== true) {
                            array_push($a, [
                                $key => $v
                            ]);
                        }
                    }
                } else {
                    if (Functions::validateIpAddress($value, 4, false) !== true) {
                        array_push($a, [
                            $key => $value
                        ]);
                    }
                }
            }
        }
        if (count($a) > 0) {
            return $a;
        }
        return true;
    }

    public function jsonValidateDate($json, $header)
    {
        foreach ($json as $key => $value) {
            if (in_array($key, $header) && strtotime($value) === false) {
                return false;
            }
        }
        return true;
    }
}
