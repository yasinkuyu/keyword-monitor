@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Keywords</div>

                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    @php
                                        $sort = request()->get('sort');
                                        $direction = request()->get('direction') === 'asc' ? 'desc' : 'asc';
                                    @endphp
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => $sort === 'id' ? $direction : 'asc']) }}">ID</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'keyword', 'direction' => $sort === 'keyword' ? $direction : 'asc']) }}">Keyword</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $sort === 'created_at' ? $direction : 'asc']) }}">Created At</a></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keywords as $keyword)
                                    <tr>
                                        <td>{{ $keyword->id }}</td>
                                        <td>{{ $keyword->keyword }}</td>
                                        <td>{{ $keyword->created_at }}</td>
                                        <td>
                                            <form action="{{ route('keywords.destroy', $keyword->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $keywords->links("pagination::bootstrap-4") }}
                        <a href="{{ route('keywords.create') }}" class="btn btn-success">Add Keyword</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
