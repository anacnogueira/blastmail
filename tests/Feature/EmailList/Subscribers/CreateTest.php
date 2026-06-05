<?php
use App\Models\EmailList;
use App\Models\Subscriber;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

beforeEach(function () {
    login();

    $this->emailList = EmailList::factory()->create();

    $this->route = route('subscribers.store', $this->emailList);
});

it('should be able to create a new subscriber', function () {
    post($this->route, [
        'name' => 'Joe Doe',
        'email' => 'joe@doe.com',
    ])->assertRedirect(route('subscribers.index', $this->emailList));

    assertDatabaseHas('subscribers', [
        'email_list_id' => $this->emailList->id,
        'name' => 'Joe Doe',
        'email' => 'joe@doe.com',
    ]);
});

it('name should be required', function () {
    post($this->route, ['name' => null, 'email' => 'joe@doe.com',])
        ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'name'])]);
});

it('name should have a max of 255 character', function () {
    post($this->route, ['name' => str_repeat('*', 256), 'email' => 'joe@doe.com',])
        ->assertSessionHasErrors(['name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])]);
});

it('email should be required', function () {
     post($this->route, ['name' =>  'Joe Doe', 'email' => null,])
        ->assertSessionHasErrors(['email' => __('validation.required', ['attribute' => 'email'])]);
});

it('email should have a max of 255 character', function () {
   post($this->route, ['email' => str_repeat('a', 256).'@email.com'])
        ->assertSessionHasErrors(['email' => __('validation.max.string', ['attribute' => 'email', 'max' => 255])]);
});

it('email should be unique inside an email list', function () {
    Subscriber::factory()->create([
        'email_list_id' => $this->emailList->id,
        'email' => 'joe@doe.com',
    ]);

    post($this->route, ['name' => 'Jane Doe', 'email' => 'joe@doe.com'])
        ->assertSessionHasErrors(['email' => __('validation.unique', ['attribute' => 'email','email_list_id' => $this->emailList->id])]);
});
