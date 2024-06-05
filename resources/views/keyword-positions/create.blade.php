@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Keyword Position</div>

                    <div class="card-body">
                        <form action="{{ route('keyword-positions.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="domain_id">Domain:</label>
                                        <select name="domain_id" v-model="domain_id" class="form-control" required>
                                            @foreach($domains as $domain)
                                                <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="keyword">Keyword:</label>
                                        <select name="keyword" v-model="keyword" class="form-control" required>
                                            @foreach($keywords as $keyword)
                                                <option value="{{ $keyword->keyword }}">{{ $keyword->keyword }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="language">Language:</label>
                                        <select name="language" v-model="language" class="form-control" required>
                                            @foreach($languages as $code => $language)
                                                <option value="{{ $code }}" {{ $code == 'tr' ? 'selected' : '' }}>{{ $language }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="country">Country:</label>
                                        <select name="country" v-model="country" class="form-control" required>
                                            @foreach($countries as $code => $country)
                                                <option value="{{ $code }}" {{ $code == 'tr' ? 'selected' : '' }}>{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="position">Position:</label>
                                        <input v-model="position" id="position" name="position" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
 
