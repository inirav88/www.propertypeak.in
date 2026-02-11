<?php

namespace Botble\Developer\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class DeveloperSettingController extends BaseController
{
    public function edit()
    {
        abort_unless(auth()->user()->hasPermission('developer.settings'), 403);

        $this->pageTitle('Developer Settings');

        return view('plugins/developer::settings.index');
    }

    public function update(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('developer.settings'), 403);

        $data = $request->validate([
            'developer_global_auto_approve' => ['nullable', 'boolean'],
            'developer_require_approval_default' => ['nullable', 'boolean'],
            'developer_notify_admin_on_submission' => ['nullable', 'boolean'],
        ]);

        foreach ($data as $key => $value) {
            setting()->set($key, $value);
        }

        setting()->save();

        return $this->httpResponse()->setMessage('Developer settings updated.');
    }
}
