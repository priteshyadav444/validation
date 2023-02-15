<?php
trait Test
{
    public function test($testCases, $functionName)
    {
        foreach ($testCases as $input => $output) {
            $result = self::$functionName($input);
            if ($result == $output) {
                echo "Test Case Passed :  " . $input . " => {$result}<br>";
            } else {
                echo "Test Case Failed :  " . $input . " => {$result}, (Expected => {$output})<br>";
            }
        }
    }
}
