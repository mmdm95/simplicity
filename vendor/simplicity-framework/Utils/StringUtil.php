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
        $enNumbers = range(0, 9);
        $arNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $numbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $replacements = [
            'أ' => 'ا',
            'ك' => 'گ',
            'ج' => 'چ',
            'ب' => 'پ',
            'ز' => 'ژ',
            'ة' => 'ه',
            'ي' => 'ی',
        ];

        if(StringUtil::CONVERT_NUMBERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toPersian($str[$k], $convert_type);
                }
                return $newArr;
            }

            if (is_string($str)) {
                $str = str_replace($enNumbers, $numbers, $str);
                $str = str_replace($arNumbers, $numbers, $str);
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
                $str = str_replace(array_keys($replacements), array_values($replacements), $str);
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
        $enNumbers = range(0, 9);
        $faNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $numbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $replacements = [
            'ا' => 'أ',
            'گ' => 'ك',
            'چ' => 'ج',
            'پ' => 'ب',
            'ژ' => 'ز',
            'ه' => 'ة',
            'ی' => 'ي',
        ];

        if(StringUtil::CONVERT_NUMBERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toArabic($str[$k], $convert_type);
                }
                return $newArr;
            }

            if (is_string($str)) {
                $str = str_replace($enNumbers, $numbers, $str);
                $str = str_replace($faNumbers, $numbers, $str);
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
                $str = str_replace(array_keys($replacements), array_values($replacements), $str);
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
        $arNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $faNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $numbers = range(0, 9);

        if (is_array($str)) {
            $newArr = [];
            foreach ($str as $k => $v) {
                $newArr[$k] = self::toEnglish($v);
            }
            return $newArr;
        }

        if (is_string($str)) {
            $str = str_replace($arNumbers, $numbers, $str);
            $str = str_replace($faNumbers, $numbers, $str);
        }

        return $str;
    }
}