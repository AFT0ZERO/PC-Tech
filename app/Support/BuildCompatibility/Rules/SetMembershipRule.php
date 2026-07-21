<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility\Rules;

use App\Support\BuildCompatibility\BuildContext;
use App\Support\BuildCompatibility\CompatibilityRule;
use App\Support\BuildCompatibility\RuleViolation;

/**
 * A field value must be a member of a JSON array stored on another category.
 * Example: motherboard.form_factor ∈ case.supported_form_factors
 *          cpu.socket ∈ cpu_cooler.supported_sockets
 *
 * Membership is compared case-insensitively. Null/empty data on either side
 * skips the rule (incomplete data must not produce false positives).
 */
final class SetMembershipRule implements CompatibilityRule
{
    public function __construct(
        private readonly string $valueCategory,
        private readonly string $valueField,
        private readonly string $setCategory,
        private readonly string $setField,
    ) {
    }

    public function check(BuildContext $build): ?RuleViolation
    {
        $valueSpec = $build->specOf($this->valueCategory);
        $setSpec = $build->specOf($this->setCategory);

        if ($valueSpec === null || $setSpec === null) {
            return null;
        }

        $value = $valueSpec->{$this->valueField};
        $set = (array) ($setSpec->{$this->setField} ?? []); // JSON column, cast to array on the model

        if ($value === null || $set === []) {
            return null;
        }

        if (! $this->contains($set, $value)) {
            return new RuleViolation(
                ruleType: 'set_membership',
                message: sprintf(
                    '%s %s (%s) is not supported by the selected %s.',
                    ucfirst(str_replace('_', ' ', $this->valueCategory)), $this->valueField, $value,
                    str_replace('_', ' ', $this->setCategory),
                ),
                context: [
                    'value_category' => $this->valueCategory, 'value_field' => $this->valueField, 'value' => $value,
                    'set_category' => $this->setCategory, 'set_field' => $this->setField, 'set' => $set,
                ],
            );
        }

        return null;
    }

    private function contains(array $set, mixed $value): bool
    {
        foreach ($set as $member) {
            if (is_string($member) && is_string($value)) {
                if (strcasecmp(trim($member), trim($value)) === 0) {
                    return true;
                }
            } elseif ($member === $value) {
                return true;
            }
        }

        return false;
    }
}
