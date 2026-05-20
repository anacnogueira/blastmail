<?php

namespace Tests\Feature\EmailList;

use App\Models\EmailList;
use App\Models\Subscriber;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->login();
    }

    public function test_it_should_be_able_to_delete_an_email_list(): void
    {
        $emailList = EmailList::factory()->create();

        $subscribers = Subscriber::factory()->count(10)->create(['email_list_id' => $emailList->id]);

        $response = $this->delete(route('email-lists.destroy', $emailList));

        $response->assertRedirect(route('email-lists.index'));

        $this->assertSoftDeleted('email_lists', ['id' => $emailList->id]);

        foreach($subscribers as $subscriber) {
            $this->assertSoftDeleted('subscribers', ['id' => $subscriber->id]);
        }
    }
}
