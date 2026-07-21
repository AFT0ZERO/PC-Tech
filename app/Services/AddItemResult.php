<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\BuildCompatibility\RuleViolation;

/**
 * Outcome of a build-item operation. Lets the controller decide the response
 * shape (block, or allow-with-warnings) instead of baking one product/UX
 * decision into the service.
 */
final class AddItemResult
{
    public function __construct(
        public readonly bool $added,
        public readonly ?string $blockedReason = null,
        /** @var RuleViolation[] */
        public readonly array $violations = [],
    ) {
    }
}
