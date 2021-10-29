@extends('layouts.app')
@push('after-styles')
@endpush
@section('content')
    <div class="container-fluid">
        <h4 class="page-title">All Test
        </h4>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <a href="{{ route('admin.test.create') }}" class="btn btn-flat btn-success">{{ __('Create') }}</a>
                <form action="" style="display: inline-block;" id="index-search-form">
                    <input type="text" name="search" class="form-control index-search"><i class="fas fa-search"></i>
                </form>
            </div>
            <div class="card-body pad table-responsive">
                <table class="table table-striped">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th></th>
                    </thead>
                    @foreach ($allTest as $index => $test)
                        <tr>
                            <td>{{ ($allTest->currentPage() - 1) * $allTest->perPage() + $index + 1 }}</td>
                            <td>{{ $test->name }}</td>
                            <td>{{ $test->phone }}</td>
                            <td>
                                <a class="btn btn-primary btn-ms" href="{{ route('admin.test.edit', $test->id) }}">
                                    Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {!! $allTest->links() !!}
            </div>
        </div>
    </div>
@stop
@push('after-scripts')
@endpush
