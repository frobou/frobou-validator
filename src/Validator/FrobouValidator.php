<?php

namespace Frobou\Validator;

abstract class FrobouValidator
{

    use FrobouMessages; //trait

    /**
     * validateMinimumValue(jsonObject, ['username' => 5, 'firstname' => 8])
     *
     * @param \stdClass $source
     * @param array $val_list
     * @return boolean
     */
    public function validateMinimumValue($source, $val_list)
    {
        if (!is_object($source) || !is_array($val_list)){
            $this->minimum_value = 'Incorrect param type';
            return false;
        }
        $error_list = [];
        foreach ($source as $key => $value) {
            if (key_exists($key, $val_list) && (intval($value) < intval($val_list[$key]))) {
                array_push($error_list, [
                    $key => "exp: {$val_list[$key]} - rec: {$value}"
                ]);
            }
        }
        if (count($error_list) > 0) {
            $this->minimumValue($error_list);
            return false;
        }
        $this->minimum_value = 'OK';
        return true;
    }

    public function getMinimumValueMessage(){
        return $this->minimum_value;
    }
}