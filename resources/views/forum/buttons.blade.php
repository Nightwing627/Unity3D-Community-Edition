<div class="button-left">
    <a href="{{ route('forums.index') }}" class="btn btn-sm btn-primary">
        <i class="{{ config('other.font-awesome') }} fa-comments"></i> @lang('forum.forums')
    </a>
    <a href="{{ route('forum_latest_topics') }}" class="btn btn-sm btn-primary">
        <i class="{{ config('other.font-awesome') }} fa-book"></i> @lang('common.latest-topics')
    </a>
    <a href="{{ route('forum_latest_posts') }}" class="btn btn-sm btn-primary">
        <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('common.latest-posts')
    </a>
    <a href="{{ route('forum_subscriptions') }}" class="btn btn-sm btn-primary">
        <i class="{{ config('other.font-awesome') }} fa-clone"></i> @lang('common.subscriptions')
    </a>
    <a href="{{ route('forum_search_form') }}" class="btn btn-sm btn-primary">
        <i class="{{ config('other.font-awesome') }} fa-clone"></i> @lang('common.search')
    </a>
</div>
