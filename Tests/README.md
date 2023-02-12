
# Test Cases for Each Validation Type:
We would like to inform you that this library has not been thoroughly tested and may contain bugs. If you encounter any issues while using the library, please report them in the issues section.

Additionally, PHP Unit Tests have been added for each validation type. To ensure the library works as expected, we highly recommend testing the library using the tests located in the 'Tests' folder. These tests will give you a better understanding of the functionality and reliability of the library."

Currently Testes Test Cases. 

1) Required:

- Empty input
- Input with a value

2)Numeric:

- Empty input
- Input as string
- Input with special characters
- Alphanumeric input
- Input as a number

3)Decimal:

- Empty input
- Input as string
- Input with special characters
- Alphanumeric input
- Input as a decimal number (12.00)

4)String:

- Empty input
- Input as a number
- Input with special characters
- Alphanumeric input
- Input as a string
- Input as full name

5)Email:

- Empty input
- Input as a number
- Input as a string
- Alphanumeric input
- Valid email input

6)Minimum:

- Input less than the minimum value
- Input equal to the minimum value
- Input as a negative value
- Input greater than the minimum value
- Input as the minimum value
7)Maximum:

- Input less than the maximum value
- Input equal to the maximum value
- Input as a negative value
- Input greater than the maximum value
- Input as the maximum value

8)Password:

- Invalid format input
- Valid format input

9)Confirm Password:

- Mismatch with the password input
- Pincode:
- Input as a string
- Numeric input with less than 6 characters
- Numeric input with more than 6 characters
- Non-existent pincode input
- Existing pincode input
10)File Upload:

Validation Types Supported:

Required
- Maximum file size (max:12)
- File type (filetype:pdf)
Currently Supported File Types:

  ```
  "jpg" => "image/jpeg"
  "jpeg" => "image/jpeg"
   "json" => "application/json"
  "png" => "image/png"
  "pdf" => "application/pdf"
  "txt" => "text/plain"
  "zip" => "application/zip"
  Default => "application/octet-stream"
  Note: If Confirm Password is processed prior to Password, the keys will be considered as Password.
  ```

