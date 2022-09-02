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

    $id = DB::table('urls')->insertGetId($urlData);
    return redirect(route('urls.show', compact('id')));
})->name('urls.store');


Route::get('urls', function () {
    $urls = DB::table('urls')->paginate(15);
    return view('url.index', compact('urls'));
})->name('urls.index');


Route::get('urls/{id}', function ($id) {
    $url = DB::table('urls')->find($id);
    if ($url) {
        return view('url.show', compact('url'));
    }
    abort(404);
})->name('urls.show');
