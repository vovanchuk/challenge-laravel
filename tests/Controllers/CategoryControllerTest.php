<?php

namespace Tests\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    const CATEGORY_STRUCTURE = [
        'id',
        'name'
    ];

    public function testIndexReturnsData()
    {
        $this->json('GET', 'api/categories')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                '*' => self::CATEGORY_STRUCTURE
            ]);
    }

    public function testShowReturnsNotFoundForNonExistedCategory()
    {
        $categoryId = 9999;
        $this->json('GET', "api/categories/$categoryId")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testShowReturnsSingleCategory()
    {
        $categoryId = 1;
        $this->json('GET', "api/categories/$categoryId")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::CATEGORY_STRUCTURE);
    }

    public function testStoreThrowsUnauthorizedForNonLoggedUser()
    {
        $this->json('POST', "api/categories")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testStoreUnprocessableForLoggedIn()
    {
        $user = User::find(1);

        $this->actingAs($user)->json('POST', "api/categories")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name'])
        ;
    }

    public function testStoreSuccessForLoggedIn()
    {
        $user = User::find(1);
        $categoryName = 'Category';

        $this->actingAs($user)->json('POST', 'api/categories', ['name' => $categoryName])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::CATEGORY_STRUCTURE)
            ->assertJsonPath('name', $categoryName);
    }

    public function testUpdateThrowsUnauthorizedForNonLoggedUser()
    {
        $categoryId = 1;
        $this->json('PUT', "api/categories/$categoryId")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateUnprocessableForLoggedIn()
    {
        $user = User::find(1);
        $categoryId = 1;

        $this->actingAs($user)->json('PUT', "api/categories/$categoryId")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testUpdateSuccessForLoggedIn()
    {
        $user = User::find(1);
        $categoryName = 'New Category';
        $categoryId = 1;

        $this->actingAs($user)->json('PUT', "api/categories/$categoryId", ['name' => $categoryName])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::CATEGORY_STRUCTURE)
            ->assertJsonPath('name', $categoryName);
    }

    public function testDestroyDestroysSuccessForLoggedUser()
    {
        $user = User::find(1);
        $categoryId = 1;

        $this->actingAs($user)->json('DELETE', "api/categories/$categoryId")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $afterCategory = Category::find(1);
        $this->assertNull($afterCategory);
    }

    public function testDestroyThrowsUnauthorizedForNonLoggedUser()
    {
        $categoryId = 1;
        $this->json('DELETE', "api/categories/$categoryId")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
