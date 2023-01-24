@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <button class="btn btn-primary btn-sm" id="bulkIgnore" title="Ignore">{{ __('Ignore') }}</button>
                <button class="btn btn-danger btn-sm ml-1" id="bulkBan" title="Ban">{{ __('Ban') }}</button>
            </div>
            <br>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll" /></th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('IP') }}</th>
                        <th>{{ __('Images') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                        <tr>
                            <td><input type="checkbox" name="reported_users[]" value="{{ $value->id }}" /></td>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->ip }}</td>
                            <td>
                                @foreach (json_decode($value->images) as $image)
                                    <img src="{{ asset('storage/images/reported-users/' . $image) }}"
                                        class="reported-image" width="50px" title="{{ __('View') }}" alt="user" />
                                @endforeach
                            </td>
                            <td>
                                <button data-id="{{ $value->id }}" class="btn btn-primary btn-sm ignore"
                                    title="{{ __('Ignore') }}"><i class="fa fa-minus"></i></button>
                                <button data-id="{{ $value->id }}" class="btn btn-danger btn-sm ban"
                                    title="{{ __('Ban') }}"><i class="fa fa-ban"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('IP') }}</th>
                        <th>{{ __('Images') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="modal fade" id="reportedImageModal" tabindex="-1" role="dialog"
        aria-labelledby="reportedImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <img id="reportedImage">
            </div>
        </div>
    </div>
@endsection
