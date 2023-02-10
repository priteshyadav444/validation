<?php

/**
 * FileUpload : class that validate and upload files 
 */
class FileUpload
{
    private $fileObject = array();
    private $key = "";
    private $targetDir = "";
    private $errors = array();

    /**
     * __construct : Construtor Takes a $_FILES object and populate @input
     *
     * @param  mixed $input : $_FILES copy
     * @param  mixed $key : key 
     * @param  mixed $targetDir : target directory to uplpoad file (Default : "uploads/")
     * @return void
     */
    public function __construct($input, $key, $targetDir = "uploads/")
    {
        if (count($input) < 0)
            $this->fileObject = [$key => array("name" => "", "full_path" => "", "type" => "", "tmp_name" => "", "error" => 4, "size" => 0)];
        $this->fileObject = $input;
        $this->key = $key;
        $this->targetDir = $targetDir;
        $this->errors = array();
    }
    /**
     * isFileUploaded : Check is file uploaded or not temporarily 
     *
     * @return bool 
     */
    public  function isFileUploaded(): bool
    {
        if (isset($this->fileObject) == true && isset($this->fileObject[$this->key]['tmp_name']) == true && empty($this->fileObject[$this->key]['error'])) {
            return true;
        }
        return false;
    }
    /**
     * getFileSize : Return file size in KB
     *
     * @return int
     */
    public  function getFileSize(): int
    {
        if ($this->isFileUploaded()) {
            return $this->fileObject[$this->key]['size'];
        }
        return 0;
    }
    /**
     * getFileType : return file mime type also known as media type
     *
     * @return bool : string
     */
    public  function getFileType(): bool|string
    {

        if ($this->isFileUploaded()) {
            $memeType = mime_content_type($this->fileObject[$this->key]['tmp_name']);
            // check passed file extention and meme type.
            if ($this->getMemeType(pathinfo($this->fileObject[$this->key]['name'], PATHINFO_EXTENSION)) != $memeType)
                array_push($this->errors, $this->errorMessage("INVALID_FORMAT"));
            return $memeType;
        }
        return false;
    }
    /**
     * getTempFile : return path of temprory location of uploaded file
     *
     * @return string
     */
    public  function getTempFile(): string
    {
        if ($this->isFileUploaded()) {
            $tempFile = $_FILES[$this->key]['tmp_name'];
            return $tempFile;
        }
        return false;
    }
    /**
     * getTargetFile : create a path for upload location
     *
     * @return string
     */
    public  function getTargetFile(): string
    {
        if ($this->isFileUploaded()) {
            $targetFile = basename($_FILES[$this->key]['name']);
            return $targetFile;
        }
        return "/";
    }
    /**
     * upload : it upload a file 
     *
     * @return void
     */
    public function upload()
    {
        $tempFile = $this->getTempFile();
        $targetFile = $this->targetDir . "/" . $this->getTargetFile();

        if (!file_exists($this->targetDir)) mkdir($this->targetDir);

        if (!move_uploaded_file($tempFile, $targetFile))
            return false;

        return true;
    }
    /**
     * isError :  check is there any error in file upload 
     *
     * @param  mixed $return : pass true to get errors in array format
     * @return bool|array
     */
    public function isError($return = false): bool|array
    {
        if ($return == true)
            return $this->errors;

        if (empty($this->errors))
            return false;
        else
            return true;
    }
    /** 
     * validate : validate a file as per validation type and add a error in @param errors 
     *
     * @param  mixed $validationType
     * @return void
     */
    public function validate($validationType)
    {
        if (strpos($validationType, ":") != false) {
            list($type, $info) = explode(":", $validationType);
            if (empty($info))  return; // if info not specfied 
        } else
            $type = $validationType;

        if ($type == "required") {
            if (!$this->isFileUploaded())
                array_push($this->errors, $this->errorMessage(4));
        }
        if ($type == "max"  && isset($info)) {
            if ($this->getFileSize() > $info)
                array_push($this->errors, $this->errorMessage(2, $info));
        }
        if ($type == "filetype" && isset($info)) {
            if (!$this->checkMimeType($info))
                array_push($this->errors, $this->errorMessage("INVALID_FILE_FORMAT", $info));
        }
    }
    /**
     * checkMimeType :  check mime type same or not as passed file extension
     *
     * @param  mixed $type 
     * @return void
     */
    public function checkMimeType($type)
    {
        $memeType = $this->getMemeType($type);
        return ($this->getFileType() == $memeType);
    }
    /**
     * getMemeType : map a file extention with mime type
     *
     * @param  mixed $type
     * @return void
     */
    private function getMemeType($type)
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
     * errorMessage : return a error message as per mapped $errorCode
     *
     * @param  mixed $errorCode
     * @param  mixed $info
     * @return void
     */
    public function errorMessage($errorCode, $info = "")
    {
        $message = match ($errorCode) {
            0 => "File is uploaded successfully",
            1 => "Uploaded file cross the limit.",
            2 => "Uploaded file cross the limit. $info KB",
            3 => "File is partially uploaded or there is any error in between uploading.",
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
