<!-- resources/views/domains/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create domain</div>

                    <div class="card-body">
                        <form action="{{ route('domains.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="domain">Domain:</label>
                                <input type="text" class="form-control" id="name" name="name" required pattern="^([a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a class="btn btn-primary" href="{{ route('domains.index') }}"> Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
