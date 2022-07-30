@extends('layout.default')

@section('title')
    <title>@lang('request.add-request') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ url('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('request.requests')</span>
        </a>
    </li>
    <li>
        <a href="{{ url('add_request_form') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('request.add-request')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @if ($user->can_request == 0)
            <div class="container">
                <div class="jumbotron shadowed">
                    <div class="container">
                        <h1 class="mt-5 text-center">
                            <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>
                            @lang('request.no-privileges')
                        </h1>
                        <div class="separator"></div>
                        <p class="text-center">@lang('request.no-privileges-desc')!</p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-12">
                <div class="well well-sm mt-20">
                    <p class="lead text-orange text-center"><strong>@lang('request.no-imdb-id')</strong></p>
                </div>
            </div>
            <h1 class="upload-title">@lang('request.add-request')</h1>
            <form role="form" method="POST" action="{{ route('add_request') }}">
                @csrf
                <div class="block">
                    <div class="upload col-md-12">
                        <div class="form-group">
                            <label for="name">@lang('request.title')</label>
                            <label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') ?? $title }}"
                                    required>
                            </label>
                        </div>
        
                        <div class="form-group">
                            <label for="category_id">@lang('request.category')</label>
                            <label>
                                <select name="category_id" class="form-control" required>
                                    <option hidden="" disabled="disabled" selected="selected" value="">--Select Category--</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
        
                        <div class="form-group">
                            <label for="type_id">@lang('request.type')</label>
                            <label>
                                <select name="type_id" class="form-control" required>
                                    <option hidden="" disabled="disabled" selected="selected" value="">--Select Type--</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="resolution_id">@lang('request.resolution')</label>
                            <label>
                                <select name="resolution_id" class="form-control">
                                    <option hidden="" disabled="disabled" selected="selected" value="">--Select Resolution--</option>
                                    @foreach ($resolutions as $resolution)
                                        <option value="{{ $resolution->id }}">{{ $resolution->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="name">TMDB ID <b>(@lang('request.required'))</b></label>
                            <label>
                                <input type="number" name="tmdb" id="autotmdb" class="form-control" value="{{ $tmdb ?? old('tmdb') }}" required>
                            </label>
                        </div>


                        <div class="form-group">
                            <label for="name">IMDB ID <b>(@lang('torrent.optional'))</b></label>
                            <label>
                                <input type="number" name="imdb" id="autoimdb" class="form-control" value="{{ $imdb ?? old('imdb') }}" required>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="name">TVDB ID (@lang('torrent.optional'))</label>
                            <label>
                                <input type="number" name="tvdb" id="autotvdb" value="{{ old('tvdb') ?? '0' }}" class="form-control" required>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="name">MAL ID (@lang('request.required') For Anime)</label>
                            <label>
                                <input type="number" name="mal" value="{{ old('mal') ?? '0' }}" class="form-control" required>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="name">IGDB ID <b>(@lang('request.required') For Games)</b></label>
                            <label>
                                <input type="number" name="igdb" value="{{ old('igdb') ?? '0' }}" class="form-control" required>
                            </label>
                        </div>
        
                        <div class="form-group">
                            <label for="description">@lang('request.description')</label>
                            <label for="request-form-description"></label>
                            <textarea id="request-form-description" name="description" cols="30" rows="10" class="form-control"></textarea>
                        </div>
        
                        <div class="form-group">
                            <label for="bonus_point">@lang('request.reward')
                                <small><em>(@lang('request.reward-desc'))</em></small></label>
                            <label>
                                <input class="form-control" name="bounty" type="number" min='100' value="100" required>
                            </label>
                        </div>
        
                        <label for="anon" class="control-label">@lang('common.anonymous')?</label>
                        <div class="radio-inline">
                            <label><input type="radio" name="anon" value="1">@lang('common.yes')</label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="anon" checked="checked" value="0">@lang('common.no')</label>
                        </div>
                    </div>
        
                    <br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">@lang('common.submit')</button>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $(document).ready(function() {
            $('#request-form-description').wysibb({});
        })
    
    </script>
@endsection
