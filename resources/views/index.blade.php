@extends('adminlte::page')

@section('content')
    <div class="row pt-3">
        <div class="col-12" id="vuejs-users-ui">
            <user-index />
        </div>
    </div>
@endsection

@pushOnce('js')
    @routes('users')
    @routes('users-api')
    <script src="{{ mix('vue.js', 'vendor/kieranfyi/users-ui') }}"></script>
@endpushOnce