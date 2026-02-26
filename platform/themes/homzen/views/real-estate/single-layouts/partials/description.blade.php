@php
    $model = $model ?? $property ?? null;
@endphp

<style>
/* Overview Section UI/UX Fixes - Inline for priority */
.single-property-overview {
    background: #fff !important;
    padding: 30px !important;
    border-radius: 16px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08) !important;
    margin-bottom: 30px !important;
}

.single-property-overview .title {
    font-size: 20px !important;
    font-weight: 700 !important;
    margin-bottom: 24px !important;
    color: #1a1a1a !important;
}

/* Use CSS Grid for consistent layout */
.single-property-overview .info-box {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
    gap: 16px !important;
}

.single-property-overview .info-box .item {
    display: flex !important;
    align-items: flex-start !important;
    gap: 12px !important;
    padding: 16px !important;
    background: #f8f9fa !important;
    border-radius: 12px !important;
    border: 1px solid #e5e7eb !important;
    transition: all 0.2s ease !important;
}

.single-property-overview .info-box .item:hover {
    background: #f3f4f6 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
}

.single-property-overview .info-box .box-icon {
    width: 44px !important;
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #fff !important;
    border-radius: 10px !important;
    color: #3b82f6 !important;
    font-size: 20px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.06) !important;
    flex-shrink: 0 !important;
}

.single-property-overview .info-box .content {
    flex: 1 !important;
    min-width: 0 !important;
    overflow-wrap: break-word !important;
    word-wrap: break-word !important;
    word-break: break-word !important;
}

.single-property-overview .label {
    font-size: 11px !important;
    color: #6b7280 !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    display: block !important;
    margin-bottom: 4px !important;
}

.single-property-overview .content span:last-child {
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #111827 !important;
    line-height: 1.5 !important;
    display: block !important;
}

/* Description Section */
.single-property-desc {
    background: #fff !important;
    padding: 30px !important;
    border-radius: 16px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08) !important;
    margin-bottom: 30px !important;
}

.single-property-desc .title {
    font-size: 20px !important;
    font-weight: 700 !important;
    margin-bottom: 16px !important;
}

/* Responsive */
@media (max-width: 768px) {
    .single-property-overview .info-box {
        grid-template-columns: 1fr !important;
    }
}
</style>

@if ($model->content || ($model->private_notes ?? null))
    <div @class(['single-property-desc', $class ?? null])>
        @if($model->content)
            <div class="h7 title fw-7">{{ __('Description') }}</div>
            <div class="body-2 text-variant-1">
                <div class="ck-content single-detail">
                    {!! BaseHelper::clean($model->content) !!}
                </div>
            </div>
        @endif

        @if(($model->can_see_private_notes ?? false) && ($model->private_notes ?? null))
            <div class="bd-callout bd-callout-info mt-4">
                <div class="h7 title fw-7 mb-2">{{ __('Private Notes') }}</div>
                {!! BaseHelper::clean(nl2br($model->private_notes)) !!}
            </div>
        @endif
    </div>
@endif

<div @class(['single-property-overview', $class ?? null])>
    <div class="h7 title fw-7">{{ __('Overview') }}</div>
    <div class="info-box">
        <div class="item">
            <div class="box-icon w-52">
                <x-core::icon name="ti ti-home" />
            </div>
            <div class="content">
                <span class="label">
                    @if($model instanceof \Botble\RealEstate\Models\Project)
                        {{ __('Project ID:') }}
                    @else
                        {{ __('Property ID:') }}
                    @endif
                </span>
                <span>{{ $model->unique_id ?: $model->getKey() }}</span>
            </div>
        </div>
        @if ($model->categories->isNotEmpty())
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-category" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Type:') }}</span>
                    <span>
                        @foreach ($model->categories as $category)
                            <a href="{{ $category->url }}">{!! BaseHelper::clean($category->name) !!}</a>@if (!$loop->last),&nbsp;@endif
                        @endforeach
                    </span>
                </div>
            </div>
        @endif
        @if (($model->investor->name ?? null))
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-building" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Investor:') }}</span>
                    <span>{{ $model->investor->name }}</span>
                </div>
            </div>
        @endif
        @if (($model->number_block ?? null))
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-packages" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Blocks:') }}</span>
                    <span>{{ number_format($model->number_block) }}</span>
                </div>
            </div>
        @endif
        @if (($model->number_flat ?? null))
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-building" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Flats:') }}</span>
                    <span>{{ number_format($model->number_flat) }}</span>
                </div>
            </div>
        @endif
        @if (($model->number_bedroom ?? null))
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-bed" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Bedrooms:') }}</span>
                    <span>{{ number_format($model->number_bedroom) }}</span>
                </div>
            </div>
        @endif
        @if (($model->number_bathroom ?? null))
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-bath" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Bathrooms:') }}</span>
                    <span>{{ number_format($model->number_bathroom) }}</span>
                </div>
            </div>
        @endif
        @if (($model->number_floor ?? null))
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-stairs" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Floors:') }}</span>
                    <span>{{ number_format($model->number_floor) }}</span>
                </div>
            </div>
        @endif
        @if (($model->square ?? null))
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-ruler-2" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Square:') }}</span>
                    <span>{{ $model->square_text }}</span>
                </div>
            </div>
        @endif
        @if (($model->date_finish ?? null))
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-calendar-check" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Finish Date:') }}</span>
                    <span>{{ $model->date_finish->format('M d, Y') }}</span>
                </div>
            </div>
        @endif
        @if (($model->date_sell ?? null))
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-calendar-dollar" />
                </div>
                <div class="content">
                    <span class="label">{{ __('Open Sell Date:') }}</span>
                    <span>{{ $model->date_sell->format('M d, Y') }}</span>
                </div>
            </div>
        @endif
        @foreach ($model->customFields as $customField)
            @continue(! $customField->value)
            <div class="item">
                <div class="box-icon w-52">
                    <x-core::icon name="ti ti-box" />
                </div>
                <div class="content">
                    <span class="label">{!! BaseHelper::clean($customField->name) !!}:</span>
                    <span>{!! BaseHelper::clean($customField->value) !!}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
