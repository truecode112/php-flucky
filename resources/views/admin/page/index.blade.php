@extends('layouts.admin')

@section('page', $page)
@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('content')
<div class="card">
<div class="card-body">
    <table id="pages" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>{{__('ID')}}</th>
                <th>{{__('Key')}}</th>
                <th>{{__('Value')}}</th>
                <th>{{__('Action')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->key }}</td>
                    <td>{{ strlen($value->value) > 250 ? substr($value->value, 0, 250) . '...' : $value->value }}
                    </td>
                    <td>
                        <a href="/pages/edit/{{ $value->id }}">
                            <button class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i>
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>{{__('ID')}}</th>
                <th>{{__('Key')}}</th>
                <th>{{__('Value')}}</th>
                <th>{{__('Action')}}</th>
            </tr>
        </tfoot>
    </table>
</div>
</div>
@endsection
