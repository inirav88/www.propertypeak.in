<?php

namespace Botble\Developer\Http\Controllers\Fronts;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Developer\Models\DeveloperLead;
use Botble\Developer\Models\DeveloperProfile;
use Botble\Developer\Models\DeveloperProfileRevision;
use Botble\Developer\Models\DeveloperProject;
use Botble\Developer\Models\DeveloperProjectRevision;
use Botble\Developer\Services\SlugConflictService;
use Botble\RealEstate\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeveloperDashboardController extends BaseController
{
    public function editProfile()
    {
        $account = auth('account')->user();

        abort_unless($account->type === 'builder', 403);

        $profile = DeveloperProfile::query()->firstOrCreate([
            'account_id' => $account->id,
        ], [
            'slug' => $account->username,
            'company_name' => $account->company ?: $account->name,
            'status' => 'draft',
        ]);

        return view('plugins/developer::fronts.profile', compact('profile', 'account'));
    }

    public function updateProfile(Request $request)
    {
        /** @var Account $account */
        $account = auth('account')->user();

        abort_unless($account->type === 'builder', 403);

        $profile = DeveloperProfile::query()->firstOrCreate([
            'account_id' => $account->id,
        ], [
            'slug' => $account->username,
            'company_name' => $account->company ?: $account->name,
            'status' => 'draft',
        ]);

        $payload = $request->validate([
            'slug' => ['required', 'string', 'max:255', function (string $attribute, mixed $value, \Closure $fail) use ($profile): void {
                $service = app(SlugConflictService::class);

                if (! $service->isAvailable((string) $value, DeveloperProfile::class, $profile->id)) {
                    $fail(__('The :attribute has already been taken or reserved.', ['attribute' => $attribute]));
                }
            }],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'website' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive,draft'],
        ]);

        if ($this->shouldAutoApprove($profile)) {
            $profile->fill($payload);
            $profile->status = $payload['status'] ?? 'active';
            $profile->approved_at = now();
            $profile->approved_by = null;
            $profile->save();

            return $this->httpResponse()->setMessage(__('Profile updated successfully.'));
        }

        $revision = DeveloperProfileRevision::query()->create([
            'entity_id' => $profile->id,
            'entity_type' => DeveloperProfile::class,
            'payload' => $payload,
            'status' => 'pending',
            'submitted_by' => $account->id,
            'submitted_at' => now(),
        ]);

        $this->notifyAdminProfileSubmission($account, $revision);

        return $this->httpResponse()->setMessage(__('Profile changes submitted for approval.'));
    }

    public function projects()
    {
        $account = auth('account')->user();

        abort_unless($account->type === 'builder', 403);

        $profile = DeveloperProfile::query()->where('account_id', $account->id)->firstOrFail();

        $projects = DeveloperProject::query()
            ->where('developer_profile_id', $profile->id)
            ->latest()
            ->paginate(15);

        return view('plugins/developer::fronts.projects', compact('projects', 'profile'));
    }

    public function createProject()
    {
        $account = auth('account')->user();

        abort_unless($account->type === 'builder', 403);

        $profile = DeveloperProfile::query()->where('account_id', $account->id)->firstOrFail();

        return view('plugins/developer::fronts.project-form', [
            'project' => new DeveloperProject(),
            'profile' => $profile,
        ]);
    }

    public function storeProject(Request $request)
    {
        $account = auth('account')->user();

        abort_unless($account->type === 'builder', 403);

        $profile = DeveloperProfile::query()->where('account_id', $account->id)->firstOrFail();

        $payload = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', function (string $attribute, mixed $value, \Closure $fail): void {
                $service = app(SlugConflictService::class);

                if (! $service->isAvailable((string) $value, DeveloperProject::class)) {
                    $fail(__('The :attribute has already been taken or reserved.', ['attribute' => $attribute]));
                }
            }],
            'project_status' => ['required', 'in:ongoing,completed,upcoming'],
            'status' => ['nullable', 'in:draft,published'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        if ($this->shouldAutoApprove($profile)) {
            $project = DeveloperProject::query()->create([
                ...$payload,
                'developer_profile_id' => $profile->id,
                'approval_status' => 'approved',
                'approved_at' => now(),
                'published_at' => ($payload['status'] ?? 'draft') === 'published' ? now() : null,
            ]);

            return $this->httpResponse()->setNextUrl(route('public.account.developer.projects.edit', $project->id))->setMessage(__('Project created successfully.'));
        }

        $revision = null;

        DB::transaction(function () use ($payload, $profile, $account, &$revision): void {
            $project = DeveloperProject::query()->create([
                ...$payload,
                'developer_profile_id' => $profile->id,
                'approval_status' => 'pending',
            ]);

            $revision = DeveloperProjectRevision::query()->create([
                'entity_id' => $project->id,
                'entity_type' => DeveloperProject::class,
                'payload' => $payload,
                'status' => 'pending',
                'submitted_by' => $account->id,
                'submitted_at' => now(),
            ]);
        });

        $this->notifyAdminProfileSubmission($account, $revision);

        return $this->httpResponse()->setMessage(__('Project submitted for approval.'));
    }

    public function editProject(int|string $id)
    {
        $account = auth('account')->user();

        abort_unless($account->type === 'builder', 403);

        $profile = DeveloperProfile::query()->where('account_id', $account->id)->firstOrFail();

        $project = DeveloperProject::query()->where('developer_profile_id', $profile->id)->findOrFail($id);

        return view('plugins/developer::fronts.project-form', compact('project', 'profile'));
    }

    public function updateProject(int|string $id, Request $request)
    {
        $account = auth('account')->user();

        abort_unless($account->type === 'builder', 403);

        $profile = DeveloperProfile::query()->where('account_id', $account->id)->firstOrFail();

        $project = DeveloperProject::query()->where('developer_profile_id', $profile->id)->findOrFail($id);

        $payload = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', function (string $attribute, mixed $value, \Closure $fail) use ($project): void {
                $service = app(SlugConflictService::class);

                if (! $service->isAvailable((string) $value, DeveloperProject::class, $project->id)) {
                    $fail(__('The :attribute has already been taken or reserved.', ['attribute' => $attribute]));
                }
            }],
            'project_status' => ['required', 'in:ongoing,completed,upcoming'],
            'status' => ['nullable', 'in:draft,published'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        if ($this->shouldAutoApprove($profile)) {
            $project->fill($payload);
            $project->approval_status = 'approved';
            $project->approved_at = now();
            $project->published_at = ($payload['status'] ?? 'draft') === 'published' ? now() : null;
            $project->save();

            return $this->httpResponse()->setMessage(__('Project updated successfully.'));
        }

        DB::transaction(function () use ($project, $payload, $account): void {
            DeveloperProjectRevision::query()->create([
                'entity_id' => $project->id,
                'entity_type' => DeveloperProject::class,
                'payload' => $payload,
                'status' => 'pending',
                'submitted_by' => $account->id,
                'submitted_at' => now(),
            ]);

            $project->approval_status = 'pending';
            $project->save();
        });

        return $this->httpResponse()->setMessage(__('Project changes submitted for approval.'));
    }

    public function leads()
    {
        $account = auth('account')->user();

        abort_unless($account->type === 'builder', 403);

        $profile = DeveloperProfile::query()->where('account_id', $account->id)->firstOrFail();

        $leads = DeveloperLead::query()->where('developer_profile_id', $profile->id)->latest()->paginate(20);

        return view('plugins/developer::fronts.leads', compact('leads', 'profile'));
    }

    protected function shouldAutoApprove(DeveloperProfile $profile): bool
    {
        $requiresApproval = $profile->requires_approval;

        if (is_null($requiresApproval)) {
            $requiresApproval = (bool) setting('developer_require_approval_default', true);
        }

        $globalAutoApprove = (bool) setting('developer_global_auto_approve', false);

        return $profile->auto_approve_changes || $globalAutoApprove || ! $requiresApproval;
    }

    protected function notifyAdminProfileSubmission(Account $account, DeveloperProfileRevision|DeveloperProjectRevision $revision): void
    {
        $adminEmail = setting('admin_email') ?: config('mail.from.address');

        if (! $adminEmail) {
            return;
        }

        EmailHandler::setModule(DEVELOPER_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'developer_name' => $account->name,
                'developer_email' => $account->email,
                'revision_url' => route('developer.approvals.index') . '#revision-' . $revision->id,
            ])
            ->sendUsingTemplate('developer-profile-change-submitted', $adminEmail);
    }
}
