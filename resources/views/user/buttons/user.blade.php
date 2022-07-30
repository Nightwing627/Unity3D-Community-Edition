<div class="mt-20 text-center">
    <a href="{{ route('user_resurrections', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
        @lang('user.resurrections')
    </a>
    <a href="{{ route('torrents') }}?bookmarked=1" class="btn btn-sm btn-primary">
        @lang('user.bookmarks')
    </a>
    <a href="{{ route('wishes.index', ['username' => $user->username]) }}" class="btn btn-sm btn-primary">
        @lang('user.wishlist')
    </a>
    <a href="{{ route('seedboxes.index', ['username' => $user->username]) }}">
        <button class="btn btn-sm btn-primary">
            @lang('user.seedboxes')</button>
    </a>
    <a href="{{ route('invites.index', ['username' => $user->username]) }}"><span
            class="btn btn-sm btn-primary">@lang('user.invites')</span></a>
    <a href="{{ route('invites.create') }}">
        <button class="btn btn-sm btn-primary">@lang('user.send-invite')
        </button>
    </a>
</div>
