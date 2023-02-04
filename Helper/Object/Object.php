<?php

/**
 * ObjectFormatter
 */
class ObjectFormatter
{
    /**
     * format : data in json format ex. { status}
     *
     * @param  mixed $data
     * @param  mixed $code
     * @param  mixed $statuscode
     * @return void
     */
    public static function format(array $data = [], int $code = 200, string $statuscode = "SUCCESSFULL", string $message = null)
    {
        $keys = array();
        $values = array();
        if ($message != null) {
            array_push($keys, 'statuscode', 'code', 'message', 'data');
            array_push($values, $statuscode, $code, $message, $data);
        } else {
            $keys = array('statuscode', 'code', 'data');
            $values = array($statuscode, $code, $data);
        }

        return json_encode(array_combine($keys, $values));
    }
}
