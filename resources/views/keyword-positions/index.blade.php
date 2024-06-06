@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex flex-col">
            <div class="w-full">
                <div class="bg-white shadow-md rounded-lg">
                    <div class="bg-gray-200 px-4 py-2 rounded-t-lg font-semibold">Keyword Positions</div>

                    <div class="p-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @php
                                        $sort = request()->get('sort');
                                        $direction = request()->get('direction') === 'asc' ? 'desc' : 'asc';
                                    @endphp
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => $sort === 'id' ? $direction : 'asc']) }}">ID</a>
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'domain', 'direction' => $sort === 'domain' ? $direction : 'asc']) }}">Domain</a>
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'keyword', 'direction' => $sort === 'keyword' ? $direction : 'asc']) }}">Keyword</a>
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'lang', 'direction' => $sort === 'lang' ? $direction : 'asc']) }}">Lang</a>
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'country', 'direction' => $sort === 'country' ? $direction : 'asc']) }}">Country</a>
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $sort === 'created_at' ? $direction : 'asc']) }}">Query Date</a>
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'position', 'direction' => $sort === 'position' ? $direction : 'asc']) }}">Position</a>
                                    </th>
                                    <th class="px-4 py-2"></th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($keywordPositions as $keywordPosition)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $keywordPosition->id }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $keywordPosition->domain->name }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $keywordPosition->keyword->keyword }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $keywordPosition->lang }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $keywordPosition->country }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $keywordPosition->created_at }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $keywordPosition->position }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap"></td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <a href="{{ route('keyword-positions.edit', $keywordPosition->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('keyword-positions.destroy', $keywordPosition->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $keywordPositions->links("pagination::tailwind") }} <!-- Sayfalama linkleri -->
                        </div>
                        <a href="{{ route('keyword-positions.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-600 disabled:opacity-25 transition">Add Keyword Position</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
