<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;

/**************************************
 *  Static Variables Start
 ***********/
define('SOMETHING_WRONG', 'Something Went Wrong, Please Try Again!');

/**************************************
 *  Static Variables End
 ***********/

class StaticHelper
{
    /**
     * Filter string to lowercase and remove special characters
     *
     * @param string $string
     * @return string
     */
    public static function filterStringToLower($string)
    {
        $username = preg_replace('/ /i', '', $string);
        $username = preg_replace('/[^A-Za-z0-9\-]/', '', $username);
        $username = strtolower($username);
        return $username;
    }

    /**
     * Generate random string
     *
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 12)
    {
        $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Generate unique username
     *
     * @param string $first_name
     * @param string $last_name
     * @param string $table
     * @return string
     */
    public static function generateUsername($first_name, $last_name, $table = "users", $column_name = 'user_name')
    {
        // Make username Dynamically
        $generate_name_with_count = "";
        do {
            // Generate username
            $firstName = $first_name;
            $lastName = $last_name;

            if ($generate_name_with_count == "") {
                if (strlen($firstName) >= 6) {
                    $generate_name = self::filterStringToLower($firstName);
                } else {
                    $modfy_last_name = explode(' ', $lastName);
                    $lastName = self::filterStringToLower($modfy_last_name[0]);
                    $firstName = self::filterStringToLower($firstName);
                    $generate_name = $firstName . $lastName;
                    if (strlen($generate_name) < 6) {
                        $firstName = self::filterStringToLower($firstName);
                        $lastName = self::filterStringToLower($lastName);
                        $generate_name = $firstName . $lastName;

                        if (strlen($generate_name) < 6) {
                            $getCurrentLen = strlen($generate_name);
                            $dueChar = 6 - $getCurrentLen;
                            $generate_due_char = strtolower(self::generateRandomString($dueChar));
                            $generate_name = $generate_name . $generate_due_char;
                        }
                    }
                }
            } else {
                $generate_name = $generate_name_with_count;
            }

            // Find User is already exists or not
            $chekUser = DB::table($table)->where($column_name, $generate_name)->first();

            if ($chekUser == null) {
                $loop = false;
            } else {
                $generate_name_with_count = $generate_name;

                $split_string = array_reverse(str_split($generate_name_with_count));
                $username_string_part = "";
                $last_numeric_values = "";
                $numeric_close = false;

                foreach ($split_string as $character) {
                    if ($numeric_close == false) {
                        if (is_numeric($character)) {
                            $last_numeric_values .= $character;
                        } else {
                            $numeric_close = true;
                        }
                    }
                    if ($numeric_close == true) {
                        $username_string_part .= $character;
                    }
                }

                if ($last_numeric_values == "") { // If has no number in username string;
                    $last_numeric_values = 1;
                }

                $username_string_part = strrev($username_string_part);
                $last_numeric_values = strrev($last_numeric_values);
                $generate_name_with_count = $username_string_part . ($last_numeric_values + 1);
                $loop = true;
            }
        } while ($loop);

        return $generate_name;
    }
}
