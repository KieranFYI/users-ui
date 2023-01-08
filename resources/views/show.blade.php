@extends('adminlte::page')

@section('content')
    <div class="row pt-3">
        <div class="col-md-3">

            <div class="card card-primary card-outline">
                <div class="card-body box-profile p-0">
                    <div id="users-ui">
                        <user-details
                                :user-data='@json($user)'
                                :has-info="{{ $infos->isEmpty() ? 'true' : 'false' }}"
                                endpoint="{{ route('admin.users.update', $user) }}"
                        />
                    </div>
                    <ul class="list-group list-group-unbordered mb-1">
                        @foreach($infos as $info)
                            <li class="list-group-item px-2 py-1 @if($loop->first)border-top-0 @endif @if($loop->last)border-bottom-0 @endif">
                                {!! $info !!}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @foreach($sidebars as $sidebar)
                {!! $sidebar !!}
            @endforeach

        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-body text-center text-danger p-4">
                    @if($tabs->isEmpty())
                        No content found.
                    @else
                        @include('users-ui::show-tabs')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ mix('vue.js', 'vendor/kieranfyi/users-ui') }}"></script>
@endsection