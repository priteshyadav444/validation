<?php

namespace Form;

include "Validate.php";

use ValidateClass\Validate;

/**
 * FormValidator
 */
class FormValidator extends Validate
{
    /**
     * password
     *
     * @var undefined
     */
    private $password = null;
    private $oldValues = array();

    /**
     * validate : iterate through all passed validation keys afte senitizing data   
     * @method updateOldValues : call this fethod to  Update values 
     * @param  mixed $data : take refrences of data to sentize form data
     * @param  mixed $validations
     * @return object current object
     * 
     */
    public function validate(&$data, $validations): object
    {
        foreach ($validations as $key => $validation) {
            if (array_key_exists($key, $data)) {
                $data[$key] = Validate::senitizeInput($data[$key]);
                $this->validateKey($key, $data[$key], $validation);
            }
        }
        if ($this->isError()) $this->updateOldValues($data, $this->all());
        return $this;
    }
    /**
     * validateKey : iterrate though all the validation like by exploding | pipe assign
     *
     * @param  mixed $key
     * @param  mixed $value
     * @param  mixed $validations
     * @return void
     */
    private function validateKey($key, $value, $validations = "")
    {
        $validations = array_unique(explode("|", $validations)); // remove dublicate validation. 
        foreach ($validations as $validationType) {
            $validationCode = $this->getValidationType($validationType);
            if ($validationCode != false) {
                if ($validationCode == 'CHECK_MINIMUM' || $validationCode == 'CHECK_MAXIMUM') {
                    $meta = substr($validationType, 4,);
                    $this->performValidation($key, $value, $validationCode, $meta);
                } else {
                    $this->performValidation($key, $value, $validationCode);
                }
            }
        }
    }
    /**
     * performValidation as permapped $validationType
     *
     * @param  mixed $key
     * @param  mixed $value
     * @param  mixed $validationType
     * @param  mixed $meta
     * @return void
     */
    private function performValidation($key, $value, $validationType, $meta = null)
    {
        if ($validationType == "FIELD_REQUIRED") {
            if ($this->isEmpty($value) == true) {
                Validate::setError(Validate::getErrorMessage($validationType, $key), $key);
            }
        }
        if ($validationType == "CHECK_DATA_INT") {
            if (Validate::isInt($value) == false) {
                Validate::setError(Validate::getErrorMessage($validationType, $key, "number"), $key);
            }
        }
        if ($validationType == "CHECK_DATA_STRING") {
            if (Validate::isString($value) == false) {
                Validate::setError(Validate::getErrorMessage($validationType, $key, 'string'), $key);
            }
        }
        if ($validationType == "CHECK_DATA_DECIMAL") {
            if (Validate::isFloat($value) == false) {
                Validate::setError(Validate::getErrorMessage($validationType, $key, 'float'), $key);
            }
        }
        if ($validationType == "CHECK_DATA_EMAIL") {
            if (Validate::isEmail($value) == false) {
                Validate::setError(Validate::getErrorMessage($validationType, $key, 'email'), $key);
            }
        }
        if ($validationType == "CHECK_MINIMUM") {
            if (Validate::checkMinimum($value, $meta) == false) {
                Validate::setError(Validate::getErrorMessage($validationType, $key, '', $meta), $key);
            }
        }
        if ($validationType == "CHECK_MAXIMUM") {
            if (Validate::checkMaximum($value, $meta) == false) {
                Validate::setError(Validate::getErrorMessage($validationType, $key, '', $meta), $key);
            }
        }
        if ($validationType == "CHECK_PASSWORD") {
            if ((Validate::checkPassword($value)) == false) {
                $this->password = "";
                Validate::setError(Validate::getErrorMessage($validationType, $key, '', $meta), $key);
            } else {
                $this->password = $value;
            }
        }
        if ($validationType == "CHECK_CONFORM_PASSWORD") {
            if ($this->password != ($value)) {
                Validate::setError(Validate::getErrorMessage($validationType, $key, '', $meta), $key);
            }
        }
        if ($validationType == "CHECK_INDIAN_PINCODE") {
            $response = Validate::checkPinCode($value);
            if ($response->status == false) {
                Validate::setError(($response->message), $key);
            }
        }
    }
    /**
     * getValidationType : get Mapped Error Type forValidation    
     *
     * @param  mixed $input
     * @return void
     */
    private function getValidationType($input)
    {
        $minmax = substr($input, 0, 3);
        if ($minmax == "max" || $minmax == "min")
            $input = $minmax;
        if ($input == 'cpassword' and $this->password == null)
            $input = 'password';
        if ($input == 'password' and isset($this->password))
            $input = 'cpassword';

        $validationType = match ($input) {
            'required' => "FIELD_REQUIRED",
            'numeric' => "CHECK_DATA_INT",
            'string' => "CHECK_DATA_STRING",
            'decimal' => "CHECK_DATA_DECIMAL",
            'email' => "CHECK_DATA_EMAIL",
            'min' => "CHECK_MINIMUM",
            'max' => "CHECK_MAXIMUM",
            'password' => 'CHECK_PASSWORD',
            'cpassword' => 'CHECK_CONFORM_PASSWORD',
            'pincode' => 'CHECK_INDIAN_PINCODE',
            default => false
        };
        return $validationType;
    }
    private function updateOldValues($formData, $errors)
    {
        $this->oldValues = $formData;
        foreach (array_keys($this->oldValues) as $key) {
            if ($this->isError($key))
                unset($this->oldValues[$key]);
        }
    }
    /**
     * old : return old vales if data was valid 
     *
     * @param  mixed $key : 
     * @return string
     */
    public function old($key): string|int
    {
        if (!empty($this->oldValues[$key]))
            return $this->oldValues[$key];
        return "";
    }
}
