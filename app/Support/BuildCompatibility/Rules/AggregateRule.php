<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility\Rules;

use App\Support\BuildCompatibility\Aggregate;
use App\Support\BuildCompatibility\BuildContext;
use App\Support\BuildCompatibility\CompatibilityRule;
use App\Support\BuildCompatibility\RuleViolation;
use InvalidArgumentException;

/**
 * Aggregates (SUM or COUNT) a field across one category — or across the whole
 * build — and compares it against a target field, with an optional multiplier.
 *
 * Examples:
 *  - SUM(power_draw_watts of all parts) * 1.2  <=  psu.wattage   (sourceCategory = null => whole build)
 *  - SUM(ram.capacity_gb)                      <=  motherboard.max_ram_capacity_gb
 *  - COUNT(ram sticks)                         <=  motherboard.ram_slots
 *
 * A null target value skips the rule (incomplete spec data must not produce
 * false positives). Null source fields count as 0.
 */
final class AggregateRule implements CompatibilityRule
{
    public function __construct(
        private readonly ?string $sourceCategory, // null = every part of the build (used with power draw)
        private readonly ?string $sourceField,     // null only when aggregate = COUNT
        private readonly Aggregate $aggregate,
        private readonly string $operator,         // '<=' or '>='
        private readonly string $targetCategory,
        private readonly string $targetField,
        private readonly float $multiplier = 1.0,
    ) {
    }

    public function check(BuildContext $build): ?RuleViolation
    {
        $target = $build->specOf($this->targetCategory);

        if ($target === null) {
            return null;
        }

        $targetRaw = $target->{$this->targetField};

        if ($targetRaw === null) {
            return null;
        }

        $sourceValue = $this->resolveSourceValue($build) * $this->multiplier;
        $targetValue = (float) $targetRaw;

        $satisfied = match ($this->operator) {
            '<=' => $sourceValue <= $targetValue,
            '>=' => $sourceValue >= $targetValue,
            default => throw new InvalidArgumentException("Unsupported comparison operator: {$this->operator}"),
        };

        if (! $satisfied) {
            return new RuleViolation(
                ruleType: 'aggregate',
                message: $this->violationMessage($sourceValue, $targetValue),
                context: [
                    'source_category' => $this->sourceCategory, 'source_field' => $this->sourceField,
                    'aggregate' => $this->aggregate->value, 'multiplier' => $this->multiplier,
                    'computed_source_value' => $sourceValue,
                    'target_category' => $this->targetCategory, 'target_field' => $this->targetField,
                    'target_value' => $targetValue,
                ],
            );
        }

        return null;
    }

    private function resolveSourceValue(BuildContext $build): float
    {
        if ($this->sourceCategory === null) {
            return $build->totalPowerDraw();
        }

        $specs = $build->specsOf($this->sourceCategory);

        return match ($this->aggregate) {
            Aggregate::COUNT => (float) count($specs),
            Aggregate::SUM => array_sum(array_map(
                fn ($spec) => (float) ($spec->{$this->sourceField} ?? 0),
                $specs,
            )),
        };
    }

    private function violationMessage(float $sourceValue, float $targetValue): string
    {
        if ($this->sourceCategory === null) {
            return sprintf(
                'Total power draw (%.0fW including headroom) exceeds the %s %s (%.0fW).',
                $sourceValue, $this->targetCategory, $this->targetField, $targetValue,
            );
        }

        return sprintf(
            '%s of %s %s (%s) does not satisfy %s %s %s (%.0f).',
            $this->aggregate->value, $this->sourceCategory, $this->sourceField ?? 'units',
            $this->formatNumber($sourceValue), $this->operator,
            $this->targetCategory, $this->targetField, $targetValue,
        );
    }

    private function formatNumber(float $value): string
    {
        return fmod($value, 1.0) === 0.0 ? (string) (int) $value : (string) $value;
    }
}
