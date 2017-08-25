@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('common.errors')
            @include('common.success')
            <div class="col-md-8 col-md-offset-2">
                <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" required name="email" id="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="file" class="control-label">Файл</label>
                        <input type="file" required name="file" id="file" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="description" class="control-label">Описание</label>
                        <textarea name="description" id="description" class="form-control" maxlength="1000"></textarea>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-default">
                                <i class="fa fa-plus"></i> Добавить файл
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection