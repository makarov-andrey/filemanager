@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form action="{{ route('admin.file.edit', $file) }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" required name="name" id="name" class="form-control" value="{{ $file->name }}">
                    </div>

                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <textarea name="description" id="description" class="form-control" maxlength="1000">{{ $file->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <a href="{{ $file->downloadLink() }}" class="btn btn-default">Download</a>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-plus"></i> Редактировать файл
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection