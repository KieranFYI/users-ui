@extends('adminlte::page')

@section('content')
    <div class="row pt-3">
        <div class="col-lg-12 col-xl-3">

            <div class="card card-primary card-outline">
                <div class="card-body box-profile p-0">
                    <div id="vuejs-users-ui">
                        <user-details
                                :user-data='@json($user)'
                                :has-info="{{ $infos->isEmpty() ? 'true' : 'false' }}"
                        />
                    </div>
                    <ul class="list-group list-group-unbordered mb-1">
                        @foreach($infos as $component)
                            <li class="list-group-item px-2 py-1 @if($loop->first)border-top-0 @endif @if($loop->last)border-bottom-0 @endif">
                                @include('users-ui::component', ['component' => $component])
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @foreach($sidebars as $component)
                @include('users-ui::component', ['component' => $component])
            @endforeach

        </div>

        <div class="col-lg-12 col-xl-9">
            @if($tabs->isEmpty())
                <div class="card">
                    <div class="card-body text-center text-danger p-4">
                        No content found.
                    </div>
                </div>
            @else
                @include('users-ui::show-tabs')
            @endif
        </div>
    </div>
@endsection

@pushOnce('js')
    @routes('users-api')
    <script src="{{ mix('vue.js', 'vendor/kieranfyi/users-ui') }}"></script>
@endpushOnce