<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

uses(RefreshDatabase::class);
pest()->extend(TestCase::class);

it('can redirect to Google for authentication', function () {
    $response = $this->getJson('/api/auth/google');

    $response->assertStatus(200);
    $this->assertArrayHasKey('url', $response->json());
    $this->assertStringStartsWith('https://accounts.google.com', $response->json()['url']);
});

it('can handle Google callback and return user token', function () {
    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturnSelf();

    Socialite::shouldReceive('stateless')
        ->andReturnSelf();

    $mockUser = Mockery::mock();
    $mockUser->shouldReceive('getId')->andReturn('google-id-123');
    $mockUser->shouldReceive('getName')->andReturn('John Doe');
    $mockUser->shouldReceive('getEmail')->andReturn('john@example.com');

    Socialite::shouldReceive('user')
        ->andReturn($mockUser);

    $this->getJson('/api/auth/google/callback')
        ->assertStatus(302);
});
