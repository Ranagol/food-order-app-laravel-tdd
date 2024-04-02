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
    use RefreshDatabase;

    /** @test */
    public function food_search_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function food_search_page_displays_all_products_from_db()
    {
        Product::factory()->count(3)->create();
        $items = Product::all();
        $response = $this->get('/');
        $response->assertViewIs('search')
                    ->assertViewHas('items', $items)
                    ->assertSeeInOrder(
                        [
                            $items[0]->name,
                            $items[1]->name,
                            $items[2]->name,
                        ]
                    );
    }

    /** @test */
    public function food_can_be_searched_with_a_query()
    {

        Product::factory()->create([
            'name' => 'Taco'
        ]);
        Product::factory()->create([
            'name' => 'Pizza'
        ]);
        Product::factory()->create([
            'name' => 'BBQ'
        ]);

        //Here we expect to see only bbq, and not to se pizza and taco
        $this->get('/?query=bbq')
                ->assertSee('BBQ')
                ->assertDontSeeText('Pizza')
                ->assertDontSeeText('Taco');

        //There is no query for this test, so we expect to see all the products
        $this->get('/')->assertSeeInOrder(['Taco', 'Pizza', 'BBQ']);
    }
}
