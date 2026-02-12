<?php

namespace Botble\Crm\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Crm\Services\CrmPipelineService;
use Botble\RealEstate\Models\Consult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CrmLeadController extends BaseController
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('crm.view'), 403);

        $leads = $this->visibleLeadsQuery($request)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $this->pageTitle('CRM Leads');

        return view('plugins/crm::leads.index', [
            'leads' => $leads,
            'stages' => app(CrmPipelineService::class)->getAvailableStages(),
        ]);
    }

    public function board(Request $request, CrmPipelineService $pipelineService)
    {
        abort_unless(auth()->user()->hasPermission('crm.view'), 403);

        $stages = $pipelineService->getAvailableStages();

        $leads = $this->visibleLeadsQuery($request)
            ->latest()
            ->get();

        $leadsByStage = collect($stages)
            ->mapWithKeys(fn (string $stage): array => [$stage => collect()]);

        foreach ($leads as $lead) {
            $stage = $lead->pipeline_stage ?: 'new';
            $targetStage = in_array($stage, $stages, true) ? $stage : 'new';
            $leadsByStage[$targetStage]->push($lead);
        }

        $this->pageTitle('CRM Pipeline Board');

        return view('plugins/crm::leads.board', [
            'stages' => $stages,
            'leadsByStage' => $leadsByStage,
        ]);
    }

    public function updateStage(Consult $lead, Request $request, CrmPipelineService $pipelineService)
    {
        abort_unless(auth()->user()->hasPermission('crm.update'), 403);

        $this->abortIfLeadNotVisible($lead);

        $validated = $request->validate([
            'stage' => ['required', 'string', 'max:50'],
        ]);

        $lead = $pipelineService->moveToStage($lead, $validated['stage']);

        return $this->httpResponse()
            ->setMessage('Lead stage updated successfully.')
            ->setData([
                'id' => $lead->getKey(),
                'pipeline_stage' => $lead->pipeline_stage,
                'contacted_at' => $lead->contacted_at,
            ]);
    }

    public function assign(Consult $lead, Request $request, CrmPipelineService $pipelineService)
    {
        abort_unless(auth()->user()->hasPermission('crm.assign'), 403);

        $this->abortIfLeadNotVisible($lead);

        $validated = $request->validate([
            'assigned_to' => ['nullable', 'integer'],
        ]);

        $lead = $pipelineService->assignTo($lead, $validated['assigned_to'] ?? null);

        return $this->httpResponse()
            ->setMessage('Lead assignment updated successfully.')
            ->setData([
                'id' => $lead->getKey(),
                'assigned_to' => $lead->assigned_to,
            ]);
    }

    public function scheduleFollowUp(Consult $lead, Request $request, CrmPipelineService $pipelineService)
    {
        abort_unless(auth()->user()->hasPermission('crm.update'), 403);

        $this->abortIfLeadNotVisible($lead);

        $validated = $request->validate([
            'follow_up_at' => ['required', 'date'],
        ]);

        $lead = $pipelineService->scheduleFollowUp($lead, Carbon::parse($validated['follow_up_at']));

        return $this->httpResponse()
            ->setMessage('Lead follow-up scheduled successfully.')
            ->setData([
                'id' => $lead->getKey(),
                'follow_up_at' => $lead->follow_up_at,
            ]);
    }

    protected function visibleLeadsQuery(Request $request): Builder
    {
        $user = auth()->user();

        return Consult::query()
            ->when($request->filled('stage'), fn (Builder $query) => $query->where('pipeline_stage', $request->string('stage')))
            ->when(! $user->isSuperUser(), fn (Builder $query) => $query->where('assigned_to', $user->getKey()));
    }

    protected function abortIfLeadNotVisible(Consult $lead): void
    {
        $user = auth()->user();

        if (! $user->isSuperUser() && $lead->assigned_to !== $user->getKey()) {
            abort(403);
        }
    }
}
