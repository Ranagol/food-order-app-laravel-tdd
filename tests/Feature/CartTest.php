<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cart_page_is_accessible()
    {
        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertViewIs('cart');
    }

    /** @test */
    public function item_can_be_added_to_the_cart()
    {
        //Create 3 products in the database
        Product::factory()->count(3)->create();

        /**
         * When there will be a post request to /cart, the CartController@store will be called. This method
         * pushes the given item into the session cart. So, the cart is not saved into the database, but it is
         * saved in the session. And since the app uses blade files for the FE, therefore the blade FE
         * has access to data in the session. We redirect the user to the same page, however now the cart
         * is not empty, but it has the item that was added.
         */
        $response = $this->post('/cart', ['id' => 1]);
        $response->assertRedirect('/cart');
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('cart.0', [
            'id' => 1,
            'qty' => 1,
        ]);
    }

    /** @test */
    public function same_item_can_not_be_added_to_the_cart_again()
    {
        //Create 3 products in the database
        Product::factory()->create([
            'name' => 'Taco',
            'cost' => 1.5,
        ]);
        Product::factory()->create([
            'name' => 'Pizza',
            'cost' => 2.1,
        ]);
        Product::factory()->create([
            'name' => 'BBQ',
            'cost' => 3.2,
        ]);

        //Add Taco to the cart
        $this->post('/cart', [
            'id' => 1, // Taco
        ]);

        //Add Taco to the cart again
        $this->post('/cart', [
            'id' => 1, // Taco
        ]);

        //Add Pizza to the cart
        $this->post('/cart', [
            'id' => 2, // Pizza
        ]);

        //Although 3 items were added to the cart, only two items should be in the cart: Taco and Pizza
        $this->assertEquals(2, count(session('cart')));
    }

    /** @test */
    public function items_added_to_the_cart_can_be_seen_in_the_cart_page()
    {

        Product::factory()->create([
            'name' => 'Taco',
            'cost' => 1.5,
        ]);
        Product::factory()->create([
            'name' => 'BBQ',
            'cost' => 3.2,
        ]);

        $this->post('/cart', [
            'id' => 1, // Taco
        ]);
        $this->post('/cart', [
            'id' => 3, // BBQ
        ]);

        $cart_items = [
            [
                'id' => 1,
                'qty' => 1,
                'name' => 'Taco',
                'image' => 'some-image.jpg',
                'cost' => 1.5,
            ],
            [
                'id' => 3,
                'qty' => 1,
                'name' => 'BBQ',
                'image' => 'some-image.jpg',
                'cost' => 3.2,
            ],
        ];

        $response = $this->get('/cart');
        $response->assertViewHas('cart_items', $cart_items);
        $response->assertSeeTextInOrder([
                'Taco',
                'BBQ',
            ]);

    }
}
