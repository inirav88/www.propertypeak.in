<?php

namespace Botble\RealEstate\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\Project;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class AccountProjectTable extends ProjectTable
{
    public function setup(): void
    {
        $this
            ->model(Project::class)
            ->addActions([
                EditAction::make()->route('public.account.projects.edit'),
                DeleteAction::make()->route('public.account.projects.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('unique_id', function (Project $item) {
                return BaseHelper::clean($item->unique_id ?: '&mdash;');
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'images',
                'created_at',
                'status',
                'views',
                'unique_id',
            ])
            ->where([
                'author_id' => auth('account')->id(),
                'author_type' => Account::class,
            ]);

        return $this->applyScopes($query);
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('public.account.projects.create'));
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            ImageColumn::make()
                ->searchable(false)
                ->orderable(false),
            NameColumn::make()->route('public.account.projects.edit'),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }
}
