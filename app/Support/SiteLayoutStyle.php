<?php

namespace App\Support;

enum SiteLayoutStyle: string
{
    case GMAIL = 'gmail';
    case OFFICE = 'office';

    public static function default(): self
    {
        return SiteLayoutStyle::OFFICE;
    }
}
