<?php

namespace App\Enums;

enum SourceKey: string
{
    case NEWSAPI = 'newsapi';
    case GUARDIAN = 'guardian';
    case NYT = 'nyt';

    public function title(): string
    {
        return match($this) {
            self::NEWSAPI => 'NewsAPI',
            self::GUARDIAN => 'The Guardian',
            self::NYT => 'New York Times',
        };
    }

    public function apiName(): string
    {
        return match($this) {
            self::NEWSAPI => 'newsapi',
            self::GUARDIAN => 'guardian',
            self::NYT => 'nyt',
        };
    }
}
