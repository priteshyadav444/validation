<?php

namespace ValidateClass;

include 'Helper/Helper.php';
/**
 * Validate Class 
 * @author Pritesh Yadav (priteshyadav2015@gmail.com)
 * @link https://priteshyadav444.in
 */
class Validate extends \ErrorHandler
{
    /**
     * isInt : Validate input is int or not 
     *
     * @param  mixed $input
     * @return bool
     */
    public static function isInt($input): bool
    {
        $intValue = intval($input);
        return ($input == $intValue);
    }
    /**
     * isfloat : Validate input is flaot or not 
     *
     * @param  mixed $input
     * @return bool {@see isInt()}
     */
    public static function isfloat($input): bool
    {
        $floatval = (float)$input;
        if (strpos($input, '.') == false)
            if (self::isInt($input)) return false;
        return ($floatval == $input);
    }
    /**
     * isString : Validate input is string or not 
     *
     * @param  mixed $input
     * @return bool
     */
    public static function isString($input): bool
    {
        if (strlen($input) == 0) return false;
        if (Validate::isInt($input)) return false;
        return (preg_match("/^[a-zA-Z]{3,}( {1,2}[a-zA-Z]{3,}){0,}$/", $input) == 1);
    }
    /**
     * isEmail : Validate wether $input is email or not
     *
     * @param  mixed $input
     * @return bool
     */
    public static function isEmail($input): bool
    {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) return false;
        return true;
    }
    /**
     * getInt :  Return integer, float or bool when invalid data 
     *
     * @param  mixed $input
     * @return int | float | bool
     */
    public static function getInt($input): int | float | bool
    {
        if (self::isInt($input)) {
            return intval($input);
        } elseif (self::isfloat($input)) {
            return floatval($input);
        }
        return false;
    }
    /**
     * getString : Return string value or boolen (false) when invalid data
     *
     * @param  mixed $input
     * @return string | bool
     */
    public static function getString($input): string | bool
    {
        if (self::isString($input) && !empty($input) && !self::isInt($input)) {
            $stringValue = (string)($input);
            return $stringValue;
        }
        return false;
    }
    /**
     * getLength : get length of length
     *
     * @param  mixed $input
     * @return void
     */
    public static function getLength($input)
    {
        if (empty($input)) return 0;
        return strlen($input);
    }
    /**
     * checkMinimum : Check minimum length of $input 
     *
     * @param  mixed $input
     * @param  mixed $length
     * @return bool
     */
    public static function checkMinimum($input, $length): bool
    {
        return (self::getLength($input) >= $length);
    }
    /**
     * checkMaximum : check maximum length
     *
     * @param  mixed $input
     * @param  mixed $length
     * @return bool
     */
    public static function checkMaximum($input, $length): bool
    {
        return (self::getLength($input) <= $length);
    }
    /**
     * checkPassword : validate $input as per passed regex
     *
     * @param  mixed $input
     * @return bool
     */
    public static function checkPassword($input): bool
    {
        if (strlen($input) == 0) return false;
        return (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $input) == 1);
    }
    /**
     * extractInteger: Extract only int from the the input
     *
     * @param  mixed $input
     * @return int | float
     */
    public static function extractInteger($input): int | float
    {
        // if input is is already integer
        if ($input == null) return false;
        if (self::isInt($input)) return $input;
        $result = 0;
        for ($i = 0; $i < strlen($input); $i++) {
            // Validate for floating value
            // if (isset($input[$i]) && $input[$i] == ".")
            //     break;

            if (isset($input[$i]) && self::isInt($input[$i])) {
                $result =  $result * 10 + self::getInt($input[$i]);
            }
        }
        return $result;
    }
    /**
     * extractString : extract only string charecter from the the input
     *
     * @param  mixed $input
     * @return void
     */
    public function extractString($input)
    {
        if ($input == null) return false;
        if (self::isInt($input)) return false;

        $result = "";
        for ($i = 0; $i < strlen($input); $i++) {
            $char = $input[$i];
            if (is_string($char) && $char != '.') {
                $result .= $input[$i];
            }
        }
        return $result;
    }
    /**
     * handleInput : helper function of validateInput Validate pass datattypes untill data match to $dataTypes
     *
     * @param  mixed $functionName : particular function for Validateing datatypes
     * @param  mixed $input : nput data 
     * @param  mixed $dataTypes : Expected datatypes
     * @return void
     */
    private function handleInput($functionName, $input, $dataTypes)
    {
        $result = Validate::$functionName($input);
        do {
            if ($result === false) {
                // ErrorHandler::__displayError($dataTypes);
                \ErrorHandler::errorHandler('INVALID_DATATYPE', $dataTypes);
                $result = Validate::$functionName(readline("Enter Input Again :\n"));
            } else {
                break;
            }
        } while (true);
        return $result;
    }
    /**
     * validateInput : validate input unless pass datatypes doest match on console application
     *
     * @param  mixed $input : passed data
     * @param  mixed $dataTypes : pass datatypes that  expected 
     * @return void
     */
    /**
     * validateInput 
     *
     * @param  mixed $input
     * @param  mixed $dataTypes
     * @return void
     */
    public function validateInput($input, $dataTypes)
    {
        if (isset($input) && ($dataTypes == 'int' || $dataTypes == 'string')) {
            $functionName = "get" . ucfirst($dataTypes);
            $result = self::handleInput($functionName, $input, $dataTypes);
            return $result;
        }
    }

    /**
     * echoit
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
    /**
     * stringLower
     *
     * @param  mixed $string1
     * @return bool
     */
    public function stringLower($string1): bool
    {
        return (strtolower($string1) == $string1);
    }
    /**
     * senitizeInput
     *
     * @param  mixed $data
     * @return void
     */
    public function senitizeInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    /**
     * isEmpty
     *
     * @param  mixed $input
     * @return bool
     */
    public static function isEmpty($input): bool
    {
        return (strlen($input) == 0);
    }
    /**
     * checkPinCode : validaiton of indian pincode
     *
     * @param  mixed $input
     * @return mixed
     */
    public static function checkPinCode($input): mixed
    {
        $obj = new \IndianPincode();
        return $obj->validate($input);
    }
}
