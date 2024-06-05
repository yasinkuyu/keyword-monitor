@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Keyword Position</div>

                    <div class="card-body">
                        <form @submit.prevent="handleSubmit" id="searchForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="service">Service:</label>
                                        <select v-model="service" class="form-control" required>
                                            <option v-for="option in serviceOptions" :value="option.value">
                                                @{{ option.text }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="domain">Domain:</label>
                                        <input type="text" v-model="domain" class="form-control" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="keyword">Keyword:</label>
                                        <input type="text" v-model="keyword" @input="fetchKeywords" class="form-control" required autocomplete="off">
                                        <div v-if="suggestedKeywords.length" class="autocomplete-list">
                                            <ul class="list-group">
                                                <li class="list-group-item" v-for="suggestedKeyword in suggestedKeywords" @click="selectKeyword(suggestedKeyword)">
                                                   <a href="#"> @{{ suggestedKeyword.keyword }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="language">Language:</label>
                                        <select v-model="language" class="form-control" required>
                                            @foreach($languages as $code => $language)
                                                <option value="{{ $code }}" {{ $code == 'tr' ? 'selected' : '' }}>{{ $language }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="country">Country:</label>
                                        <select v-model="country" class="form-control" required>
                                            @foreach($countries as $code => $country)
                                                <option value="{{ $code }}" {{ $code == 'tr' ? 'selected' : '' }}>{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="position">Position:</label>
                                        <input v-model="position" id="position" name="position" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>
                            <div v-if="!isRecaptchaHidden && isRecaptchaRequired" :id="recaptchaId"></div>
                            <button class="btn btn-success" type="submit" :disabled="isLoading">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('keyword-positions.list')
    </div>
@endsection

@section('scripts')
    @include('home-scripts')
@endsection
 