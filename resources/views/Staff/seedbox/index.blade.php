@extends('layout.default')

@section('title')
    <title>@lang('staff.seedboxes') - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('staff.seedboxes') - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.seedboxes.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.seedboxes')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <h2>@lang('staff.seedboxes')</h2>
            <hr>
            <p class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-list"></i>
                    @lang('staff.seedboxes')</strong></p>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('common.no')</th>
                            <th>@lang('common.user')</th>
                            <th>@lang('common.ip')</th>
                            <th>@lang('user.created-on')</th>
                            <th>@lang('common.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($seedboxes->count())
                            @foreach ($seedboxes as $key => $seedbox)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td><a href="{{ route('users.show', ['username' => $seedbox->user->username]) }}">
                                            {{ $seedbox->user->username }}
                                        </a>
                                    </td>
                                    <td class="text-success">{{ $seedbox->ip }}</td>
                                    <td>
                                        {{ $seedbox->created_at->toDayDateTimeString() }}
                                        ({{ $seedbox->created_at->diffForHumans() }})
                                    </td>
                                    <td>
                                        <form action="{{ route('staff.seedboxes.destroy', ['id' => $seedbox->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger"><i
                                                    class="{{ config('other.font-awesome') }} fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="text-center">
                    {{ $seedboxes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
