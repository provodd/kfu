<?php

namespace App\Controllers;
/**
 * Вспомогательный класс с различными функциями
 */
class Helper extends \provodd\base_framework\Helpers\Helper
{
    private static $instance;

    public static function instance(): Helper
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    function uniqidReal($lenght = 4)
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception(
                'no cryptographically secure random function available'
            );
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    public function ruble($number)
    {
        return number_format($number, 2, ',', ' ') . " &#8381;";
    }

    public function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool
    {
        return preg_match('/^[0-9]{' . $minDigits . ',' . $maxDigits . '}\z/', $s);
    }

    public function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool
    {
        if (preg_match('/^[+][0-9]/', $telephone)) {
            $count = 1;
            $telephone = str_replace(['+'], '', $telephone, $count); //remove +
        }

        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);

        return $this->isDigits($telephone, $minDigits, $maxDigits);
    }

    public function normalizeTelephoneNumber(string $telephone): string
    {
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
        return $telephone;
    }
}
