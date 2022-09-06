<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DiDom\Document;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {
    $url = $request->old('url') ?? ['name' => ''];
    return view('homepage', compact('url'));
})->name('homepage');


Route::post('urls', function (Request $request) {
    $urlData = $request->input('url');
    $urlData['created_at'] = Carbon::now();

    $validator = Validator::make(
        $urlData,
        ['name' => 'url|max:255'],
        ['url' => 'Некорректный URL', 'max' => 'Некорректный URL']
    );

    if ($validator->fails()) {
        return redirect(route('homepage'))
                    ->withErrors($validator)
                    ->withInput();
    }

    $tableUrlId = DB::table('urls')->where('name', $urlData['name'])->value('id');

    if ($tableUrlId) {
        $id = $tableUrlId;
        $flashMessage = 'Страница уже существует';
    } else {
        $id = DB::table('urls')->insertGetId($urlData);
        $flashMessage = 'Страница успешно добавлена';
    }

    session()->flash('success', $flashMessage);

    return redirect(route('urls.show', compact('id')));
})->name('urls.store');


Route::get('urls', function () {
    $urls = DB::table('urls')->paginate(15);
    $lastChecks = [];
    foreach ($urls as $url) {
        $lastChecks[$url->id] = DB::table('url_checks')
            ->where('url_id', '=', $url->id)
            ->latest()
            ->first();
    }
    return view('url.index', compact('urls', 'lastChecks'));
})->name('urls.index');


Route::post('urls/{id}/checks', function ($id) {
    $urlName = DB::table('urls')->find($id)->name;

    try {
        $urlResponse = Http::timeout(3)->retry(3, 100)->get($urlName);
    } catch (ConnectionException | RequestException $e) {
        session()->flash('error', 'Сервер не доступен');
        return redirect(route('urls.show', $id));
    }

    $status = $urlResponse->status();
    $parsedHtml = new Document($urlResponse->body());
    $title = optional($parsedHtml->first('title'))->text();
    $h1 = optional($parsedHtml->first('h1'))->text();
    $description = optional($parsedHtml->first('[name="description"]'))->getAttribute('content');

    $urlCheckData = [
        'url_id' => $id,
        'title' => $title,
        'h1' => $h1,
        'description' => $description,
        'status_code' => $status,
        'created_at' => Carbon::now()
    ];

    DB::table('url_checks')->insert($urlCheckData);
    session()->flash('success', 'Страница успешно проверена');

    return redirect(route('urls.show', $id));
})->name('urls.checks.store');


Route::get('urls/{id}', function ($id) {
    $url = DB::table('urls')->find($id);
    $urlChecks = DB::table('url_checks')
        ->where('url_id', '=', $id)
        ->get()
        ->sortDesc();
    if ($url) {
        return view('url.show', compact('url', 'urlChecks'));
    }
    abort(404);
})->name('urls.show');
