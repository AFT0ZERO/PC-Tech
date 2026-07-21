<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility;

/**
 * Shared contract for all compatibility rules (Strategy pattern).
 *
 * A rule returns a RuleViolation on failure, or null when it is satisfied —
 * or when a part it depends on has not been added to the build yet.
 */
interface CompatibilityRule
{
    public function check(BuildContext $build): ?RuleViolation;
}
