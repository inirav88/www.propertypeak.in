<?php

namespace Botble\RealEstate\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\RepeaterField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\RealEstate\Facades\RealEstateHelper;
use Botble\RealEstate\Http\Requests\PackageRequest;
use Botble\RealEstate\Models\Currency;
use Botble\RealEstate\Models\Package;

class PackageForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts(['input-mask']);

        $currencies = Currency::query()->pluck('title', 'id')->all();

        $this
            ->model(Package::class)
            ->setValidatorClass(PackageRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('price', 'text', [
                'label' => trans('plugins/real-estate::package.price'),
                'wrapper' => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr' => [
                    'id' => 'price-number',
                    'placeholder' => trans('plugins/real-estate::package.price'),
                    'class' => 'form-control input-mask-number',
                    'data-thousands-separator' => RealEstateHelper::getThousandSeparatorForInputMask(),
                    'data-decimal-separator' => RealEstateHelper::getDecimalSeparatorForInputMask(),
                ],
            ])
            ->add('currency_id', 'customSelect', [
                'label' => trans('plugins/real-estate::package.currency'),
                'wrapper' => [
                    'class' => 'form-group mb-3 col-md-6',
                ],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => $currencies,
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('percent_save', 'text', [
                'label' => trans('plugins/real-estate::package.percent_save'),
                'wrapper' => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr' => [
                    'id' => 'percent-save-number',
                    'placeholder' => trans('plugins/real-estate::package.percent_save'),
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('number_of_listings', 'text', [
                'label' => trans('plugins/real-estate::package.number_of_listings'),
                'wrapper' => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr' => [
                    'id' => 'price-number',
                    'placeholder' => trans('plugins/real-estate::package.number_of_listings'),
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('number_of_projects', 'text', [
                'label' => __('Number of Projects'),
                'wrapper' => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr' => [
                    'placeholder' => __('Number of projects (for builders)'),
                    'class' => 'form-control input-mask-number',
                ],
                'help_block' => [
                    'text' => __('Leave empty for unlimited or if not applicable'),
                ],
            ])
            ->add('account_limit', 'text', [
                'label' => trans('plugins/real-estate::package.account_limit'),
                'wrapper' => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr' => [
                    'id' => 'percent-save-number',
                    'placeholder' => trans('plugins/real-estate::package.account_limit_placeholder'),
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('package_type', 'customSelect', [
                'label' => __('Package Type'),
                'wrapper' => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => [
                    'agent' => __('Agent/Broker'),
                    'builder' => __('Builder/Developer'),
                    'owner' => __('Property Owner'),
                    'addon' => __('Add-on Service'),
                ],
                'default_value' => 'agent',
            ])
            ->add('duration_days', 'text', [
                'label' => __('Duration (Days)'),
                'wrapper' => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'attr' => [
                    'placeholder' => __('E.g., 30, 90, 180'),
                    'class' => 'form-control input-mask-number',
                ],
                'help_block' => [
                    'text' => __('For one-time packages only. Leave empty for recurring packages.'),
                ],
            ])
            ->add('is_recurring', OnOffField::class, [
                'label' => __('Recurring Subscription'),
                'wrapper' => [
                    'class' => 'form-group mb-3 col-md-4',
                ],
                'default_value' => true,
                'help_block' => [
                    'text' => __('Turn off for one-time packages'),
                ],
            ])
            ->add('rowClose3', 'html', [
                'html' => '</div>',
            ])
            ->add('is_default', OnOffField::class, [
                'label' => trans('core/base::forms.is_default'),
                'default_value' => false,
            ])
            ->add('microsite_enabled', OnOffField::class, [
                'label' => __('Enable Microsite Feature'),
                'default_value' => true,
                'help_block' => [
                    'text' => __('If enabled, users with this package can create their own builder microsite.'),
                ],
            ])
            ->add('features', RepeaterField::class, [
                'label' => __('Features'),
                'fields' => [
                    [
                        'type' => 'text',
                        'label' => __('Title'),
                        'attributes' => [
                            'name' => 'text',
                            'value' => null,
                            'options' => [
                                'class' => 'form-control',
                                'data-counter' => 255,
                                'placeholder' => __('Ex: 60-Day Job Postings'),
                            ],
                        ],
                    ],
                ],
            ])
            ->add('order', 'number', [
                'label' => trans('core/base::forms.order'),
                'attr' => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
