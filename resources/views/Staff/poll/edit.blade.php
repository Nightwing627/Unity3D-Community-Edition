@extends('layout.default')

@section('title')
    <title>Polls - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.polls.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('poll.polls')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.polls.edit', ['id'=> $poll->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('poll.edit-poll') {{$poll->title}}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="page-title">
                <h1>@lang('poll.edit-poll')</h1>
            </div>
            <div class="col-md-12">
                <div class="panel panel-chat">
                    <div class="panel-heading">
                        @lang('poll.edit-poll') {{$poll->title}}
                    </div>
                    <div class="panel-body">
                        @include('Staff.poll.forms.update')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
