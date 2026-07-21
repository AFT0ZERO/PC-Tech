<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility;

/**
 * The result of a single failed compatibility rule.
 * Immutable value object — describes "what was violated" and "why", no logic.
 */
final class RuleViolation
{
    public function __construct(
        public readonly string $ruleType,   // direct_match | set_membership | aggregate | dimensional
        public readonly string $message,    // user-presentable message
        public readonly array $context = [] // raw data for debugging / custom UI rendering
    ) {
    }
}
