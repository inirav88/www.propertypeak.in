<?php

namespace Botble\RealEstate\Http\Controllers\Fronts;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Optimize\Facades\OptimizerHelper;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\ProjectStatusEnum;
use Botble\RealEstate\Forms\AccountProjectForm;
use Botble\RealEstate\Http\Requests\AccountProjectRequest;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\AccountActivityLog;
use Botble\RealEstate\Models\Project;
use Botble\RealEstate\Tables\AccountProjectTable;
use Illuminate\Support\Facades\DB;
use Throwable;

class AccountProjectController extends BaseController
{
    public function __construct()
    {
        OptimizerHelper::disable();
    }

    public function index(AccountProjectTable $projectTable)
    {
        $this->pageTitle(trans('plugins/real-estate::project.projects'));

        return $projectTable->render('plugins/real-estate::account.table.base');
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/real-estate::project.create'));

        return AccountProjectForm::create()
            ->renderForm();
    }

    public function store(AccountProjectRequest $request)
    {
        $projectForm = AccountProjectForm::create()->setRequest($request);

        $projectForm->saving(function (AccountProjectForm $form): void {
            /** @var Project $project */
            $project = $form->getModel();
            $project->fill([
                ...$form->getRequestData(),
                'author_id' => auth('account')->id(),
                'author_type' => Account::class,
            ]);

            $project->save();

            $form->fireModelEvents($project);

            AccountActivityLog::query()->create([
                'action' => 'create_project',
                'reference_name' => $project->name,
                'reference_url' => route('public.account.projects.edit', $project->id),
            ]);
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('public.account.projects.index'))
            ->setNextUrl(route('public.account.projects.edit', $projectForm->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(int|string $id)
    {
        $project = Project::query()
            ->where([
                'id' => $id,
                'author_id' => auth('account')->id(),
                'author_type' => Account::class,
            ])
            ->firstOrFail();

        $this->pageTitle(trans('plugins/real-estate::project.edit') . ' "' . $project->name . '"');

        return AccountProjectForm::createFromModel($project)
            ->renderForm();
    }

    public function update(int|string $id, AccountProjectRequest $request)
    {
        $project = Project::query()
            ->where([
                'id' => $id,
                'author_id' => auth('account')->id(),
                'author_type' => Account::class,
            ])
            ->firstOrFail();

        $projectForm = AccountProjectForm::createFromModel($project)->setRequest($request);

        $projectForm->saving(function (AccountProjectForm $form): void {
            /** @var Project $project */
            $project = $form->getModel();
            $project->fill($form->getRequestData());
            $project->save();

            $form->fireModelEvents($project);

            AccountActivityLog::query()->create([
                'action' => 'update_project',
                'reference_name' => $project->name,
                'reference_url' => route('public.account.projects.edit', $project->id),
            ]);
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('public.account.projects.index'))
            ->setNextUrl(route('public.account.projects.edit', $project->id))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id)
    {
        $project = Project::query()
            ->where([
                'id' => $id,
                'author_id' => auth('account')->id(),
                'author_type' => Account::class,
            ])
            ->firstOrFail();

        AccountActivityLog::query()->create([
            'action' => 'delete_project',
            'reference_name' => $project->name,
        ]);

        return DeleteResourceAction::make($project);
    }
}
