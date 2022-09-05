<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

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


Route::post('/', function (Request $request) {
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
                    ->withInput($request->input());
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
            ->orderByDesc('created_at')
            ->value('created_at');
    }
    return view('url.index', compact('urls', 'lastChecks'));
})->name('urls.index');


Route::post('urls/{id}/checks', function ($id) {
    $urlCheckData = [
        'url_id' => $id,
        'created_at' => Carbon::now()
    ];
    DB::table('url_checks')->insert($urlCheckData);
    session()->flash('success', 'Страница успешно проверена');
    return redirect(route('urls.show', $id));
})->name('urls.checks.store');


Route::get('urls/{id}', function ($id) {
    $url = DB::table('urls')->find($id);
    $url_checks = DB::table('url_checks')
        ->where('url_id', '=', $id)
        ->get()
        ->sortDesc();
    if ($url) {
        return view('url.show', compact('url', 'url_checks'));
    }
    abort(404);
})->name('urls.show');
