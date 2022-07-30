@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.groups.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.user') Groups</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.groups.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Add User Group</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Add New Group</h2>
        <div class="table-responsive">
            <form role="form" method="POST" action="{{ route('staff.groups.store') }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>@lang('common.name')</th>
                                <th>@lang('common.position')</th>
                                <th>Level</th>
                                <th>Color</th>
                                <th>Icon</th>
                                <th>Effect</th>
                                <th>Internal</th>
                                <th>Modo</th>
                                <th>Admin</th>
                                <th>Owner</th>
                                <th>Trusted</th>
                                <th>Immune</th>
                                <th>Freeleech</th>
                                <th>Double Upload</th>
                                <th>Incognito</th>
                                <th>Upload</th>
                                <th>Autogroup</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            <tr>
                                <td>
                                    <label>
                                        <input type="text" name="name" value="" placeholder="@lang('common.name')"
                                            class="form-control" />
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="number" name="position" value="" placeholder="@lang('common.position')"
                                            class="form-control" />
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="number" name="level" value="" placeholder="Level"
                                            class="form-control" />
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="text" name="color" value="" placeholder="HEX Color ID"
                                            class="form-control" />
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="text" name="icon" value="" placeholder="FontAwesome Icon"
                                            class="form-control" />
                                    </label></td>
                                <td>
                                    <label>
                                        <input type="text" name="effect" value="none" placeholder="GIF Effect"
                                            class="form-control" />
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="is_internal" value="0">
                                    <label>
                                        <input type="checkbox" name="is_internal" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="is_modo" value="0">
                                    <label>
                                        <input type="checkbox" name="is_modo" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="is_admin" value="0">
                                    <label>
                                        <input type="checkbox" name="is_admin" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="is_owner" value="0">
                                    <label>
                                        <input type="checkbox" name="is_owner" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="is_trusted" value="0">
                                    <label>
                                        <input type="checkbox" name="is_trusted" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="is_immune" value="0">
                                    <label>
                                        <input type="checkbox" name="is_immune" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="is_freeleech" value="0">
                                    <label>
                                        <input type="checkbox" name="is_freeleech" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="is_double_upload" value="0">
                                    <label>
                                        <input type="checkbox" name="is_double_upload" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="is_incognito" value="0">
                                    <label>
                                        <input type="checkbox" name="is_incognito" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="can_upload" value="0">
                                    <label>
                                        <input type="checkbox" name="can_upload" value="1">
                                    </label>
                                </td>
                                <td>
                                    <input type="hidden" name="autogroup" value="0">
                                    <label>
                                        <input type="checkbox" name="autogroup" value="1">
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">@lang('common.add')</button>
            </form>
        </div>
    </div>
@endsection
