<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility;

enum Aggregate: string
{
    case SUM = 'sum';
    case COUNT = 'count';
}
