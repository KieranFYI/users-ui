<div class="card-header p-2">
    <ul class="nav nav-pills">
        @foreach($tabs as $tab)
            <li class="nav-item">
                <a class="nav-link @if($tab['active'])active @endif" href="#{{ $tab['id'] }}"
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
            <div class="tab-pane @if($tab['active'])active @endif" id="{{ $tab['id'] }}">
                {!! $tab['content'] !!}
            </div>
        @endforeach
    </div>
</div>