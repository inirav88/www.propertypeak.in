<?php

namespace Botble\Developer\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Developer\Http\Requests\DeveloperProfileRequest;
use Botble\Developer\Models\DeveloperProfile;

class DeveloperProfileController extends BaseController
{
    public function index()
    {
        $this->authorize('viewAny', DeveloperProfile::class);

        $profiles = DeveloperProfile::query()->latest()->paginate(20);

        $this->pageTitle('Developers');

        return view('plugins/developer::developers.index', compact('profiles'));
    }

    public function create()
    {
        $this->authorize('create', DeveloperProfile::class);

        $profile = new DeveloperProfile();

        $this->pageTitle('Create Developer');

        return view('plugins/developer::developers.form', compact('profile'));
    }

    public function store(DeveloperProfileRequest $request)
    {
        $this->authorize('create', DeveloperProfile::class);

        $profile = DeveloperProfile::query()->create($request->validated());

        return $this->httpResponse()
            ->setNextUrl(route('developer.profiles.edit', $profile->id))
            ->setPreviousUrl(route('developer.profiles.index'))
            ->setMessage('Developer created successfully.');
    }

    public function edit(DeveloperProfile $developer)
    {
        $this->authorize('update', $developer);

        $profile = $developer;

        $this->pageTitle('Edit Developer');

        return view('plugins/developer::developers.form', compact('profile'));
    }

    public function update(DeveloperProfileRequest $request, DeveloperProfile $developer)
    {
        $this->authorize('update', $developer);

        $developer->fill($request->validated());
        $developer->save();

        return $this->httpResponse()
            ->setPreviousUrl(route('developer.profiles.index'))
            ->setMessage('Developer updated successfully.');
    }

    public function destroy(DeveloperProfile $developer)
    {
        $this->authorize('delete', $developer);

        return DeleteResourceAction::make($developer);
    }
}
