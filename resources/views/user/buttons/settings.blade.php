<div class="button-holder">
    <div class="button-left">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.profile')
        </a>
        <a href="{{ route('user_settings', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.general')
        </a>
        <a href="{{ route('user_security', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.security')
        </a>
        <a href="{{ route('user_privacy', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.privacy')
        </a>
        <a href="{{ route('user_notification', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
            @lang('user.notification')
        </a>
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
