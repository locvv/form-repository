@extends('layouts.app')
@push('after-styles')
@endpush
@section('content')
    <div class="container-fluid">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h4 class="page-title">Update Test</h4>
            </div>
            <div class="card-body pad table-responsive">
                <form action="{{route('admin.test.update',$test->id)}}" method="POST">
                    @method('PATCH')
                    {!! csrf_field() !!}
                    @include('admin::test.form')
                    <div class="form-group">
                        <button type="submit" class="btn btn-flat btn-success">Edit</button>
                        <a href="{{route('admin.test.index')}}" class="btn btn-flat btn-default">Back</a>
                        <a href="#" style="float:right" class="btn btn-flat btn-danger" id="delete-btn">Delete</a>
                    </div>
                </form>

                <div class="form-group">
                    <form action="{{route('admin.test.destroy',$test->id)}}" id="delete-form" method="post">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-scripts')
    <script>
        $('#delete-btn').click(function(event){
            event.preventDefault();
            const result = confirm('Are you sure you want to delete');
            if(result){
                $('#delete-form').submit();
            }
        });
    </script>
@endpush
@push('after-scripts')
    {!! JsValidator::formRequest(\Modules\Admin\Http\Requests\Test\CreateRequest::class) !!}
@endpush

