@extends('layouts.app')

@section('content')
    <div class="container">
        @if (count($files) > 0)
            <h1>List of files</h1>
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-striped task-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($files as $file)
                                <tr>
                                    <td class="table-text">{{ $file->name }}</td>
                                    <td class="table-text">{{ $file->description }}</td>
                                    <td class="table-text">{{ $file->created_at }}</td>
                                    <td class="table-text">{{ $file->updated_at }}</td>

                                    <td>
                                        <a href="{{ route('file.download', $file) }}" class="btn btn-default">
                                            Download
                                        </a>
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.file.edit.form', $file) }}" class="btn btn-primary">
                                            Edit
                                        </a>
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
        @else
            <h1>There are no files yet.</h1>
        @endif
    </div>
@endsection