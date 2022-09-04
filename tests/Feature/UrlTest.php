<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;


    protected $urlId;
    protected $urlData;


    protected function setUp(): void
    {
        parent::setUp();
        $this->urlData = ['name' => 'https://test.com'];
        $this->urlId = DB::table('urls')->insertGetId($this->urlData);
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
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $url);
    }

    public function testStoreIncorrecrUrl()
    {
        $url = ['name' => 'incorrect url'];
        $response = $this->post(route('urls.store'), compact('url'));
        $response->assertSessionHasErrors();
        $response->assertRedirect(route('homepage'));
    }
}
