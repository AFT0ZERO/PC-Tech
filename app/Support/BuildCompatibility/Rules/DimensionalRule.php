<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility\Rules;

use App\Support\BuildCompatibility\BuildContext;
use App\Support\BuildCompatibility\CompatibilityRule;
use App\Support\BuildCompatibility\RuleViolation;
use InvalidArgumentException;

/**
 * Compares a physical measurement between two categories with a fixed
 * operator (<=, >=).
 * Example: gpu.length_mm <= case.max_gpu_length_mm
 *          cpu_cooler.height_mm <= case.max_cooler_height_mm
 *
 * A null measurement on either side skips the rule.
 */
final class DimensionalRule implements CompatibilityRule
{
    public function __construct(
        private readonly string $categoryA,
        private readonly string $fieldA,
        private readonly string $operator,
        private readonly string $categoryB,
        private readonly string $fieldB,
    ) {
    }

    public function check(BuildContext $build): ?RuleViolation
    {
        $specA = $build->specOf($this->categoryA);
        $specB = $build->specOf($this->categoryB);

        if ($specA === null || $specB === null) {
            return null;
        }

        $rawA = $specA->{$this->fieldA};
        $rawB = $specB->{$this->fieldB};

        if ($rawA === null || $rawB === null) {
            return null;
        }

        $valueA = (float) $rawA;
        $valueB = (float) $rawB;

        $satisfied = match ($this->operator) {
            '<=' => $valueA <= $valueB,
            '>=' => $valueA >= $valueB,
            default => throw new InvalidArgumentException("Unsupported comparison operator: {$this->operator}"),
        };

        if (! $satisfied) {
            return new RuleViolation(
                ruleType: 'dimensional',
                message: sprintf(
                    'The selected %s (%s: %.0fmm) does not fit inside the selected %s (%s: %.0fmm).',
                    str_replace('_', ' ', $this->categoryA), $this->fieldA, $valueA,
                    str_replace('_', ' ', $this->categoryB), $this->fieldB, $valueB,
                ),
                context: [
                    'category_a' => $this->categoryA, 'field_a' => $this->fieldA, 'value_a' => $valueA,
                    'category_b' => $this->categoryB, 'field_b' => $this->fieldB, 'value_b' => $valueB,
                ],
            );
        }

        return null;
    }
}
