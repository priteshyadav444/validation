# Php Validation Library
The library is inspired by Laravel validation and provides limited but similar functionality for form validation. It allows developers to define validation rules in a simple, human-readable format (e.g. "required|numeric") and quickly validate user input. This helps to ensure that the data entered into forms is accurate, consistent, and meets certain standards, improving the overall user experience and reducing the risk of errors in the application.


This library provides classes for validating different types of data inputs in PHP. It includes the following classes:
-   `Validate`: contains functions for validating individual inputs, such as strings, numbers, and passwords.
-   `FormValidator`: uses the Validate class to validate form inputs and return errors.
-   `ErrorHandler`: returns error messages and it methods to handle errors for each particular form input.
## Requirements

-   PHP 7.0 or above.

## Usage

### FormValidator Class
The FormValidator class uses the Validate class to validate form inputs and return errors using Error Class. 
    $this->obj= new  Validate();
 FormValidator Class uses Error Class to Handle Error

 1. `validate()`: This method takes a reference to the data to be
    sanitized and the validation keys as input. It then iterates through
    all the validation keys, sanitizes the data.
   
 2. `old($key)`:  The `old` method is a function in a PHP class used to return old values if the data was valid. It takes a single parameter, `$key`, which is used to retrieve the value from an array called `oldValues`.

### ErrorHandler Class
The ErrorHandle class provides functions to handle and store error messages. The class has functions to set error messages, return all error messages, check if errors exist, and handle error messages. 
-   The `all()` method returns an array of all error messages stored in the object's `$errors` array.
-   The `isError()` method checks if there are any errors stored in the object's `$errors` array and returns a boolean value indicating the presence of errors. If a `$key` is passed as a parameter, it will check if there are any errors stored for that specific key in the `$errors` array and return a boolean value accordingly.

### Validate Class
This is a PHP class named "Validate" that provides functions to validate different data types (integer, float, string, email, password) and also functions to get different data types (integer, string, length). The class also includes error handling functionality through inheritance of the "ErrorHandler" class.
Following method :

1.  `isInt`: Validates whether the input is an integer or not.
    
2.  `isfloat`: Validates whether the input is a floating-point number or not.
    
3.  `isString`: Validates whether the input is a string or not.
    
4.  `isEmail`: Validates whether the input is a valid email address or not.
    
5.  `getInt`: Returns the input as an integer, floating-point number or boolean (false) if it is not valid.
    
6.  `getString`: Returns the input as a string or boolean (false) if it is not a valid string.
    
7.  `getLength`: Returns the length of the input.
    
8.  `checkMinimum`: Checks if the length of the input is greater than or equal to the specified length.
    
9.  `checkMaximum`: Checks if the length of the input is less than or equal to the specified length.
    
10.  `checkPassword`: Validates the input as a password according to the specified regular expression.
    
11.  `extractInteger`: Extracts only the integer part from the input.
    
12.  `extractFloat`: Extracts only the floating-point number part from the input. 
