@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Domains</div>

                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    @php
                                        $sort = request()->get('sort');
                                        $direction = request()->get('direction') === 'asc' ? 'desc' : 'asc';
                                    @endphp
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => $sort === 'id' ? $direction : 'asc']) }}">ID</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $sort === 'name' ? $direction : 'asc']) }}">Domain</a></th>
                                    <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $sort === 'created_at' ? $direction : 'asc']) }}">Created At</a></th>
                                    <th>Keywords</th>
                                    <th>Report</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($domains as $domain)
                                    <tr>
                                        <td>{{ $domain->id }}</td>
                                        <td>{{ $domain->name }}</td>
                                        <td>{{ $domain->created_at }}</td>
                                        <td>
                                            <a href="{{ route('keywords.domain', $domain->id)}}" class="btn btn-info btn-sm">Keywords</button>
                                        </td>
                                        <td>
                                            <a href="{{ route('domains.report', $domain->id)}}" class="btn btn-info btn-sm">Report</button>
                                        </td>
                                        <td>
                                            <a href="{{ route('domains.edit', $domain->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('domains.destroy', $domain->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $domains->links("pagination::bootstrap-4") }}
                        <a href="{{ route('domains.create') }}" class="btn btn-success btn-sm">Create New Domain</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
