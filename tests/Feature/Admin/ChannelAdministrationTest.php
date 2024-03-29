<?php
namespace Tests\Feature\Admin;
use App\Channel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
class ChannelAdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling();
    }
    /** @test */
    public function an_administrator_can_access_the_channel_administration_section()
    {
        $this->signInAdmin()
        # replaced by above from TestCase
                //        $administrator = factory('App\User')->create();
                //        config(['forum.administrators' => [ $administrator->email ]]);
                //        $this->actingAs($administrator)
            ->get('/admin/channels')
            ->assertStatus(Response::HTTP_OK);
    }
    /** @test */
    public function non_administrators_cannot_access_the_channel_administration_section()
    {
        $regularUser = factory(User::class)->create();
        $this->actingAs($regularUser)
            ->get(route('admin.channels.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
        $this->actingAs($regularUser)
            ->get(route('admin.channels.create'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
    /** @test */
    public function an_administrator_can_create_a_channel()
    {
        $response = $this->createChannel([
            'name' => 'php',
            'description' => 'This is the channel for discussing all things PHP.',
        ]);
        $this->get($response->headers->get('Location'))
            ->assertSee('php')
            ->assertSee('This is the channel for discussing all things PHP.');
    }

    /** @test */
    public function an_administrator_can_edit_an_existing_channel()
    {
        $this->signInAdmin();
        $this->patch(
            route('admin.channels.update', ['channel' => create(\App\Channel::class)->slug]),
            $updatedChannel = [
                'name' => 'altered',
                'description' => 'altered channel description',
                'color' => '#00ff00',
                'archived' => true
            ]
        );
        $this->get(route('admin.channels.index'))
            ->assertSee($updatedChannel['name'])
            ->assertSee($updatedChannel['description']);
    }

//    /** @test */
//    public function an_administrator_can_mark_an_existing_channel_as_archieved()
//    {
//        $this->signInAdmin();
//
//        $channel = create('App\Channel');
//
//        $this->assertFalse($channel->archived);
//
//        $this->patch(
//            route('admin.channels.update', ['channel' => $channel->slug]),
//            [
//                'name' => 'altered',
//                'description' => 'altered channel description',
//                'archived' => true
//            ]
//        );
//
//        $this->assertTrue($channel->fresh()->archived);
//    }

    /** @test */
    public function the_path_to_a_channel_is_unaffected_by_its_archived_status()
    {
        $thread = create('App\Thread');

        $path = $thread->path();

        $thread->channel->archive();

        $this->assertEquals($path, $thread->fresh()->path());
    }

    /** @test */
    public function a_channel_requires_a_name()
    {
        $this->createChannel(['name' => null])
            ->assertSessionHasErrors('name');
    }
    /** @test */
    public function a_channel_requires_a_description()
    {
        $this->createChannel(['description' => null])
            ->assertSessionHasErrors('description');
    }
    protected function createChannel($overrides = [])
    {
        $this->signInAdmin();
        #replaced by above in TestCase.php
            //        $administrator = create('App\User');
            //        config(['forum.administrators' => [ $administrator->email ]]);
            //        $this->signIn($administrator);

        $channel = make(Channel::class, $overrides);

        return $this->post('/admin/channels', $channel->toArray());
    }
}
