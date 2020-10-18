<?php

namespace Sim\Utils;


class StringUtil
{
    const RS_NUMBER = 0x1;
    const RS_LOWER_CHAR = 0x2;
    const RS_UPPER_CHAR = 0x4;
    const RS_ALL = StringUtil::RS_NUMBER | StringUtil::RS_LOWER_CHAR | StringUtil::RS_UPPER_CHAR;

    const CONVERT_NUMBERS = 0x1;
    const CONVERT_CHARACTERS = 0x2;
    const CONVERT_ALL = StringUtil::CONVERT_NUMBERS | StringUtil::CONVERT_CHARACTERS;

    /**
     * @var array
     */
    private static $persian_numbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /**
     * @var array
     */
    private static $arabic_numbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    /**
     * @var array
     */
    private static $english_numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

    /**
     * @var array
     */
    private static $persian_special_characters = ['ا', 'گ', 'چ', 'پ', 'ژ', 'ه', 'ی'];

    /**
     * @var array
     */
    private static $arabic_special_characters = ['أ', 'ك', 'ج', 'ب', 'ز', 'ة', 'ي'];

    /**
     * generate_random_string constants
     *
     * @param $length
     * @param int $type
     * @return string
     */
    public static function randomString($length, $type = StringUtil::RS_ALL)
    {
        $charactersMap = [
            'number' => '0123456789',
            'lower' => 'abcdefghijklmnopqrstuvwxyz',
            'upper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ];

        $characters = '';
        $haveType = StringUtil::RS_ALL;

        if ($type & StringUtil::RS_NUMBER) {
            $characters .= $charactersMap['number'];
            $haveType = $haveType ^ StringUtil::RS_NUMBER;
        }
        if ($type & StringUtil::RS_LOWER_CHAR) {
            $characters .= $charactersMap['lower'];
            $haveType = $haveType ^ StringUtil::RS_LOWER_CHAR;
        }
        if ($type & StringUtil::RS_UPPER_CHAR) {
            $characters .= $charactersMap['upper'];
            $haveType = $haveType ^ StringUtil::RS_UPPER_CHAR;
        }
        if (StringUtil::RS_ALL ^ $haveType == 0) {
            $characters = $charactersMap['number'] . $charactersMap['lower'] . $charactersMap['upper'];
        }

        $charactersLength = strlen($characters);

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * convert arabic and english numbers and arabic specific
     * characters to persian numbers and specific characters
     *
     * @param $str
     * @param int $convert_type
     * @return array|mixed
     */
    public static function toPersian($str, $convert_type = StringUtil::CONVERT_ALL)
    {
        if (StringUtil::CONVERT_NUMBERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toPersian($str[$k], $convert_type);
                }
                return $newArr;
            }

            if (is_string($str)) {
                $str = str_replace(self::$english_numbers, self::$persian_numbers, $str);
                $str = str_replace(self::$arabic_numbers, self::$persian_numbers, $str);
            }
        } elseif (StringUtil::CONVERT_CHARACTERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toPersian($v, $convert_type);
                }
                return $newArr;
            }

            if (is_string($str)) {
                $str = str_replace(self::$arabic_special_characters, self::$persian_special_characters, $str);
            }
        }

        return $str;
    }

    /**
     * convert persian and english numbers and persian specific
     * characters to arabic numbers and specific characters
     *
     * @param $str
     * @param int $convert_type
     * @return array|mixed
     */
    public static function toArabic($str, $convert_type = StringUtil::CONVERT_ALL)
    {
        if (StringUtil::CONVERT_NUMBERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toArabic($str[$k], $convert_type);
                }
                return $newArr;
            }

            if (is_string($str)) {
                $str = str_replace(self::$english_numbers, self::$arabic_numbers, $str);
                $str = str_replace(self::$persian_numbers, self::$arabic_numbers, $str);
            }
        } elseif (StringUtil::CONVERT_CHARACTERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toArabic($v, $convert_type);
                }
                return $newArr;
            }

            if (is_string($str)) {
                $str = str_replace(self::$persian_special_characters, self::$arabic_special_characters, $str);
            }
        }

        return $str;
    }

    /**
     * Convert numbers from arabic and persian to english numbers
     *
     * @param $str
     * @return array|mixed
     */
    public static function toEnglish($str)
    {
        if (is_array($str)) {
            $newArr = [];
            foreach ($str as $k => $v) {
                $newArr[$k] = self::toEnglish($v);
            }
            return $newArr;
        }

        if (is_string($str)) {
            $str = str_replace(self::$arabic_numbers, self::$english_numbers, $str);
            $str = str_replace(self::$persian_numbers, self::$english_numbers, $str);
        }

        return $str;
    }

    /**
     * @see https://stackoverflow.com/a/16239689/12154893
     * @param string $string
     * @param int $length
     * @param string $delimiter
     * @return string
     */
    public static function truncate(string $string, int $length, string $delimiter = '...'): string
    {
        return mb_strimwidth($string, 0, $length, $delimiter);
    }

    /**
     * @see https://stackoverflow.com/a/16239689/12154893
     * @param string $string
     * @param int $length
     * @param string $delimiter
     * @return string
     */
    public static function truncate_word(string $string, int $length, string $delimiter = '...'): string
    {
        // we don't want new lines in our preview
        $text_only_spaces = preg_replace('/\s+/', ' ', $string);

        // truncates the text
        $text_truncated = mb_substr($text_only_spaces, 0, mb_strpos($text_only_spaces, ' ', $length));

        // prevents last word truncation
        $preview = trim(mb_substr($text_truncated, 0, mb_strrpos($text_truncated, ' '))) . $delimiter;

        return $preview;
    }

    /**
     * @see https://stackoverflow.com/a/2955878/12154893
     * @param string $string
     * @return string
     */
    public static function slugify(string $string): string
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}