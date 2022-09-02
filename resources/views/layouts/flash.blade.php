@if ($errors->any())
    <div>
        <ul class="list-group">
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session()->has('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if (session()->has('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif