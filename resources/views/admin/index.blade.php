@extends('layouts.app')

@section('content')
    <div class="container">
        @include('common.success')
        <div class="panel-body">
            @if (count($files) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ trans('file.list_of_files') }}
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($files as $file)
                                    <tr>
                                        <td class="table-text">{{ $file->name }}</td>

                                        <td class="table-text">{{ $file->description }}</td>

                                        <td>
                                            <a href="{{ route('file.download', $file) }}" class="btn btn-default">Download</a>
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.file.edit.form', $file) }}" class="btn btn-primary">Edit</a>
                                        </td>

                                        <td>
                                            <form action="{{ route('admin.file.remove', $file) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}

                                                <button type="submit" class="btn btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $files->links() }}
            @endif
        </div>
    </div>
@endsection