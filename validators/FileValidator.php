<?php

/**
 * EndpointHelper File that validate and upload files 
 * php version 7.2.10
 *
 * @category Class
 * @package  Validaiton
 * @author   Pritesh Yadav <priteshyadav2015@gmail.com>
 * @license  GPL https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link     https://priteshyadav444.in
 */

namespace Validators;

/**
 * EndpointHelper Class that validate and upload files 
 *
 * @category Class
 * @package  Validaiton
 * @author   Pritesh Yadav <priteshyadav2015@gmail.com>
 * @license  GPL https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link     https://priteshyadav444.in
 */
class FileValidator
{
    private $_fileObject = array();
    private $_key = "";
    private $_targetDir = "";
    private $_errors = array();

    /**
     * Construtor Takes a $_FILES object and populate @input
     *
     * @param mixed $input      : $_FILES copy
     * @param mixed $_key       : key 
     * @param mixed $_targetDir : target directory to uplpoad file (Default : "uploads/")
     * 
     * @return void
     */
    public function __construct($input, $_key, $_targetDir = "uploads/")
    {
        if (count($input) < 0) {
            $this->_fileObject = [$_key => array("name" => "", "full_path" => "", "type" => "", "tmp_name" => "", "error" => 4, "size" => 0)];
        }
        $this->_fileObject = $input;
        $this->_key = $_key;
        $this->_targetDir = $_targetDir;
        $this->_errors = array();
    }
    /**
     * Check is file uploaded or not temporarily 
     *
     * @return bool 
     */
    public  function isFileUploaded(): bool
    {
        if (isset($this->_fileObject) == true
            && isset($this->_fileObject[$this->_key]['tmp_name']) == true
            && empty($this->_fileObject[$this->_key]['error'])
        ) {
            return true;
        }
        return false;
    }
    /**
     * Return file size in KB
     *
     * @return float
     */
    public  function getFileSize(): float
    {
        if ($this->isFileUploaded()) {
            return ($this->_fileObject[$this->_key]['size'] / 1024);
        }
        return 0;
    }
    /**
     * Return file mime type also known as media type
     *
     * @return bool : string
     */
    public  function getFileType(): bool|string
    {

        if ($this->isFileUploaded()) {
            $memeType = mime_content_type($this->_fileObject[$this->_key]['tmp_name']);
            // check uploaded file extention and meme type.
            if ($this->_getMemeType(
                pathinfo($this->_fileObject[$this->_key]['name'], PATHINFO_EXTENSION)
            ) != $memeType
            ) {
                array_push($this->_errors, $this->errorMessage("INVALID_FORMAT"));
            }
            return $memeType;
        }
        return false;
    }
    /**
     * Return path of temprory location of uploaded file
     *
     * @return string
     */
    public  function getTempFile(): string
    {
        if ($this->isFileUploaded()) {
            $tempFile = $_FILES[$this->_key]['tmp_name'];
            return $tempFile;
        }
        return false;
    }
    /**
     * Create a path for upload location
     *
     * @return string
     */
    public  function getTargetFile(): string
    {
        if ($this->isFileUploaded()) {
            $targetFile = basename($_FILES[$this->_key]['name']);
            return $targetFile;
        }
        return "/";
    }
    /**
     * It upload a file which was on instance 
     *
     * @return void
     */
    public function upload()
    {
        $tempFile = $this->getTempFile();
        $targetFile = $this->_targetDir . "/" . $this->getTargetFile();

        if (!file_exists($this->_targetDir)) {
            mkdir($this->_targetDir);
        }

        if (!move_uploaded_file($tempFile, $targetFile)) {
            return false;
        }

        return true;
    }
    /**
     * Check is there any error in file upload 
     *
     * @param mixed $return : pass true to get _errors in array format
     * 
     * @return bool|array
     */
    public function isError($return = false): bool|array
    {
        if ($return == true) {
            return $this->_errors;
        }

        if (empty($this->_errors)) {
            return false;
        } else {
            return true;
        }
    }
    /** 
     * Validate a file as per validation type and add a error in @param _errors 
     *
     * @param mixed $validationType : validation key 
     * 
     * @return void
     */
    public function validate($validationType)
    {
        if (strpos($validationType, ":") != false) {
            list($type, $info) = explode(":", $validationType);
            if (empty($info)) {
                return; // if info not specfied 
            }
        } else {
            $type = $validationType;
        }

        if ($type == "required") {
            if (!$this->isFileUploaded()) {
                array_push($this->_errors, $this->errorMessage(4));
            }
        }
        if ($type == "max"  && isset($info)) {
            if ($this->getFileSize() > $info) {
                array_push($this->_errors, $this->errorMessage(2, $info));
            }
        }
        if ($type == "filetype" && isset($info)) {
            if (!$this->checkMimeType($info)) {
                array_push(
                    $this->_errors,
                    $this->errorMessage("INVALID_FILE_FORMAT", $info)
                );
            }
        }
    }
    /**
     * Check  mime type same or not as passed file extension
     *
     * @param mixed $type : File Extention
     * 
     * @return void
     */
    public function checkMimeType($type)
    {
        $memeType = $this->_getMemeType($type);
        return ($this->getFileType() == $memeType);
    }
    /**
     * Map a file extention with mime type
     *
     * @param mixed $type : file extention
     * 
     * @return void
     */
    private function _getMemeType($type)
    {
        $memeType =  match ($type) {
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "json" => "application/json",
            "png" => "image/png",
            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "zip" => "application/zip",
            default => "application/octet-stream",
        };
        return $memeType;
    }
    /**
     * It return a error message as per mapped $errorCode
     *
     * @param mixed $errorCode : error code of Error Message
     * @param mixed $info      : othe meta information of error Code
     * 
     * @return void
     */
    public function errorMessage($errorCode, $info = "")
    {
        $message = match ($errorCode) {
            0 => "File is uploaded successfully",
            1 => "Uploaded file cross the limit.",
            2 => "Uploaded file cross the limit. $info KB",
            3 => "File is not uploaded properly",
            4 => "No file was uploaded.",
            6 => "Missing a temporary folder.",
            7 => "Failed to write file to disk.",
            8 => "A PHP extension stopped the uploading process.",
            "INVALID_FILE_FORMAT" => "File should be in $info format.",
            "INVALID_FORMAT" => "upload file mime type and extention mismatch",
            default => "Unexpected Error in File Uploading",
        };
        return $message;
    }
}
