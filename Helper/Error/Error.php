<?php

/**
 * ErrorHandler
 */
class ErrorHandler
{
    /**
     * errors
     *
     * @var array
     */
    private $errors = array();

    /**
     * setError for particular key in
     *
     * @param  mixed $newError : Error Message 
     * @param  mixed $key : p
     * @return void
     */
    public function setError($newError, $key)
    {
        if (!empty($key)) {
            if (isset($this->errors[$key]) && is_array($this->errors[$key])) {
                array_push($this->errors[$key], $newError);
            } else {
                $this->errors[$key]  = array($newError);
            }
        }
    }

    /**
     * return array of all errors
     *
     * @return array
     */
    public function all(): array
    {
        $allErrors = array();
        foreach ($this->errors as $keyError) {
            foreach ($keyError as $error)
                array_push($allErrors, $error); {
            }
        }
        return $allErrors;
    }
    /**
     * return if errors present for all keys as well as particular key
     *
     * @param  mixed $key : to check for partucular key is errors present or not
     * @return bool
     */
    public function isError($key = null): bool
    {
        if (empty($this->errors)) return false;

        if ($key == null) {
            return count($this->errors) > 0;
        } else {
            if (isset($this->errors[$key]))
                return count($this->errors[$key]) > 0;
        }
        return false;
    }

    /**
     * errorHandler : display error message as per mapped Error code
     *
     * @param  mixed $errorCode : error Code of Error Message
     * @param  mixed $dataTypes
     * @param  mixed $key
     * @param  mixed $return
     * @param  mixed $meta
     * @return string Error Message
     */
    public function errorHandler($errorCode, $dataTypes = null, $key = "", $return = false, $meta = null)
    {
        $errorMessage = match ($errorCode) {
            'INVALID_DATATYPE' => "Enter Valid Data Expected $dataTypes",
            'INVALID_DATATYPE_INT' => "$key must be $dataTypes",
            'INVALID_DATATYPE_STRING' => "$key must be $dataTypes",
            'INVALID_DATATYPE_DECIMAL' => "$key must be $dataTypes",
            'INVALID_DATATYPE_EMAIL' => "$key must be $dataTypes",
            'INVALID_OPTION' => 'Enter option is In Valid',
            'NO_DATA_FOUND' => 'No Record Found!!',
            'DATABASE_EMPTY' => 'Dataset is Empty!!!!',
            'FIELD_REQUIRED' => "$key field required",
            'MINIMUM_LENGTH_REQUIRED' => "$key minimum length is $meta",
            'MAXIMUM_LENGTH_REQUIRED' => "$key maximum length is $meta",
            'INVALID_PASSWORD_FORMAT' => "invalid password format </li>
             <li> password required eight characters,</li>
             <li> password Required at least one uppercase letter </li> 
             <li> password Required one lowercase letter</li>
             <li> password required one number </li>
             <li> password required one special character",
            'PASSWORD_MISMATCH' => "password mismatch",
            default => "Unexpected Validation Error",
        };
        if ($return == true) {
            return $errorMessage;
        } else {
            if ($errorCode == 'INVALID_DATATYPE' && $dataTypes != null) {
                self::__displayError($errorMessage, $dataTypes);
                return;
            }
            self::__displayError($errorMessage);
        }
    }
    /**
     * getErrorMessage : return Array Message 
     *
     * @param  mixed $validationType
     * @param  mixed $key
     * @param  mixed $dataTypes
     * @param  mixed $meta : Extra information helper in error message
     * @return void
     */
    public function getErrorMessage($validationType, $key, $dataTypes = null, $meta = null)
    {
        $errorCode = match ($validationType) {
            'CHECK_DATA_INT' => 'INVALID_DATATYPE_INT',
            'CHECK_DATA_STRING' => 'INVALID_DATATYPE_STRING',
            'CHECK_DATA_DECIMAL', 'INVALID_DATATYPE_DECIMAL',
            'CHECK_DATA_EMAIL' => 'INVALID_DATATYPE_EMAIL',
            'FIELD_REQUIRED' => 'FIELD_REQUIRED',
            'CHECK_MINIMUM' => 'MINIMUM_LENGTH_REQUIRED',
            'CHECK_MAXIMUM' => 'MAXIMUM_LENGTH_REQUIRED',
            'CHECK_PASSWORD' => 'INVALID_PASSWORD_FORMAT',
            'CHECK_CONFORM_PASSWORD' => 'PASSWORD_MISMATCH',
            default => "UNEXPECTED_VALIDATION_CODE"
        };
        return $this->errorHandler($errorCode, $dataTypes, $key, true, $meta);
    }
    // display error message with expected Data Types 
    // @params: $dataTypes : pass datatypes that  expected 
    // @return type : void
    private static function __displayError($message, $dataTypes = null)
    {
        $message = $dataTypes == null ? $message : $message . " Expected ($dataTypes)";
        self::echoit($message);
    }
    /**
     * echoit : Custom echo 
     *
     * @param  mixed $msg
     * @param  mixed $newLine
     * @return void
     */
    public static function echoit($msg, $newLine = 2)
    {
        if ($newLine < 1) {
            echo "$msg \n";
            return;
        }

        $temp = "";
        for ($i = 1; $i <= $newLine; $i++)
            $temp .= "\n";
        echo $msg . $temp;
    }
}
