<?php

namespace Tests\Feature\EmailList;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_title_should_be_required(): void
    {
         /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('email-lists.store'), [])
            ->assertSessionHasErrors(['title' => 'The title field is required.']);
    }

     public function test_title_should_have_a_max_of_255_characters()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post(route('email-lists.store'), ['title' => str_repeat('*', 256)])
            ->assertSessionHasErrors(['title' => 'The title field must not be greater than 255 characters.']);
    }

    public function test_file_should_be_required()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post(route('email-lists.store'), [])
            ->assertSessionHasErrors(['file' => 'The file field is required.']);
    }


    public function test_it_should_to_be_able_to_create_an_email_list(): void
    {

         /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'title' => 'Test List',
            'file' =>  UploadedFile::fake()->createWithContent('sample_names.csv',
            <<<'CSV'
            Name,Email
            Joe Doe,joe.doe@example.com
            CSV),
        ];



        $request = $this->post(route('email-lists.store'), $data);

        $request->assertRedirectToRoute('email-lists.index');

        $this->assertDatabaseHas('email_lists', [
            'title' => 'Test List',
        ]);
        $this->assertDatabaseHas('subscribers', [
            'email_list_id' => 1,
            'name' => 'Joe Doe',
            'email' => 'joe.doe@example.com',
        ]);
    }
}
