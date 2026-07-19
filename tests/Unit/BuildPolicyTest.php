<?php

namespace Tests\Unit;

use App\Models\Build;
use App\Models\User;
use App\Policies\BuildPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BuildPolicyTest extends TestCase
{
    use DatabaseTransactions;

    private BuildPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new BuildPolicy;
    }

    public function test_owner_can_delete_build(): void
    {
        $owner = User::factory()->create();
        $build = Build::factory()->create(['user_id' => $owner->id]);

        $result = $this->policy->delete($owner, $build);

        $this->assertTrue($result);
    }

    public function test_other_user_cannot_delete_build(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $build = Build::factory()->create(['user_id' => $owner->id]);

        $result = $this->policy->delete($other, $build);

        $this->assertFalse($result);
    }
}
