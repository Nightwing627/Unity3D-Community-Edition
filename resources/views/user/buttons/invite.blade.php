<div class="button-holder">
    <div class="button-left">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('invites.index', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
                @lang('user.invites')
            </a>
            <a href="{{ route('invites.create') }}" class="btn btn-sm btn-success">
                <i class="{{ config('other.font-awesome') }} fa-gift"></i> @lang('user.send-invite')
            </a>
        @endif
    </div>
    <div class="button-right">
        @if(auth()->user()->id == $user->id)
            <a href="{{ route('user_settings', ['username' => $user->username]) }}" class="btn btn-sm btn-danger">
                @lang('user.settings')
            </a>
            <a href="{{ route('user_edit_profile_form', ['username' => $user->username]) }}">
                <button class="btn btn-sm btn-danger">@lang('user.edit-profile')</button></a>
        @endif
    </div>
</div>
