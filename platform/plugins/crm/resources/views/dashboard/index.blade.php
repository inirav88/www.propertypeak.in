@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="page-content">
        <h2>CRM Dashboard</h2>

        <pre>{{ json_encode($summary, JSON_PRETTY_PRINT) }}</pre>
        <pre>{{ json_encode($stageDistribution, JSON_PRETTY_PRINT) }}</pre>
        <pre>{{ json_encode($userPerformance, JSON_PRETTY_PRINT) }}</pre>
        <pre>{{ json_encode($referenceSplit, JSON_PRETTY_PRINT) }}</pre>
    </div>
@endsection
