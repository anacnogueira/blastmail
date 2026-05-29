<?php

use Illuminate\Http\UploadedFile;

pest()->group('email-list');

beforeEach(function () {
    login();
});

test('title should be required', function () {
    $this->post(route('email-lists.store'), [])
        ->assertSessionHasErrors(['title' => 'The title field is required.']);
});

test('title should have a max of 255 characters', function () {
    $this->post(route('email-lists.store'), ['title' => str_repeat('*', 256)])
        ->assertSessionHasErrors(['title' => 'The title field must not be greater than 255 characters.']);
});

test('file should be required', function () {
    $this->post(route('email-lists.store'), [])
        ->assertSessionHasErrors(['file' => 'The file field is required.']);
});

test('it should be able create an email list', function () {
    // Arrange
    $data = [
        'title' => 'Email List Test',
        'file' => UploadedFile::fake()->createWithContent(
            'sample_names.csv',
            <<<'CSV'
                Name,Email
                Joe Doe,joe@doe.com
                CSV
        ),
    ];

    // Act
    $request = $this->post(route('email-lists.store'), $data);

    // Assert
    $request->assertRedirectToRoute('email-lists.index');

    $this->assertDatabaseHas('email_lists', [
        'title' => 'Email List Test',
    ]);

    $this->assertDatabaseHas('subscribers', [
        'email_list_id' => 1,
        'name' => 'Joe Doe',
        'email' => 'joe@doe.com',
    ]);
});
