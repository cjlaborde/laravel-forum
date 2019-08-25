<?php
//
//namespace Tests\Feature;
//
//
//use App\Thread;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//use Tests\TestCase;
//
//
//
//class SearchTest extends TestCase
//{
//
//    use RefreshDatabase;
//    /**
//     * A basic feature test example.
//     *
//     * @return void
//     */
//    /** @test */
//    public function a_user_can_search_threads()
//    {
//        config(['scout.driver' => 'algolia']);
//        $this->withoutExceptionHandling();
//        # given we have 4 threads and few of them contain a particular keyword.
//        create('App\Thread', [], 2);
//        create('App\Thread', ['body' => 'A thread with the foobar term.'], 2);
//        # use do or else you going to get errors to to latency due to using algolia
//        do {
//            sleep(.25);
//             # if return empty means it has not index yet so do it again
//            $results = $this->getJson('/threads/search?q=foobar')->json()['data'];
//        } while (empty($results));
//        $this->assertCount(2, $results);
//        Thread::latest()->take(4)->unsearchable();
//    }
//}
//# it's better to use test in only one place and use null driver later. This test to see if the API actually working.
//# Or else if they make changes to API lots of tests will fail
//
//
//
//
/////** @test */
////public function a_user_can_search_threads()
////{
////    config(['scout.driver' => 'algolia']);
//////        $this->withoutExceptionHandling();
////    $search = 'foobar';
////    # given we have 4 threads and few of them contain a particular keyword.
////    create('App\Thread', [], 2);
////
//////        $desiredThreads = create('App\Thread', ['body' => "A thread with the {$search} term."], 2);
////    create('App\Thread', ['body' => "A thread with the {$search} term."], 2);
////
////    $results = $this->getJson("/threads/search?q={$search}")->json();
////
//////        dd($results['data']);
////
////    $this->assertCount(2, $results['data']);
////
////    # we are calling a Trait called Macroable that dynamically hooks into the class and adds additional functionality.
////    # follow Map bellow by following till you find the unsearchable
////    # app/Threads Searchable ----> Laravel\Scout\Searchable; ---> function bootSearchable() --->registerSearchableMacros --->  BaseCollection::macro('unsearchable', function () use ($self) {
//////        $desiredThreads->unsearchable();
////
////    # another alternative is strip the latest out of algolia
////    Thread::latest()->take(4)->unsearchable();
////
////
////}
