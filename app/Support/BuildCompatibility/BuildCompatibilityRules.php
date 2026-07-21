<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility;

use App\Support\BuildCompatibility\Rules\AggregateRule;
use App\Support\BuildCompatibility\Rules\DimensionalRule;
use App\Support\BuildCompatibility\Rules\DirectMatchRule;
use App\Support\BuildCompatibility\Rules\SetMembershipRule;

/**
 * The single, readable registry of every known compatibility rule.
 *
 * Category keys are not hardcoded to database IDs or names — they are the
 * spec keys derived from categories.specs_table (cpu_specs => "cpu"), so
 * renaming a category never breaks a rule (see Category::specKey()).
 *
 * Adding a new rule = one new line here. Adding a new rule *type* =
 * one new class under Rules/ implementing CompatibilityRule, then lines here.
 * Nothing else in the codebase needs to change (Open/Closed Principle).
 *
 * @return CompatibilityRule[]
 */
final class BuildCompatibilityRules
{
    /** @return CompatibilityRule[] */
    public static function all(): array
    {
        return [
            // 1) Direct property equality
            new DirectMatchRule('cpu', 'socket', 'motherboard', 'socket'),
            new DirectMatchRule('ram', 'type', 'motherboard', 'supported_ram_type'),

            // 2) Set membership (value ∈ JSON array)
            new SetMembershipRule('motherboard', 'form_factor', 'case', 'supported_form_factors'),
            new SetMembershipRule('cpu', 'socket', 'cpu_cooler', 'supported_sockets'),

            // 3) Aggregates
            new AggregateRule(
                sourceCategory: null, // every part of the build
                sourceField: 'power_draw_watts',
                aggregate: Aggregate::SUM,
                operator: '<=',
                targetCategory: 'psu',
                targetField: 'wattage',
                multiplier: 1.2, // 20% headroom over the estimated draw
            ),
            new AggregateRule(
                sourceCategory: 'ram',
                sourceField: 'capacity_gb',
                aggregate: Aggregate::SUM,
                operator: '<=',
                targetCategory: 'motherboard',
                targetField: 'max_ram_capacity_gb',
            ),
            new AggregateRule(
                sourceCategory: 'ram',
                sourceField: null,
                aggregate: Aggregate::COUNT,
                operator: '<=',
                targetCategory: 'motherboard',
                targetField: 'ram_slots',
            ),

            // 4) Physical dimensions
            new DimensionalRule('gpu', 'length_mm', '<=', 'case', 'max_gpu_length_mm'),
            new DimensionalRule('cpu_cooler', 'height_mm', '<=', 'case', 'max_cooler_height_mm'),
        ];
    }
}
