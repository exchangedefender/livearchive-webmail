<?php

function parse_address_rfc822(string $input): string
{
    return data_get(mailparse_rfc822_parse_addresses($input), '0.address', $input);
}

function parse_display_rfc822(string $input): string
{
    return data_get(mailparse_rfc822_parse_addresses($input), '0.display', $input);
}

function getInitials(string $name): string
{
    $ret = '';
    foreach (explode(' ', $name) as $word) {
        $ret .= strtoupper($word[0]);
    }

    return $ret;
}
