<div class="col-sm-2 col-lg-2">
    <div class="block">
        <ul class="nav nav-list">

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-link"></i> @lang('staff.links')
            </li>
            <li>
                <a href="{{ route('home.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.frontend')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.dashboard.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.staff-dashboard')
                </a>
            </li>
            @if (auth()->user()->group->is_owner)
                <li>
                    <a href="{{ route('staff.backups.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-hdd"></i> @lang('backup.backup')
                        @lang('backup.manager')
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.commands.index') }}">
                        <i class="fab fa-laravel"></i> Commands
                    </a>
                </li>
            @endif

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.chat-tools')
            </li>
            <li>
                <a href="{{ route('staff.statuses.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-comment-dots"></i> @lang('staff.statuses')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.rooms.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-comment-dots"></i> @lang('staff.rooms')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.bots.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-robot"></i> @lang('staff.bots')
                </a>
            </li>
            <li>
                <form role="form" method="POST" action="{{ route('staff.flush.chat') }}" style="padding: 10px 15px;">
                    @csrf
                    <i class="{{ config('other.font-awesome') }} fa-broom"></i>
                    <button type="submit" class="btn btn-xs btn-info" style="margin-bottom: 5px;">
                        @lang('staff.flush-chat')
                    </button>
                </form>
            </li>

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.general-tools')
            </li>
            <li>
                <a href="{{ route('staff.articles.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-newspaper"></i> @lang('staff.articles')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.applications.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-list"></i> @lang('staff.applications')
                    <span class="badge badge-danger"> {{ $apps->pending }} </span>
                </a>
            </li>
            @if (auth()->user()->group->is_admin)
                <li>
                    <a href="{{ route('staff.forums.index') }}">
                        <i class="fab fa-wpforms"></i> @lang('staff.forums')
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.groups.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-users"></i> @lang('staff.groups')
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('staff.internals.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-magic"></i> Internals
                </a>
            </li>
            <li>
                <a href="{{ route('staff.pages.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.pages')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.polls.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-chart-pie"></i> @lang('staff.polls')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.rss.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-rss"></i> @lang('staff.rss')
                </a>
            </li>
            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.torrent-tools')
            </li>
            <li>
                <a href="{{ route('staff.moderation.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-moderation')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.categories.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-categories')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.types.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-types')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.resolutions.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('staff.torrent-resolutions')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.media_languages.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-columns"></i> @lang('common.media-languages')
                </a>
            </li>
            <li>
                <form role="form" method="POST" action="{{ route('staff.flush.peers') }}" style="padding: 10px 15px;">
                    @csrf
                    <i class="{{ config('other.font-awesome') }} fa-ghost"></i>
                    <button type="submit" class="btn btn-xs btn-info" style="margin-bottom: 5px;">
                        @lang('staff.flush-ghost-peers')
                    </button>
                </form>
            </li>

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i> @lang('staff.user-tools')
            </li>
            <li>
                <a href="{{ route('user_search') }}">
                    <i class="{{ config('other.font-awesome') }} fa-users"></i> @lang('staff.user-search')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.watchlist.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-eye"></i> Watchlist
                </a>
            </li>
            <li>
                <a href="{{ route('staff.gifts.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-gift"></i> @lang('staff.user-gifting')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.mass-pm.create') }}">
                    <i class="{{ config('other.font-awesome') }} fa-envelope-square"></i> @lang('staff.mass-pm')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.mass-actions.validate') }}">
                    <i class="{{ config('other.font-awesome') }} fa-history"></i> @lang('staff.mass-validate-users')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.cheaters.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-question"></i>
                    @lang('staff.possible-leech-cheaters')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.seedboxes.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-server"></i> @lang('staff.seedboxes')
                </a>
            </li>

            <li class="nav-header head">
                <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.logs')
            </li>
            <li>
                <a href="{{ route('staff.audits.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.audit-log')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.bans.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.bans-log')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.authentications.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.failed-login-log')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.invites.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.invites-log')
                </a>
            </li>
            <li>
                <a href="{{ route('staff.notes.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.user-notes')
                </a>
            </li>
            @if (auth()->user()->group->is_owner)
                <li>
                    <a href="/staff/log-viewer">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.laravel-log')
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('staff.reports.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.reports-log')
                    <span class="badge badge-danger"> {{ $reports->unsolved }} </span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.warnings.index') }}">
                    <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('staff.warnings-log')
                </a>
            </li>

        </ul>
    </div>
</div>
