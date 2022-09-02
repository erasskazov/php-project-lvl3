<form action="{{ route('urls.store') }}" method="post" class="d-flex justify-content-center">
    @csrf
    <input type="text" name="url[name]" value="{{ $url['name'] }}" class="form-control form-control-lg" placeholder="https://www.example.com">
    <input type="submit" class="btn btn-primary btn-lg ms-3 px-5 text-uppercase mx-3" value="Проверить">
</form>