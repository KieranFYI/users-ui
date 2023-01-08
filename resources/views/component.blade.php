@isset($component->view)
    @include($component->view['template'], $component->view['arguments'])
@endisset

@if(!empty($component->html))
    {!! $component->html !!}
@endif