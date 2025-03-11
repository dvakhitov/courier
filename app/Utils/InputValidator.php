<?php
namespace App\Utils;

class InputValidator {
    /**
     * Получает и валидирует целое число из $_POST.
     * Возвращает число или null, если данные недействительны.
     */
    public static function getInt(string $key): ?int {
        $value = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);

        return $value === false ? null : $value;
    }

    /**
     * Получает и очищает строковое значение из $_POST.
     */
    public static function getString(string $key): ?string {
        $value = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW);
        if ($value === null || trim($value) === '') {
            return null;
        }

        return trim(strip_tags($value));
    }

    /**
     * Проверяет, соответствует ли строка формату даты.
     * Возвращает значение, если формат корректный, иначе null.
     */
    public static function validateDate(string $key, string $format = 'Y-m-d'): ?string {
        $value = self::getString($key);
        if (!$value) {
            return null;
        }
        $dateObj = \DateTime::createFromFormat($format, $value);

        return ($dateObj && $dateObj->format($format) === $value) ? $value : null;
    }
}
