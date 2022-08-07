<?php

namespace Tests\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    const PRODUCT_STRUCTURE = [
        'id',
        'name',
        'sku',
        'quantity',
        'price',
        'category'
    ];

    public function testIndexReturnsData()
    {
        $this->json('GET', 'api/products')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                '*' => self::PRODUCT_STRUCTURE
            ]);
    }

    public function testShowReturnsNotFoundForNonExistedProduct()
    {
        $productId = 9999;
        $this->json('GET', "api/products/$productId")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testShowReturnsSingleProduct()
    {
        $productId = 1;
        $this->json('GET', "api/products/$productId")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::PRODUCT_STRUCTURE);
    }

    public function testStoreThrowsUnauthorizedForNonLoggedUser()
    {
        $this->json('POST', "api/products")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testStoreUnprocessableForLoggedIn()
    {
        $user = User::find(1);

        $this->actingAs($user)->json('POST', "api/products")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'sku', 'quantity', 'price'])
        ;
    }

    public function testStoreSuccessForLoggedIn()
    {
        $user = User::find(1);
        $productData = [
            'name' => 'Product999',
            'sku' => '123123123178',
            'price' => 77.66,
            'quantity' => 66,
            'category_id' => 1
        ];

        $this->actingAs($user)->json('POST', 'api/products', $productData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::PRODUCT_STRUCTURE)
            ->assertJsonPath('name', $productData['name'])
            ->assertJsonPath('sku', $productData['sku'])
            ->assertJsonPath('price', $productData['price'])
            ->assertJsonPath('quantity', $productData['quantity'])
            ->assertJsonPath('category.id', $productData['category_id'])
        ;
    }

    public function testUpdateThrowsUnauthorizedForNonLoggedUser()
    {
        $productId = 1;
        $this->json('PUT', "api/products/$productId")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateSuccessForLoggedIn()
    {
        $user = User::find(1);

        $productId = 1;
        $newProduct = Product::factory()->make();
        $arr = $newProduct->toArray();

        $this->actingAs($user)->json('PUT', "api/products/$productId", $arr)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($arr);
    }

    public function testDestroyDestroysSuccessForLoggedUser()
    {
        $user = User::find(1);
        $productId = 1;

        $this->actingAs($user)->json('DELETE', "api/products/$productId")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $afterProduct = Product::find(1);
        $this->assertNull($afterProduct);
    }

    public function testDestroyThrowsUnauthorizedForNonLoggedUser()
    {
        $productId = 1;
        $this->json('DELETE', "api/products/$productId")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testTotalReturnsValue()
    {
        $this->json('GET', 'api/products/total')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['total']);
    }
}
