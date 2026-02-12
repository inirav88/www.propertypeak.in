<?php

namespace Botble\Crm\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Crm\Services\CrmAnalyticsService;
use Illuminate\Http\Request;

class CrmDashboardController extends BaseController
{
    protected CrmAnalyticsService $analytics;

    public function __construct(CrmAnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('crm.view'), 403);

        $filters = [
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'stage' => $request->input('stage'),
            'assigned_to' => $request->input('assigned_to'),
            'reference_type' => $request->input('reference_type'),
        ];

        $summary = $this->analytics->getSummaryStats($filters);
        $stageDistribution = $this->analytics->getStageDistribution($filters);
        $userPerformance = $this->analytics->getUserPerformance($filters);
        $referenceSplit = $this->analytics->getReferenceSplit($filters);

        $this->pageTitle('CRM Dashboard');

        return view('plugins/crm::dashboard.index', compact(
            'summary',
            'stageDistribution',
            'userPerformance',
            'referenceSplit',
            'filters'
        ));
    }
}
