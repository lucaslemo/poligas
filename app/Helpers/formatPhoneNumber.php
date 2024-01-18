<?php

if (! function_exists('formatPhoneNumber')) {
    function formatPhoneNumber(string $number): string
    {
        $size = strlen(preg_replace("/[^0-9]/", "", $number));

        if ($size == 13) {
            // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
            return "+".substr($number, 0, $size-11)." (".substr($number, $size-11, 2).") ".substr($number, $size-9, 5)."-".substr($number, -4);
        }
        if ($size == 12) {
            // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
            return "+".substr($number, 0, $size-10)." (".substr($number, $size-10, 2).") ".substr($number, $size-8, 4)."-".substr($number, -4);
        }
        if ($size == 11) {
            // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
            return " (".substr($number, 0, 2).") ".substr($number, 2, 5)."-".substr($number, 7, 11);
        }
        if ($size == 10) {
            // COM CÓDIGO DE ÁREA NACIONAL
            return " (".substr($number, 0, 2).") ".substr($number, 2, 4)."-".substr($number, 6, 10);
        }
        if ($size <= 9) {
            // SEM CÓDIGO DE ÁREA
            return substr($number, 0, $size-4)."-".substr($number, -4);
        }
    }
}
