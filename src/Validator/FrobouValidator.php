<?php

namespace Frobou\Validator;

class FrobouValidator extends FrobouValidatorAbstract
{

    public function validate(array $types, array $data)
    {
        if (count($types) === 0){
            throw new \Exception('Validation not found');
        }
        foreach ($types as $value) {
            if (!isset($data[$value])){
                throw new \Exception("Data not found in {$value}");
            }
            $this->{$value}($data[$value]);
        }
        if (count($this->error_list) > 0) {
            return $this->error_list;
        }
        return true;
    }

}