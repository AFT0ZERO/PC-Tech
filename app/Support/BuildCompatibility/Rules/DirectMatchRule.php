<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility\Rules;

use App\Support\BuildCompatibility\BuildContext;
use App\Support\BuildCompatibility\CompatibilityRule;
use App\Support\BuildCompatibility\RuleViolation;

/**
 * A field value of category A must equal a field value of category B.
 * Example: cpu.socket == motherboard.socket
 *
 * Side A may be a multi-unit category (e.g. ram): every unit must match side B.
 * When either side is missing — or its value is null (incomplete data) — the
 * rule is skipped instead of producing a false positive.
 */
final class DirectMatchRule implements CompatibilityRule
{
    public function __construct(
        private readonly string $categoryA,
        private readonly string $fieldA,
        private readonly string $categoryB,
        private readonly string $fieldB,
    ) {
    }

    public function check(BuildContext $build): ?RuleViolation
    {
        $specsA = $build->specsOf($this->categoryA);
        $specB = $build->specOf($this->categoryB);

        if ($specsA === [] || $specB === null) {
            return null;
        }

        $expected = $specB->{$this->fieldB};

        if ($expected === null) {
            return null;
        }

        foreach ($specsA as $specA) {
            $actual = $specA->{$this->fieldA};

            if ($actual === null) {
                continue;
            }

            if (! $this->valuesMatch($actual, $expected)) {
                return new RuleViolation(
                    ruleType: 'direct_match',
                    message: sprintf(
                        '%s %s (%s) does not match %s %s (%s).',
                        ucfirst(str_replace('_', ' ', $this->categoryA)), $this->fieldA, $actual,
                        ucfirst(str_replace('_', ' ', $this->categoryB)), $this->fieldB, $expected,
                    ),
                    context: [
                        'category_a' => $this->categoryA, 'field_a' => $this->fieldA, 'value_a' => $actual,
                        'category_b' => $this->categoryB, 'field_b' => $this->fieldB, 'value_b' => $expected,
                    ],
                );
            }
        }

        return null;
    }

    /**
     * Compatibility fields are case-insensitive identifiers (AM5 vs am5,
     * DDR5 vs ddr5) and whitespace-insensitive (LGA 1700 vs LGA1700).
     */
    private function valuesMatch(mixed $actual, mixed $expected): bool
    {
        if (is_string($actual) && is_string($expected)) {
            $a = preg_replace('/\s+/', '', $actual);
            $b = preg_replace('/\s+/', '', $expected);

            return strcasecmp($a, $b) === 0;
        }

        return $actual === $expected;
    }
}
