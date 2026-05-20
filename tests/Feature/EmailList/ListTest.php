<?php

namespace Tests\Feature\EmailList;

use App\Models\EmailList;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ListTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->login();
    }

    public function test_it_needs_to_be_authenticated(): void
    {
        Auth::logout();
        $this->getJson(route('email-lists.index'))->assertUnauthorized();
    }


    public function test_it_should_be_paginate(): void
    {
        EmailList::factory()->count(40)->create();

        $response = $this->get(route('email-lists.index'));

        $response->assertViewHas('emailLists', function($list) {
            $this->assertInstanceOf(LengthAwarePaginator::class, $list);

            $this->assertCount(5, $list);

            return true;
        });
    }

    public function test_it_should_be_able_to_search_a_list()
    {
        EmailList::factory()->count(10)->create();
        EmailList::factory()->create(['title' => 'Title 1']);
        $emailList = EmailList::factory()->create(['title' => 'Title Testing 2']);

        $response = $this->get(route('email-lists.index', ['search' => 'Testing 2']));

        $response->assertViewHas('emailLists', function($list) use ($emailList) {
            $this->assertCount(1, $list);

            $this->assertEquals($emailList->id, $list->first()->id);

            return true;
        });
    }
}
