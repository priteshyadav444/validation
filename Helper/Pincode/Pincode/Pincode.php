<?php

/**
 * PincodeData
 * @author Aadrah halder (hadrash04@gmail.com)
 */
class PincodeData
{
    public $error;  // Variable to store the error

    protected $pincode, $class_region, $class_state_UT, $class_gpo, $flag, $class_data;   // Variables to store and work on information

    protected static $region_array = ['Northen' => [1, 2], 'Western' => [3, 4], 'Southern' => [5, 6], 'Eastern' => [7, 8], 'APS' => [9]];   // Array to store region information

    protected static $UT_array = [
        'Dadra and Nagar Haveli,Daman and Diu' => [396], 'Goa' => [403], 'Puducherry' => [605],
        'Lakshadweep' => [682], 'Sikkim' => [737], 'Andaman and Nicobar Islands' => [744],
        'Manipur' => [795], 'Mizoram' => [796], 'Nagaland' => [797, 798], 'Tripura' => [799]
    ];

    protected static $state_array = [
        'Delhi' => [11], 'Chandigarh' => [16], 'Himachal Pradesh' => [17], 'Chhattisgarh' => [49],
        'Telangana' => [50], 'Assam' => [78], 'North Eastern' => [79], 'Haryana' => [12, 13],
        'Punjab' => [14, 15], 'Jammu and Kashmir,Ladakh' => [18, 19], 'Uttar Pradesh,Uttarakhand' => [20, 28],
        'Rajasthan' => [30, 34], 'Gujarat' => [36, 39], 'Maharashtra' => [40, 44], 'Madhya Pradesh' => [45, 49],
        'Andhra Pradesh' => [51, 53], 'Karnataka' => [56, 59], 'Tamil Nadu' => [60, 66], 'Kerala' => [67, 69],
        'West Bengal' => [70, 74], 'Odisha' => [75, 77], 'Bihar,Jharkand' => [80, 85], 'Army Postal Service (APS)' => [90, 99]
    ];  // Array to store circle information

    protected static $gpo_array = [
        'Dadra and Nagar Haveli,Daman and Diu' => [362520, 396193, 'Dadra/Diu'], 'Goa' => [403001, 403806, 'Panaji'],
        'Puducherry' => [362520, 396193, 'Pondicherry'], 'Lakshadweep' => [682551, 682559, 'Lakshadweep'],
        'Sikkim' => [737101, 737139, 'Gangtok'], 'Andaman and Nicobar Islands' => [744101, 744304, 'Nicobar'],
        'Manipur' => [795001, 795159, 'Imphal'], 'Mizoram' => [796001, 796901, 'Aizawl'],
        'Nagaland' => [797001, 798627, 'Kohima'], 'Tripura' => [799001, 799290, 'Agartala'], 'Delhi' => [110001, 110096, 'New Delhi'],
        'Chandigarh' => [160001, 160103, 'Chandigarh'], 'Himachal Pradesh' => [172001, 177601, 'Shimla'],
        'Chhattisgarh' => [490001, 497778, 'Raipur'], 'Telangana' => [500001, 509412, 'Hyderabad'],
        'Assam' => [781001, 788931, 'Guwahati'], 'North Eastern' => [790001, 794115, 'Agartala'],
        'Haryana' => [121001, 136156, 'Ambala'], 'Punjab' => [140001, 160104, 'Amritsar'],
        'Jammu and Kashmir,Ladakh' => [180001, 194404, 'Srinagar'],
        'Uttar Pradesh,Uttarakhand' => [201001, 285223, 'Lucknow/Dehradun'],
        'Rajasthan' => [301001, 345034, 'Jaipur'], 'Gujarat' => [360001, 396590, 'Ahmedabad'],
        'Maharashtra' => [400001, 445402, 'Mumbai'], 'Madhya Pradesh' => [450001, 488448, 'Indore'],
        'Andhra Pradesh' => [515001, 535594, 'Hyderabad'], 'Karnataka' => [560001, 591346, 'Bangalore'],
        'Tamil Nadu' => [600001, 643253, 'Chennai'], 'Kerala' => [670001, 695615, 'Thiruvanamthapuram'],
        'West Bengal' => [700001, 743711, 'Kolkata'], 'Odisha' => [751001, 770076, 'Bhubneswar'],
        'Bihar,Jharkand' => [800001, 855117, 'Patna/Ranchi'], 'Army Postal Service (APS)' => '-'
    ];
}
class Errors extends PincodeData
{   // Class for Errors
    protected function returnError($error_occured)
    { // Function returns the true if error is stored
        if ($this->error = match ($error_occured) {
            'start with 0' => 'Pincode must not start with 0',
            'invalid pincode' => 'Entered pincode is not in existance',
            'insufficient size' => 'Pincode must not be less than 6 digit',
            'size exceeded' => 'Pincode must not be more than 6 digit',
            'non numeric' => 'Pincode must be numeric',
            'invalid region' => 'Region does not exist, please enter valid pincode',
            'invalid circle' => 'State/UT does not exist, please enter valid pincode',
            default => ''
        }) {
            return true;
        } else {
            return false;
        }
    }

    public function matchError($error_occured)
    { // Function calls returnError and return 
        return $this->returnError($error_occured);
    }
}

class IndianPincode extends Errors
{ // Class for Indian pincode inheriting Errors class
    protected function returnDetails()
    {
        // Region detail
        foreach (self::$region_array as $key => $val) {
            foreach ($val as $value) {
                if ($value == $this->pincode[0]) {
                    $this->class_region = $key;
                    $this->flag = true;
                    break;
                } else {
                    $this->flag = false;
                }
            }
        }

        // State/UT detail
        foreach (self::$UT_array as $key => $val) {
            foreach ($val as $value) {
                if ($value == substr($this->pincode, 0, 3)) {
                    $this->class_state_UT = $key;
                    $this->flag = true;
                    goto GPO;
                } else {
                    $this->flag = false;
                }
            }
        }
        foreach (self::$state_array as $key => $val) {
            if (count($val) == 1) {
                if ($val[0] == substr($this->pincode, 0, 2)) {
                    $this->class_state_UT = $key;
                    $this->flag = true;
                    goto GPO;
                } else {
                    $this->flag = false;
                }
            } else {
                $range = range($val[0], $val[1]);

                foreach ($range as $value) {
                    if ($value == substr($this->pincode, 0, 2)) {
                        $this->class_state_UT = $key;
                        $this->flag = true;
                        goto GPO;
                    } else {
                        $this->flag = false;
                    }
                }
            }
        }

        // GPO detail

        // if ($this->class_state_UT == 'Army Postal Service (APS)') {
        //     $this->class_gpo = self::$gpo_array[$this->class_state_UT];
        //     $this->flag = true;
        //     goto result;
        // } else {
        //     if ($this->class_state_UT == "") {
        //         $this->flag = false;
        //         goto result;
        //     };
        //     $curr_gpo = self::$gpo_array[$this->class_state_UT];
        //     $range = range($curr_gpo[0], $curr_gpo[1]);
        //     if (array_search($this->pincode, $range)) {
        //         $this->class_gpo = $curr_gpo[2];
        //         $this->flag = true;
        //     } else {
        //         $this->flag = false;
        //     }
        // }
        GPO:
        result:
        if ($this->flag != false) {
            $this->class_data = [$this->class_region, $this->class_state_UT];
            return $this->class_data;
        } else {
            $this->matchError('invalid pincode');
            return false;
        }
    }

    protected function validatePincode()
    {
        if (is_numeric($this->pincode)) {
            if ($this->pincode[0] != 0) {
                if (!(strlen($this->pincode) < 6)) {
                    if (!(strlen($this->pincode) > 6)) {
                        if ($this->pincode[0] > 0 and $this->pincode[0] < 10) {
                            if (substr($this->pincode, 0, 2) > 10 and substr($this->pincode, 0, 2) < 100) {
                                return "valid Pincode";
                                // return $this->returnDetails();
                            } else {
                                $this->matchError('invalid circle');
                                return false;
                            }
                        } else {
                            $this->matchError('invalid region');
                            return false;
                        }
                    } else {
                        $this->matchError('size exceeded');
                        return false;
                    }
                } else {
                    $this->matchError('insufficient size');
                    return false;
                }
            } else {
                $this->matchError('start with 0');
                return false;
            }
        } else {
            $this->matchError('non numeric');
            return false;
        }
    }

    protected function savePincode($user_pincode)
    {
        $this->pincode = $user_pincode;
        return $this->validatePincode();
    }

    public function enterPincode($user_pincode)
    {
        return $this->savePincode($user_pincode);
    }

    public function validate($input)
    {
        $response = array('status' => "", 'message' => '');
        $data = $this->enterPincode($input);
        if ($data) {
            $info = ['Region' => $data[0], 'State/UT' =>  $data[1], 'GPO'];
            $response['status'] = true;
            $response['message'] = $info;
            $response = (object) $response;
            return $response;
        } else {
            $response['status'] = false;
            $response['message'] = $this->error;
            $response = (object) $response;
            return $response;
        }
    }
}
