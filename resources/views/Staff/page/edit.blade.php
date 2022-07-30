@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.pages.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.pages')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.pages.edit', ['id' => $page->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.edit') @lang('staff.page')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            @lang('common.edit')
            @lang('staff.page')
            {{ $page->name }}
        </h2>
        <form role="form" method="POST" action="{{ route('staff.pages.update', ['id' => $page->id]) }}">
            @csrf
            <div class="form-group">
                <label for="name">@lang('staff.page') @lang('common.name')</label>
                <label>
                    <input type="text" name="name" class="form-control" value="{{ $page->name }}">
                </label>
            </div>
    
            <div class="form-group">
                <label for="content">@lang('common.content')</label>
                <textarea name="content" id="content" cols="30" rows="10" class="form-control">{{ $page->content }}</textarea>
            </div>
    
            <button type="submit" class="btn btn-default">@lang('common.save')</button>
        </form>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $(document).ready(function() {
            $('#content').wysibb({});
        })
    
    </script>
@endsection
