<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected $urlId;
    protected $urlData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlData = ['name' => 'https://test.com'];
        $this->urlId = DB::table('urls')->insertGetId($this->urlData);
        Http::fake();
    }

    public function testHomepage()
    {
        $response = $this->get(route('homepage'));
        $response->assertOk();
    }

    public function testIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testShow()
    {
        $response = $this->get(route('urls.show', $this->urlId));
        $response->assertOk();
        $response->assertSee($this->urlData['name']);
    }

    public function testStoreCorrectUrl()
    {
        $url = ['name' => 'https://test-store.com'];
        $response = $this->post(route('urls.store'), compact('url'));
        $this->assertDatabaseHas('urls', $url);
        $response->assertRedirect();
    }

    public function testStoreExictingUrl()
    {
        $url = $this->urlData;
        $response = $this->post(route('urls.store'), compact('url'));
        $response->assertRedirect();
        $dbUrls = DB::table('urls')->where('name', $url['name'])->get()->all();
        $this->assertCount(1, $dbUrls);
    }

    public function testStoreIncorrectUrl()
    {
        $url = ['name' => 'incorrect url'];
        $response = $this->post(route('urls.store'), compact('url'));
        $response->assertSessionHasErrors();
        $response->assertRedirect(route('homepage'));
    }

    public function testStoreUrlCheck()
    {
        $response = $this->post(route('urls.checks.store', $this->urlId));
        $this->assertDatabaseHas('url_checks', ['url_id' => $this->urlId]);
        $response->assertRedirect();
    }
}
