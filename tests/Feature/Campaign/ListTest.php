<?php

use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use function Pest\Laravel\{get, getJson};

beforeEach(function() {
    login();
});

it('only logged users can access the campaigns', function(){
    Auth::logout();
    getJson(route('campaigns.index'))
        ->assertUnauthorized();
});

it('should ble possible see the entire list of campaigns', function() {
    Campaign::factory()->count(5)->create();


    get(route('campaigns.index'))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(5);

            return true;
        });
});

it('should be able to search a campaign by name', function () {
    Campaign::factory()->count(5)->create();
    Campaign::factory()->create(['name' => 'Charlie Smith', 'deleted_at' => null]);

    get(route('campaigns.index', ['search' => 'Charlie']))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(1);

            expect($value)->first()->id->toBe(6);

            return true;
        });
});

it('should ble able to search by id', function(){
    Campaign::factory()->create(['name' => 'Joe Doe', 'deleted_at' => null]);
    Campaign::factory()->create(['name' => 'Jane Doe', 'deleted_at' => null]);

    // Filtrar com ID
    get(route('campaigns.index', ['search' => 2]))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(1);

            expect($value)->first()->id->toBe(2);

            return true;
        });
});

it('should ble able to show deleted records', function(){
    Campaign::factory()->create(['deleted_at' => now()]);

    Campaign::factory()->create();

    get(route('campaigns.index'))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(1);

            return true;
        });

    get(route('campaigns.index', ['withTrashed' => 1]))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(2);

            return true;
        });
});


it('should ble paginated', function(){
    Campaign::factory()->count(30)->create();

    Campaign::factory()->create();

    get(route('campaigns.index'))
        ->assertViewHas('campaigns', function ($value) {

            expect($value)->count(15);
            expect($value)->toBeInstanceOf(LengthAwarePaginator::class);

            return true;
        });
});
