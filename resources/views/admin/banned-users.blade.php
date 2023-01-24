@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <button class="btn btn-primary btn-sm" id="bulkUnban" title="Unban">{{ __('Unban') }}</button>
            </div>
            <br>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll" /></th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('IP') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                        <tr>
                            <td><input type="checkbox" name="banned_users[]" value="{{ $value->id }}" /></td>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->ip }}</td>
                            <td>
                                <button data-ip="{{ $value->ip }}" class="btn btn-primary btn-sm unban"
                                    title="{{ __('Unban') }}"><i class="fa fa-minus"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('IP') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="modal fade" id="reportedImageModal" tabindex="-1" role="dialog" aria-labelledby="reportedImageModalLabel"
        aria-hidden="true">
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
