@extends('layout.default')

@section('title')
    <title>@lang('common.edit') @lang('forum.post') - {{ $topic->name }}
        - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $forum->name . ' - ' . trans('forum.edit-post') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('forum.forums')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forums.categories.show', ['id' => $category->id]) }}" itemprop="url"
            class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forums.show', ['id' => $forum->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $forum->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_topic', ['id' => $topic->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $topic->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_post_edit_form', ['id' => $topic->id, 'postId' => $post->id]) }}" itemprop="url"
            class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.edit') @lang('forum.post')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="forum box container">
        <div class="col-md-12">
            <h2>@lang('common.edit') @lang('forum.post') {{ strtolower(trans('forum.in')) }}
                : {{ $forum->name }}</h2>
            <form role="form" method="POST"
                action="{{ route('forum_post_edit', ['id' => $topic->id, 'postId' => $post->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="content"></label><textarea id="content" name="content" cols="30" rows="10" class="form-control">{{ $post->content }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">@lang('common.submit')</button>
            </form>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $(document).ready(function() {
            $('#content').wysibb();
        })
    </script>
@endsection
