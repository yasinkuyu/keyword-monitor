
   <hr> <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Keyword Positions</div>

                <div class="card-body">
                    <table id="keywordPositionsTable" class="table">
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Keyword</th>
                                <th>Position</th>
                                <th>Country</th>
                                <th>Language</th>
                                <th>Created At</th>
                                <th>Last Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="keywordPosition in keywordPositions" :key="keywordPosition.id">
                                <td>@{{ keywordPosition.domain.name }}</td>
                                <td>@{{ keywordPosition.keyword.keyword }}</td>
                                <td>@{{ keywordPosition.position }}</td>
                                <td>@{{ keywordPosition.country }}</td>
                                <td>@{{ keywordPosition.language }}</td>
                                <td>@{{ keywordPosition.created_at_format }}</td>
                                <td>@{{ keywordPosition.updated_at_format }}</td>
                              </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
