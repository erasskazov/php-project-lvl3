@extends('layouts.app')

@section('content')
    <h1 class="display-5 mt-5">Сайт {{ $url->name }}</h1>
    <div class="table-responsive mt-5">
        <table class="table table-bordered table-hover text-nowrap">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td>{{ $url->id }}</td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td>{{ $url->name }}</td>
                </tr>
                <tr>
                    <td>Дата создания</td>
                    <td> {{ $url->created_at }} </td>
                </tr>
            </tbody>
        </table>
    </div>
    <h2 class="mt-5 mb-3">Проверки</h2>
    <form action="{{ route('urls.checks.store', $url->id) }}" method="post">
        @csrf
        <input type="submit" class="btn btn-primary" value="Запустить проверку">
    </form>
    <table class="table table-bordered table-hover text-nowrap mt-5">
        <tbody>
            <tr>
                <th>ID</th>
                <th>Код ответа</th>
                <th>h1</th>
                <th>title</th>
                <th>description</th>
                <th>Дата создания</th>
            </tr>
            
            @foreach ($urlChecks as $urlCheck)
                <tr>
                    <th>{{ $urlCheck->id }}</th>
                    <th>{{ $urlCheck->status_code }}</th>
                    <th>{{ $urlCheck->h1 }}</th>
                    <th>{{ $urlCheck->title }}</th>
                    <th>{{ $urlCheck->description }}</th>
                    <th>{{ $urlCheck->created_at }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection<tr></tr>