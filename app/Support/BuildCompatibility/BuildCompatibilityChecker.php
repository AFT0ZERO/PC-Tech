<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility;

use App\Models\Build;

/**
 * Runs every registered CompatibilityRule against a build and collects the
 * violations. The build may be persisted or transient (an unsaved Build with
 * its `items` relation set) — the checker never queries the database itself;
 * all data access happens once, eagerly, inside BuildContext::fromBuild().
 */
final class BuildCompatibilityChecker
{
    /** @return RuleViolation[] */
    public function check(Build $build): array
    {
        $context = BuildContext::fromBuild($build);

        return array_values(array_filter(array_map(
            fn (CompatibilityRule $rule) => $rule->check($context),
            BuildCompatibilityRules::all(),
        )));
    }

    public function isCompatible(Build $build): bool
    {
        return $this->check($build) === [];
    }
}
