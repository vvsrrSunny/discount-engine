<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('applies discount correctly', function () {
    $this->artisan('db:seed');
    // Prepare request payload
    $payload = [
        "subTotal" => 34.98,
        "items" => [
            ["id" => 3],
            ["id" => 5]
        ]
    ];

    // Make POST request and assert response
    postJson(route('discounts.apply'), $payload)
        ->assertStatus(200)
        ->assertJson([
            "discounted_price" => 32.7315
        ]);
});
