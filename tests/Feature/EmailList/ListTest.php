<?php

use App\Models\EmailList;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\{get, getJson};

pest()->group('email-list');

beforeEach(function()
{
    login();
});

test('it needs to be authenticated', function() {    
    Auth::logout();
    getJson(route('email-lists.index'))->assertUnauthorized();

    login();

    get(route('email-lists.index'))->assertSuccessful();
});

test('it should be paginate', function() {
    EmailList::factory()->count(40)->create();

    $response = get(route('email-lists.index'));

    $response->assertViewHas('emailLists', function($list) {
        expect($list)->toBeInstanceOf(LengthAwarePaginator::class);

        expect($list)->toHaveCount(5);

        return true;
    });
});

test('it should be able to search a list', function() {
    EmailList::factory()->count(10)->create();
    EmailList::factory()->create(['title' => 'Title 1']);
    $emailList = EmailList::factory()->create(['title' => 'Title Testing 2']);

    $response = get(route('email-lists.index', ['search' => 'Testing 2']));

    $response->assertViewHas('emailLists', function($list) use ($emailList) {
        expect($list)->toHaveCount(1);

        expect($emailList->id)->toBe($list->first()->id);

        return true;
    });
});  

