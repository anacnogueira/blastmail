<?php
use App\Models\Template;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;

it('test it should be able to delete a template', function () {
    // Arrange
    login();

    $template = Template::factory()->create();

    // Act
    $response = delete(route('templates.destroy', ['template' => $template]));

    // Assert
    $response->assertRedirect(route('templates.index'));

    assertSoftDeleted('templates', ['id' => $template->id]);
});
