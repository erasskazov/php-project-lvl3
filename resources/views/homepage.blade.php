@extends('layouts.app')

@section('content')
<div class="row mt-5">
    <div class="col-12 col-md-10 col-lg-8 mx-auto border rounded-3 bg-light p-5">
        <h1 class="display-3">Анализатор страниц</h1>
        <div class="lead mb-3">Бесплатно проверяйте сайты на SEO пригодность</div>
        @include('url.form')
    </div>
</div>
@endsection