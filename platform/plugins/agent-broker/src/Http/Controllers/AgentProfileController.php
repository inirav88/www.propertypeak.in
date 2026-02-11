<?php

namespace Botble\AgentBroker\Http\Controllers;

use Botble\AgentBroker\Http\Requests\AgentProfileRequest;
use Botble\AgentBroker\Models\AgentProfile;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;

class AgentProfileController extends BaseController
{
    public function index()
    {
        $this->authorize('viewAny', AgentProfile::class);

        $agents = AgentProfile::query()->latest()->paginate(20);

        $this->pageTitle('Agents / Brokers');

        return view('plugins/agent-broker::agents.index', compact('agents'));
    }

    public function create()
    {
        $this->authorize('create', AgentProfile::class);

        $agent = new AgentProfile();

        $this->pageTitle('Create Agent / Broker');

        return view('plugins/agent-broker::agents.form', compact('agent'));
    }

    public function store(AgentProfileRequest $request)
    {
        $this->authorize('create', AgentProfile::class);

        $agent = AgentProfile::query()->create($request->validated());

        return $this->httpResponse()
            ->setNextUrl(route('agent-broker.profiles.edit', $agent->id))
            ->setPreviousUrl(route('agent-broker.profiles.index'))
            ->setMessage('Agent profile created successfully.');
    }

    public function edit(AgentProfile $agent)
    {
        $this->authorize('update', $agent);

        $this->pageTitle('Edit Agent / Broker');

        return view('plugins/agent-broker::agents.form', compact('agent'));
    }

    public function update(AgentProfileRequest $request, AgentProfile $agent)
    {
        $this->authorize('update', $agent);

        $agent->fill($request->validated());
        $agent->save();

        return $this->httpResponse()
            ->setPreviousUrl(route('agent-broker.profiles.index'))
            ->setMessage('Agent profile updated successfully.');
    }

    public function destroy(AgentProfile $agent)
    {
        $this->authorize('delete', $agent);

        return DeleteResourceAction::make($agent);
    }
}
