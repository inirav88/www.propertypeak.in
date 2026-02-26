@php
    $model = $model ?? $property ?? null;
@endphp

@if ($model->facilities->isNotEmpty())
    <style>
        .single-property-nearby {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }
        
        .single-property-nearby .title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #1a1a1a;
        }
        
        .single-property-nearby > .body-2 {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        
        .nearby-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
        }
        
        .nearby-card {
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        
        .nearby-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        
        .nearby-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .nearby-card-header i,
        .nearby-card-header svg {
            color: #3b82f6;
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .nearby-card-header .category-name {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            flex: 1;
        }
        
        .nearby-card-body {
            padding: 12px 16px;
        }
        
        .nearby-places-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .nearby-place-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 12px;
            background: #fff;
            border-radius: 8px;
            font-size: 13px;
            color: #4b5563;
        }
        
        .nearby-place-name {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .nearby-distance-badge {
            font-weight: 600;
            color: #3b82f6;
            font-size: 12px;
            background: #eff6ff;
            padding: 4px 10px;
            border-radius: 12px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        
        .nearby-within-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            color: #059669;
            font-size: 12px;
            background: #d1fae5;
            padding: 4px 10px;
            border-radius: 12px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        
        @media (max-width: 768px) {
            .single-property-nearby {
                padding: 20px;
            }
            
            .nearby-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div @class(['single-property-nearby', $class ?? null])>
        <div class="h7 title fw-7">{{ __('What\'s nearby?') }}</div>
        <p class="body-2">{{ __("Explore nearby amenities to precisely locate your property and identify surrounding conveniences, providing a comprehensive overview of the living environment and the property's convenience.") }}</p>
        
        <div class="nearby-grid">
            @foreach ($model->facilities as $facility)
                @php
                    // Parse the facility data
                    $categoryName = $facility->name;
                    $locationsData = $facility->pivot->distance ?? '';
                    
                    // Split by pipe to get individual locations
                    $locations = [];
                    if (strpos($locationsData, '|') !== false) {
                        $locations = array_map('trim', explode('|', $locationsData));
                    } elseif (!empty($locationsData)) {
                        $locations = [$locationsData];
                    }
                    
                    // Extract "within X kms" pattern if present
                    $withinDistance = '';
                    foreach ($locations as $key => $location) {
                        if (preg_match('/within\s+(\d+\s*(kms?|km|miles?))/i', $location, $matches)) {
                            $withinDistance = $matches[1];
                            // Remove the "within" part from the location
                            $locations[$key] = preg_replace('/within\s+\d+\s*(kms?|km|miles?)\s*/i', '', $location);
                            break;
                        }
                    }
                @endphp
                
                <div class="nearby-card">
                    <div class="nearby-card-header">
                        @if($facility->icon)
                            {!! BaseHelper::renderIcon($facility->icon) !!}
                        @else
                            {!! BaseHelper::renderIcon('ti ti-map-pin') !!}
                        @endif
                        <span class="category-name">{{ $categoryName }}</span>
                        @if($withinDistance)
                            <span class="nearby-within-badge">
                                <i class="ti ti-walk"></i>
                                Within {{ $withinDistance }}
                            </span>
                        @endif
                    </div>
                    <div class="nearby-card-body">
                        <div class="nearby-places-list">
                            @foreach($locations as $location)
                                @php
                                    // Extract place name and distance
                                    $placeName = $location;
                                    $distanceText = '';
                                    
                                    // Match patterns like "2 kms", "5 kms" at the end
                                    if (preg_match('/(.*?)(\d+\s*(kms?|km|miles?))$/i', trim($location), $matches)) {
                                        $placeName = trim($matches[1]);
                                        $distanceText = trim($matches[2]);
                                    }
                                    
                                    // Skip empty entries
                                    if (empty($placeName)) continue;
                                @endphp
                                <div class="nearby-place-item">
                                    <span class="nearby-place-name">{{ $placeName }}</span>
                                    @if($distanceText)
                                        <span class="nearby-distance-badge">{{ $distanceText }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
