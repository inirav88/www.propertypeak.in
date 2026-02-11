@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <form method="POST" action="{{ route('agent-broker.settings.update') }}">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header"><h4>Agent/Broker Settings</h4></div>
            <div class="card-body row">
                <div class="col-md-4 mb-3"><label>Global auto approve</label><input type="checkbox" name="agent_broker_global_auto_approve" value="1" {{ setting('agent_broker_global_auto_approve') ? 'checked' : '' }}></div>
                <div class="col-md-4 mb-3"><label>Require approval default</label><input type="checkbox" name="agent_broker_require_approval_default" value="1" {{ setting('agent_broker_require_approval_default', 1) ? 'checked' : '' }}></div>
                <div class="col-md-4 mb-3"><label>Notify admin on submission</label><input type="checkbox" name="agent_broker_notify_admin_on_submission" value="1" {{ setting('agent_broker_notify_admin_on_submission', 1) ? 'checked' : '' }}></div>
            </div>
            <div class="card-footer"><button class="btn btn-primary">Save settings</button></div>
        </div>
    </form>
@endsection
