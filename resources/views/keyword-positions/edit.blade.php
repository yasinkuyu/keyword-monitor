<!-- resources/views/keyword-positions/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Keyword Position</div>

                    <div class="card-body">
                        <form action="{{ route('keyword-positions.update', $keywordPosition->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="keyword_id">Keyword ID:</label>
                                <input type="text" class="form-control" id="keyword_id" name="keyword_id" value="{{ $keywordPosition->keyword_id }}" required>
                            </div>
                            <div class="form-group">
                                <label for="query_date">Query Date:</label>
                                <input type="date" class="form-control" id="query_date" name="query_date" value="{{ $keywordPosition->query_date }}" required>
                            </div>
                            <div class="form-group">
                                <label for="position">Position:</label>
                                <input type="text" class="form-control" id="position" name="position" value="{{ $keywordPosition->position }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
