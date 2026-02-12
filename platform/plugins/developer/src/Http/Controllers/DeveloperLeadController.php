<?php

namespace Botble\Developer\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Developer\Models\DeveloperLead;

class DeveloperLeadController extends BaseController
{
    public function index()
    {
        abort_unless(auth()->user()->hasPermission('developer.leads.index'), 403);

        $leads = DeveloperLead::query()->with(['developerProfile', 'developerProject'])->latest()->paginate(20);

        $this->pageTitle('Developer Leads');

        return view('plugins/developer::leads.index', compact('leads'));
    }
}
