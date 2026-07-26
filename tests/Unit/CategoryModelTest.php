<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CategoryModelTest extends TestCase
{
    public function test_category_model_class_is_resolvable(): void
    {
        $this->assertTrue(class_exists(\App\Models\Category::class));
    }
}
