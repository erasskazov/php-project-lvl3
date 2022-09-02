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
@endsection