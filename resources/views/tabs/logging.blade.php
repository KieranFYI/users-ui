<div id="vuejs-users-tabs-logging">
    <logging endpoint="{{ route('admin.api.users.logs', $user) }}" />
</div>

@pushOnce('js')
<script src="{{ mix('vue-users-tabs-logging.js', 'vendor/kieranfyi/users-ui') }}"></script>
@endpushOnce