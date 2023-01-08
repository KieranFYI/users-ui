<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            @foreach($tabs as $tab)
                <li class="nav-item">
                    <a class="nav-link @if($tab['active'] || ($loop->first && request()->get('tab') === null))active @endif" href="#{{ $tab['id'] }}"
                       data-toggle="tab">
                        {{ $tab['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            @foreach($tabs as $tab)
                <div class="tab-pane @if($tab['active'] || ($loop->first && request()->get('tab') === null))active @endif" id="{{ $tab['id'] }}">
                    @include('users-ui::component', ['component' => $tab])
                </div>
            @endforeach
        </div>
    </div>
</div>