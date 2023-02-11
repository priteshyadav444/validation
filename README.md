
# Php Validation Library
The library is inspired by Laravel validation and provides limited but similar functionality for form validation. It allows developers to define validation rules in a simple, human-readable format (e.g. "required|numeric") and quickly validate user input. This helps to ensure that the data entered into forms is accurate, consistent, and meets certain standards, improving the overall user experience and reducing the risk of errors in the application.


This library provides classes for validating different types of data inputs in PHP. It includes the following classes:
-   `Validate`: contains functions for validating individual inputs, such as strings, numbers, and passwords.
-   `FormValidator`: uses the Validate class to validate form inputs and return errors.
-   `FileValidator`: it validate and uploading files. it takes in a $_FILES object and a key to identify the file, and a target directory to upload the file.
-   `ErrorHandler`: returns error messages and it methods to handle errors for each particular form input.

## Features
- Easy to Use
- API like Laravel
- File handling
- Validation
- Error handling

## Requirements

-   PHP 7.0 or above.

## Usage - How to use in core Php Form Validation

### FormValidator Class
The FormValidator class uses the Validate class to validate form inputs and return errors using Error Class. 
How to Use : 

1) Include the FormValidator class in your code by using require or include statement. 
2) Create an instance of the FormValidator class and store it in a variable. 
3) Call the validate method on the instance of the FormValidator class and pass the form data and validation rules to it as arguments. 
4) The validate method will validate the form data based on the rules and return true if the data is valid, and false otherwise. 
5) If the validate method returns false, you can access the error messages using the errors method on the instance of the FormValidator class.

    ```
    $path = "../../validation/validators/FormValidator.php"; 
    indlude "$path";
    use Validators\{FormValidator, FileValidator};
    $obj = new FormValidator();
    
    if (isset($_POST['submit'])) {
    $validations = [
        'name' => 'string',
        'email' => 'email',
        'phone' => 'numeric|min:10|max:10',
        'docs' => 'required|filetype:txt'
    ];
    
    if (!$obj->validate($_POST, $validations)->isError()) {
        $file = new FileValidator($_FILES, "docs", "files");
        $file->upload();
        }
    }
    ```
  FormValidator class uses `ErrorHandler` Class to Handle Error :
  Using isErro() method and all() we can list errors :
 
    if ($obj->isError() != false) {
            echo '<div class="alert alert-danger" role="alert">';
            foreach ($obj->all() as $error)
                echo "<li>$error</li>";
            echo '</div>';
    }
   To populate old value which was valid we can use old() method. 
  ```
  <div class="mb-3">
	<label for="name" class="form-label">Name</label>
	<input type="text" name="name" value='<?php echo "{$obj->old('name')}"; ?>' class="form-control" id="name" >
 </div>
<div class="mb-3">
	<label for="email" class="form-label">Email</label>
	<input type="email" oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" name="email" value='<?php echo "{$obj->old('email')}"; ?>' class="form-control" id="email" aria-describedby="emailHelp">
</div>
<div class="mb-3">
	<label for="phone" class="form-label">Phone</label>
	<input type="text" name="phone" value='<?php echo "{$obj->old('phone')}"; ?>' class="form-control" id="phone">
</div>
  ```
	
### ErrorHandler Class
The ErrorHandle class provides functions to handle and store error messages. The class has functions to set error messages, return all error messages, check if errors exist, and handle error messages. 
1. `isError()` : method checks if there are any errors stored in the object's `$errors` array and returns a boolean value indicating the presence of errors. If a `$key` is passed as a parameter, it will check if there are any errors stored for that specific key in the `$errors` array and return a boolean value accordingly.
2. `all()` :  method returns an array of all error messages stored in the object's `$errors` array.
3. `old($key)`:  The `old` method is a function in a PHP class used to return old values if the data was valid. It takes a single parameter, `$key`, which is used to retrieve the value from an array called `oldValues`.

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
13. `senitizeInput` : method is a security measure used to sanitize user input. It performs the following operations in the given order:

	- Removes any whitespace from the beginning and end of the input string with `trim()`.
    
    - Strips backslashes (`\`) from the input string with `stripslashes()`.
    - Converts any special HTML characters in the input string to their corresponding HTML entities with `htmlspecialchars()`.
    

The purpose of these operations is to make sure that the input data is free from any malicious content, such as scripts, that could potentially harm the application. The method returns the sanitized input as the result. 


### Important Notice for Library Users
Please be aware that this library has not been fully tested and may contain bugs. If you encounter any issues while using this library, we kindly request that you report them in the Issues section of this repository. This will allow us to identify and resolve any problems as soon as possible.

Thank you for your understanding and cooperation.



