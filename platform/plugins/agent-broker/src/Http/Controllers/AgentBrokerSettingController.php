<?php

namespace Botble\AgentBroker\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class AgentBrokerSettingController extends BaseController
{
    public function edit()
    {
        abort_unless(auth()->user()->hasPermission('agent-broker.settings'), 403);

        $this->pageTitle('Agent/Broker Settings');

        return view('plugins/agent-broker::settings.index');
    }

    public function update(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('agent-broker.settings'), 403);

        $data = $request->validate([
            'agent_broker_global_auto_approve' => ['nullable', 'boolean'],
            'agent_broker_require_approval_default' => ['nullable', 'boolean'],
            'agent_broker_notify_admin_on_submission' => ['nullable', 'boolean'],
        ]);

        foreach ($data as $key => $value) {
            setting()->set($key, $value);
        }

        setting()->save();

        return $this->httpResponse()->setMessage('Agent/Broker settings updated.');
    }
}
