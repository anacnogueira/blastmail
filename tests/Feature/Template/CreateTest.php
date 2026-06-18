<?php
use App\Models\Template;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

beforeEach(function () {
    login();

    $this->route = route('templates.store');
});

it('should be able to create a new template', function () {
    post($this->route, [
        'name' => 'Joe Doe',
        'body' => '<span>Hello World!</span>',
    ])->assertRedirect(route('templates.index'));

    assertDatabaseHas('templates', [
        'name' => 'Joe Doe',
        'body' => '<span>Hello World!</span>',
    ]);
});

it('name should be required', function () {
    post($this->route, ['name' => null, 'body' => '<span>Hello World!</span>'])
        ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'name'])]);
});

it('name should have a max of 255 character', function () {
    post($this->route, ['name' => str_repeat('*', 256), 'body' => '<span>Hello World!</span>'])
        ->assertSessionHasErrors(['name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])]);
});

it('body should be required', function () {
     post($this->route, ['name' =>  'Joe Doe', 'body' => null,])
        ->assertSessionHasErrors(['body' => __('validation.required', ['attribute' => 'body'])]);
});
