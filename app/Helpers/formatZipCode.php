<?php

if (! function_exists('formatZipCode')) {
    function formatZipCode(string $value): string
    {
        $cleanString = preg_replace("/\D/", '', $value);

        return preg_replace("/(\d{5})(\d{3})/", "\$1-\$2", $cleanString);
    }
}
