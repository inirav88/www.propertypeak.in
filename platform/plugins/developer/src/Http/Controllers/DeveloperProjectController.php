<?php

namespace Botble\Developer\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Developer\Http\Requests\DeveloperProjectRequest;
use Botble\Developer\Models\DeveloperProject;

class DeveloperProjectController extends BaseController
{
    public function index()
    {
        $this->authorize('viewAny', DeveloperProject::class);

        $projects = DeveloperProject::query()->with('developerProfile')->latest()->paginate(20);

        $this->pageTitle('Developer Projects');

        return view('plugins/developer::projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('create', DeveloperProject::class);

        $project = new DeveloperProject();

        $this->pageTitle('Create Developer Project');

        return view('plugins/developer::projects.form', compact('project'));
    }

    public function store(DeveloperProjectRequest $request)
    {
        $this->authorize('create', DeveloperProject::class);

        $project = DeveloperProject::query()->create($request->validated());

        return $this->httpResponse()
            ->setNextUrl(route('developer.projects.edit', $project->id))
            ->setPreviousUrl(route('developer.projects.index'))
            ->setMessage('Developer project created successfully.');
    }

    public function edit(DeveloperProject $project)
    {
        $this->authorize('update', $project);

        $this->pageTitle('Edit Developer Project');

        return view('plugins/developer::projects.form', compact('project'));
    }

    public function update(DeveloperProjectRequest $request, DeveloperProject $project)
    {
        $this->authorize('update', $project);

        $project->fill($request->validated());
        $project->save();

        return $this->httpResponse()
            ->setPreviousUrl(route('developer.projects.index'))
            ->setMessage('Developer project updated successfully.');
    }

    public function destroy(DeveloperProject $project)
    {
        $this->authorize('delete', $project);

        return DeleteResourceAction::make($project);
    }
}
