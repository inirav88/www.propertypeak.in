<?php

namespace Botble\RealEstate\Forms;

use Botble\RealEstate\Http\Requests\AccountProjectRequest;
use Botble\RealEstate\Models\Project;

class AccountProjectForm extends ProjectForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->model(Project::class)
            ->template('plugins/real-estate::account.forms.base')
            ->hasFiles()
            ->setValidatorClass(AccountProjectRequest::class)
            ->remove('is_featured')
            ->remove('author_id')
            ->remove('featured_priority')
            ->remove('private_notes');

        // Ensure "Project Name" is clearly labeled and is the primary field
        $this->modify('name', 'text', [
            'label' => trans('plugins/real-estate::project.project_name'),
            'attr' => [
                'placeholder' => trans('plugins/real-estate::project.project_name'),
            ],
        ], true);
    }
}
