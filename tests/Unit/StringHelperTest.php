<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\StringHelper;

class StringHelperTest extends TestCase
{
    public function test_safe_limit_basic()
    {
        $this->assertEquals('Hello...', StringHelper::safeLimit('Hello World', 5));
        $this->assertEquals('Hello', StringHelper::safeLimit('Hello', 10));
        $this->assertEquals('', StringHelper::safeLimit('', 10));
    }

    public function test_safe_limit_custom_end()
    {
        $this->assertEquals('Hello~', StringHelper::safeLimit('Hello World', 5, '~'));
    }

    public function test_first_char()
    {
        $this->assertEquals('H', StringHelper::firstChar('Hello'));
        $this->assertEquals('', StringHelper::firstChar(''));
        $this->assertEquals('世', StringHelper::firstChar('世界'));
    }
} 