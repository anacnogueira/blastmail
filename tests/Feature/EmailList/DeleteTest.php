<?php
    use App\Models\EmailList;
    use App\Models\Subscriber;
    use function Pest\Laravel\{delete, assertSoftDeleted};

    it ('should be able to delete an email list', function() 
    {
        login();

        $emailList = EmailList::factory()->create();

        $subscribers = Subscriber::factory()->count(10)->create(['email_list_id' => $emailList->id]);

        $response = delete(route('email-lists.destroy', $emailList));

        $response->assertRedirect(route('email-lists.index'));

        assertSoftDeleted('email_lists', ['id' => $emailList->id]);

        foreach($subscribers as $subscriber) {
            assertSoftDeleted('subscribers', ['id' => $subscriber->id]);
        }
    });

