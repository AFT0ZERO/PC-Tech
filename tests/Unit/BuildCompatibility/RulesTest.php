<?php

namespace Tests\Unit\BuildCompatibility;

use App\Models\CaseSpec;
use App\Models\CpuCoolerSpec;
use App\Models\CpuSpec;
use App\Models\GpuSpec;
use App\Models\MotherboardSpec;
use App\Models\PsuSpec;
use App\Models\RamSpec;
use App\Support\BuildCompatibility\Aggregate;
use App\Support\BuildCompatibility\BuildContext;
use App\Support\BuildCompatibility\Rules\AggregateRule;
use App\Support\BuildCompatibility\Rules\DimensionalRule;
use App\Support\BuildCompatibility\Rules\DirectMatchRule;
use App\Support\BuildCompatibility\Rules\SetMembershipRule;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Pure unit tests for the four compatibility rule types.
 * No database, no Laravel app — spec rows are plain in-memory models and the
 * BuildContext is assembled by hand, exactly the isolation the engine was
 * designed for (rules never query the DB).
 */
class RulesTest extends TestCase
{
    // ── DirectMatchRule ─────────────────────────────────────────────────────

    public function test_direct_match_passes_when_values_are_equal(): void
    {
        $rule = new DirectMatchRule('cpu', 'socket', 'motherboard', 'socket');
        $context = new BuildContext([
            'cpu' => [new CpuSpec(['socket' => 'AM5'])],
            'motherboard' => [new MotherboardSpec(['socket' => 'AM5'])],
        ]);

        $this->assertNull($rule->check($context));
    }

    public function test_direct_match_is_case_insensitive(): void
    {
        $rule = new DirectMatchRule('cpu', 'socket', 'motherboard', 'socket');
        $context = new BuildContext([
            'cpu' => [new CpuSpec(['socket' => 'am5'])],
            'motherboard' => [new MotherboardSpec(['socket' => 'AM5'])],
        ]);

        $this->assertNull($rule->check($context));
    }

    public function test_direct_match_returns_violation_on_mismatch(): void
    {
        $rule = new DirectMatchRule('cpu', 'socket', 'motherboard', 'socket');
        $context = new BuildContext([
            'cpu' => [new CpuSpec(['socket' => 'AM5'])],
            'motherboard' => [new MotherboardSpec(['socket' => 'LGA1700'])],
        ]);

        $violation = $rule->check($context);

        $this->assertNotNull($violation);
        $this->assertSame('direct_match', $violation->ruleType);
        $this->assertStringContainsString('AM5', $violation->message);
        $this->assertStringContainsString('LGA1700', $violation->message);
        $this->assertSame('AM5', $violation->context['value_a']);
        $this->assertSame('LGA1700', $violation->context['value_b']);
    }

    public function test_direct_match_checks_every_unit_of_a_multi_unit_category(): void
    {
        $rule = new DirectMatchRule('ram', 'type', 'motherboard', 'supported_ram_type');
        $context = new BuildContext([
            'ram' => [new RamSpec(['type' => 'DDR5']), new RamSpec(['type' => 'DDR4'])],
            'motherboard' => [new MotherboardSpec(['supported_ram_type' => 'DDR5'])],
        ]);

        $this->assertNotNull($rule->check($context));
    }

    public function test_direct_match_skips_when_a_side_is_missing_or_null(): void
    {
        $rule = new DirectMatchRule('cpu', 'socket', 'motherboard', 'socket');

        $this->assertNull($rule->check(new BuildContext())); // nothing selected yet
        $this->assertNull($rule->check(new BuildContext(['cpu' => [new CpuSpec(['socket' => 'AM5'])]])));
        $this->assertNull($rule->check(new BuildContext([
            'cpu' => [new CpuSpec(['socket' => null])],
            'motherboard' => [new MotherboardSpec(['socket' => 'AM5'])],
        ])));
    }

    // ── SetMembershipRule ───────────────────────────────────────────────────

    public function test_set_membership_passes_when_value_is_in_set(): void
    {
        $rule = new SetMembershipRule('motherboard', 'form_factor', 'case', 'supported_form_factors');
        $context = new BuildContext([
            'motherboard' => [new MotherboardSpec(['form_factor' => 'ATX'])],
            'case' => [new CaseSpec(['supported_form_factors' => ['ATX', 'mATX']])],
        ]);

        $this->assertNull($rule->check($context));
    }

    public function test_set_membership_is_case_insensitive(): void
    {
        $rule = new SetMembershipRule('cpu', 'socket', 'cpu_cooler', 'supported_sockets');
        $context = new BuildContext([
            'cpu' => [new CpuSpec(['socket' => 'am5'])],
            'cpu_cooler' => [new CpuCoolerSpec(['supported_sockets' => ['AM5', 'LGA1700']])],
        ]);

        $this->assertNull($rule->check($context));
    }

    public function test_set_membership_returns_violation_when_value_not_in_set(): void
    {
        $rule = new SetMembershipRule('motherboard', 'form_factor', 'case', 'supported_form_factors');
        $context = new BuildContext([
            'motherboard' => [new MotherboardSpec(['form_factor' => 'E-ATX'])],
            'case' => [new CaseSpec(['supported_form_factors' => ['ATX', 'mATX']])],
        ]);

        $violation = $rule->check($context);

        $this->assertNotNull($violation);
        $this->assertSame('set_membership', $violation->ruleType);
        $this->assertStringContainsString('E-ATX', $violation->message);
        $this->assertSame(['ATX', 'mATX'], $violation->context['set']);
    }

    public function test_set_membership_skips_on_missing_or_empty_data(): void
    {
        $rule = new SetMembershipRule('motherboard', 'form_factor', 'case', 'supported_form_factors');

        $this->assertNull($rule->check(new BuildContext()));
        $this->assertNull($rule->check(new BuildContext([
            'motherboard' => [new MotherboardSpec(['form_factor' => null])],
            'case' => [new CaseSpec(['supported_form_factors' => ['ATX']])],
        ])));
        $this->assertNull($rule->check(new BuildContext([
            'motherboard' => [new MotherboardSpec(['form_factor' => 'ATX'])],
            'case' => [new CaseSpec(['supported_form_factors' => []])],
        ])));
    }

    // ── AggregateRule ───────────────────────────────────────────────────────

    public function test_aggregate_power_draw_with_multiplier(): void
    {
        $rule = new AggregateRule(null, 'power_draw_watts', Aggregate::SUM, '<=', 'psu', 'wattage', 1.2);

        // 400W total draw * 1.2 = 480W <= 600W PSU → OK
        $ok = new BuildContext(['psu' => [new PsuSpec(['wattage' => 600])]], 400.0);
        $this->assertNull($rule->check($ok));

        // 500W total draw * 1.2 = 600W > 550W PSU → violation
        $over = new BuildContext(['psu' => [new PsuSpec(['wattage' => 550])]], 500.0);
        $violation = $rule->check($over);

        $this->assertNotNull($violation);
        $this->assertSame('aggregate', $violation->ruleType);
        $this->assertSame(600.0, $violation->context['computed_source_value']);
        $this->assertSame(550.0, $violation->context['target_value']);
    }

    public function test_aggregate_sum_of_ram_capacity_against_motherboard_limit(): void
    {
        $rule = new AggregateRule('ram', 'capacity_gb', Aggregate::SUM, '<=', 'motherboard', 'max_ram_capacity_gb');

        $ok = new BuildContext([
            'ram' => [new RamSpec(['capacity_gb' => 16]), new RamSpec(['capacity_gb' => 16])],
            'motherboard' => [new MotherboardSpec(['max_ram_capacity_gb' => 64])],
        ]);
        $this->assertNull($rule->check($ok));

        $over = new BuildContext([
            'ram' => [new RamSpec(['capacity_gb' => 32]), new RamSpec(['capacity_gb' => 32])],
            'motherboard' => [new MotherboardSpec(['max_ram_capacity_gb' => 32])],
        ]);
        $this->assertNotNull($rule->check($over));
    }

    public function test_aggregate_count_of_ram_sticks_against_slots(): void
    {
        $rule = new AggregateRule('ram', null, Aggregate::COUNT, '<=', 'motherboard', 'ram_slots');

        $ok = new BuildContext([
            'ram' => [new RamSpec(), new RamSpec()],
            'motherboard' => [new MotherboardSpec(['ram_slots' => 4])],
        ]);
        $this->assertNull($rule->check($ok));

        $tooMany = new BuildContext([
            'ram' => [new RamSpec(), new RamSpec(), new RamSpec()],
            'motherboard' => [new MotherboardSpec(['ram_slots' => 2])],
        ]);
        $violation = $rule->check($tooMany);

        $this->assertNotNull($violation);
        $this->assertSame(3.0, $violation->context['computed_source_value']);
    }

    public function test_aggregate_skips_when_target_missing_or_null(): void
    {
        $rule = new AggregateRule(null, 'power_draw_watts', Aggregate::SUM, '<=', 'psu', 'wattage', 1.2);

        $this->assertNull($rule->check(new BuildContext([], 1000.0))); // no PSU selected
        $this->assertNull($rule->check(new BuildContext(['psu' => [new PsuSpec(['wattage' => null])]], 1000.0)));
    }

    public function test_aggregate_rejects_unsupported_operator(): void
    {
        $rule = new AggregateRule('ram', 'capacity_gb', Aggregate::SUM, '=', 'motherboard', 'max_ram_capacity_gb');
        $context = new BuildContext([
            'ram' => [new RamSpec(['capacity_gb' => 16])],
            'motherboard' => [new MotherboardSpec(['max_ram_capacity_gb' => 64])],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $rule->check($context);
    }

    // ── DimensionalRule ─────────────────────────────────────────────────────

    public function test_dimensional_passes_when_part_fits(): void
    {
        $rule = new DimensionalRule('gpu', 'length_mm', '<=', 'case', 'max_gpu_length_mm');
        $context = new BuildContext([
            'gpu' => [new GpuSpec(['length_mm' => 300])],
            'case' => [new CaseSpec(['max_gpu_length_mm' => 320])],
        ]);

        $this->assertNull($rule->check($context));
    }

    public function test_dimensional_returns_violation_when_part_does_not_fit(): void
    {
        $rule = new DimensionalRule('cpu_cooler', 'height_mm', '<=', 'case', 'max_cooler_height_mm');
        $context = new BuildContext([
            'cpu_cooler' => [new CpuCoolerSpec(['height_mm' => 170])],
            'case' => [new CaseSpec(['max_cooler_height_mm' => 160])],
        ]);

        $violation = $rule->check($context);

        $this->assertNotNull($violation);
        $this->assertSame('dimensional', $violation->ruleType);
        $this->assertSame(170.0, $violation->context['value_a']);
        $this->assertSame(160.0, $violation->context['value_b']);
    }

    public function test_dimensional_skips_on_missing_or_null_measurements(): void
    {
        $rule = new DimensionalRule('gpu', 'length_mm', '<=', 'case', 'max_gpu_length_mm');

        $this->assertNull($rule->check(new BuildContext()));
        $this->assertNull($rule->check(new BuildContext([
            'gpu' => [new GpuSpec(['length_mm' => null])],
            'case' => [new CaseSpec(['max_gpu_length_mm' => 320])],
        ])));
    }

    public function test_dimensional_rejects_unsupported_operator(): void
    {
        $rule = new DimensionalRule('gpu', 'length_mm', '<', 'case', 'max_gpu_length_mm');
        $context = new BuildContext([
            'gpu' => [new GpuSpec(['length_mm' => 300])],
            'case' => [new CaseSpec(['max_gpu_length_mm' => 320])],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $rule->check($context);
    }
}
