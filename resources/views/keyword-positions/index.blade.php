@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Keyword Positions</div>

                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    @php
                                        $sort = request()->get('sort');
                                        $direction = request()->get('direction') === 'asc' ? 'desc' : 'asc';
                                    @endphp
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => $sort === 'id' ? $direction : 'asc']) }}">ID</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'domain', 'direction' => $sort === 'domain' ? $direction : 'asc']) }}">Domain</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'keyword', 'direction' => $sort === 'keyword' ? $direction : 'asc']) }}">Keyword</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'lang', 'direction' => $sort === 'lang' ? $direction : 'asc']) }}">Lang</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'country', 'direction' => $sort === 'country' ? $direction : 'asc']) }}">Country</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $sort === 'created_at' ? $direction : 'asc']) }}">Query Date</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'position', 'direction' => $sort === 'position' ? $direction : 'asc']) }}">Position</a></th>
                                    <th></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keywordPositions as $keywordPosition)
                                    <tr>
                                        <td>{{ $keywordPosition->id }}</td>
                                        <td>{{ $keywordPosition->domain->name }}</td>
                                        <td>{{ $keywordPosition->keyword->keyword }}</td>
                                        <td>{{ $keywordPosition->lang }}</td>
                                        <td>{{ $keywordPosition->country }}</td>
                                        <td>{{ $keywordPosition->created_at }}</td>
                                        <td>{{ $keywordPosition->position }}</td>
                                        <td></td>
                                        <td>
                                            <a href="{{ route('keyword-positions.edit', $keywordPosition->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('keyword-positions.destroy', $keywordPosition->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $keywordPositions->links("pagination::bootstrap-4") }} <!-- Sayfalama linkleri -->
                        <a href="{{ route('keyword-positions.create') }}" class="btn btn-success btn-sm">Add Keyword Position</a>
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection
