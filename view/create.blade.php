@extends('layouts.app')
@push('after-styles')
@endpush
@section('content')
    <div class="container-fluid">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h4 class="page-title">Create new Test</h4>
            </div>
            <div class="card-body pad table-responsive">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('admin.test.store')}}" method="post">
                    {!! csrf_field() !!}
                    @include('admin::test.form')
                    <div class="form-group">
                        <button type="submit" class="btn bg-olive btn-flat margin">Create Test</button>
                        <a href="{{route('admin.test.index')}}" class="btn btn-flat btn-default">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@push('after-scripts')
    {!! JsValidator::formRequest(\Modules\Admin\Http\Requests\Test\CreateRequest::class) !!}
@endpush

