<?php

namespace Rabsana\Psp\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Rabsana\Psp\Tests\TestCase;
use Illuminate\Support\Str;
use Rabsana\Psp\Models\Merchant;

class MerchantUnitTest extends TestCase
{
    public $merchant;

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $merchantData = [
            'user_id'       => 1,
            'name'          => 'Foo Bar',
            'token'         => Str::random(16),
            'logo'          => 'test.png',
            'is_active'     => 1
        ];

        $merchantId = Merchant::create($merchantData)->id;

        $this->merchant = Merchant::find($merchantId);
    }

    public function test_scope_user_id()
    {
        $this->assertEquals(Merchant::userId(1)->first(), $this->merchant);
        $this->assertEquals(Merchant::userId(0)->first(), null);
    }

    public function test_scope_name()
    {
        $this->assertEquals(Merchant::name('Foo Bar')->first(), $this->merchant);
        $this->assertEquals(Merchant::name('test')->first(), null);
    }

    public function test_scope_token()
    {
        $this->assertEquals(Merchant::token($this->merchant->token)->first(), $this->merchant);
        $this->assertEquals(Merchant::token('test')->first(), null);
    }

    public function test_scope_is_active()
    {
        $this->assertEquals(Merchant::isActive(1)->first(), $this->merchant);
        $this->assertEquals(Merchant::isActive(0)->first(), null);

        $this->assertEquals(Merchant::active()->first(), $this->merchant);
        $this->assertEquals(Merchant::inActive()->first(), null);
    }
}
