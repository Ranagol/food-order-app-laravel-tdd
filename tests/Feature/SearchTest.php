<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * This is for the page http://127.0.0.1:8000/
 */
class SearchTest extends TestCase
{
    /** @test */
    public function food_search_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function food_search_page_has_all_the_required_data()
    {
        Product::factory()->count(3)->create();
    }
}
