<?php

namespace Frobou\Validator;

class FrobouValidator extends FrobouValidatorAbstract
{

    public function validate(array $types, array $data, $debug = false)
    {
        if (count($types) === 0) {
            throw new \Exception('Validation not found');
        }
        foreach ($types as $value) {
            if (!isset($data[$value])) {
                throw new \Exception("Data not found in {$value}");
            }
            $this->{$value}($data[$value], $debug);
        }
        if (count($this->error_list) > 0) {
            if (isset($this->logger)) {
                $this->logger->warning('ACCESS', ['Cause' => $this->error_list]);
            }
            return $this->error_list;
        }
        return true;
    }

    public function validateAfter($data)
    {
        if (!isset($data) || !is_array($data)) {
            throw new \Exception('Validation data not found');
        }
        foreach ($data as $key => $value) {
            if (ValidatorTypes::getKey($key) === false) {
                return false;
            }
            $method = 'validate' . ucfirst(strtolower(ValidatorTypes::getKey($key)));
            $this->{$method}($value);
        }
        if (count($this->error_list) > 0) {
            if (isset($this->logger)) {
                $this->logger->warning('ACCESS', ['Cause' => $this->error_list]);
            }
            return $this->error_list;
        }
        return true;
    }

    public function shift($origin, $desttination)
    {
        $result = new \stdClass();
        foreach ($origin as $key => $value) {
            if (isset($desttination[$key])) {
                $result->{$desttination[$key]} = $value;
                continue;
            }
            $result->$key = $value;
        }
        return $result;
    }

}