<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostCommentTest extends TestCase
{
    protected string $uri = 'api/comments';

    public function test_name_is_required(): void
    {
        $this->postJson($this->uri)
            ->assertJsonValidationErrors('name');
    }

    public function test_message_is_required(): void
    {
        $this->postJson($this->uri)
            ->assertJsonValidationErrors('message');
    }

    public function test_name_is_not_longer_than_255_char(): void
    {
        $this->postJson($this->uri, [ 'name' => Str::random(256) ])
            ->assertJsonValidationErrors('name');
    }

    public function test_message_is_not_longer_than_255_char(): void
    {
        $this->postJson($this->uri, [ 'name' => Str::random(256) ])
            ->assertJsonValidationErrors('name');
    }

    public function test_can_create_comment(): void
    {
        $attributes = [
            'name'    => $this->faker->name,
            'message' => $this->faker->sentence
        ];

        $this->postJson($this->uri, $attributes)
            ->assertCreated();


        $this->assertDatabaseHas('comments', $attributes);
        $this->assertDatabaseCount('comments', 1);
    }


    public function test_can_create_nested_comment(): void
    {
        $comment = Comment::factory()->create();

        $attributes = [
            'name'      => $this->faker->name,
            'message'   => $this->faker->sentence,
            'parent_id' => $comment->id
        ];

        $this->postJson($this->uri, $attributes)
            ->assertCreated();

        $this->assertDatabaseHas('comments', $attributes);
        $this->assertDatabaseCount('comments', 2);
    }

    public function test_only_three_nested_comments_allowed(): void
    {
        $comment = Comment::factory()
            ->for(
                Comment::factory()
                    ->state([ 'depth' => 2 ])
                    ->for(Comment::factory()->create(), 'parent')
                    ->create()
                , 'parent'
            )
            ->state([ 'depth' => 3 ])
            ->create();

        $attributes = [
            'name'      => $this->faker->name,
            'message'   => $this->faker->sentence,
            'parent_id' => $comment->id,
            'depth'     => 3
        ];

        $this->postJson($this->uri, $attributes)
            ->assertUnprocessable();

        $this->assertDatabaseCount('comments', 3);
    }
}
