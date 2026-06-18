<?php

use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use function Pest\Laravel\{get, getJson};

beforeEach(function() {
    login();
});

it('only logged users can access the templates', function(){
    Auth::logout();
    getJson(route('templates.index'))
        ->assertUnauthorized();
});

it('should ble possible see the entire list of templates', function() {
    Template::factory()->count(5)->create();


    get(route('templates.index'))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(5);

            return true;
        });
});

it('should be able to search a template by name', function () {
    Template::factory()->count(5)->create();
    Template::factory()->create(['name' => 'Charlie Smith']);

    get(route('templates.index', ['search' => 'Charlie']))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(1);

            expect($value)->first()->id->toBe(6);

            return true;
        });
});

it('should ble able to search by id', function(){
    Template::factory()->count(5)->create(['name' => 'Template Test']);
    Template::factory()->count(5)->create(['name' => 'Template Test 2']);

    // Filtrar com ID
    get(route('templates.index', ['search' => 2]))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(1);

            expect($value)->first()->id->toBe(2);

            return true;
        });
});

it('should ble able to show deleted records', function(){
    Template::factory()->create(['deleted_at' => now()]);

    Template::factory()->create();

    get(route('templates.index'))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(1);

            return true;
        });

    get(route('templates.index', ['withTrashed' => 1]))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(2);

            return true;
        });
});


it('should ble paginated', function(){
    Template::factory()->count(30)->create();

    Template::factory()->create();

    get(route('templates.index'))
        ->assertViewHas('templates', function ($value) {

            expect($value)->count(15);
            expect($value)->toBeInstanceOf(LengthAwarePaginator::class);

            return true;
        });
});

