@extends('plugins/real-estate::themes.dashboard.layouts.master')

@section('content')
    <div class="dashboard-content-wrapper">
        <div class="lead-detail-header">
            <div>
                <a href="{{ route('public.account.leads.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Leads') }}
                </a>
            </div>
            <div>
                <span class="lead-date">{{ $lead->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>

        <div class="lead-detail-grid">
            <div class="lead-main-info">
                <div class="info-card">
                    <h3>{{ __('Contact Information') }}</h3>
                    <div class="info-row">
                        <label>{{ __('Name') }}:</label>
                        <span>{{ $lead->name }}</span>
                    </div>
                    <div class="info-row">
                        <label>{{ __('Email') }}:</label>
                        <span><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></span>
                    </div>
                    <div class="info-row">
                        <label>{{ __('Phone') }}:</label>
                        <span><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></span>
                    </div>
                    <div class="info-row">
                        <label>{{ __('IP Address') }}:</label>
                        <span>{{ $lead->ip_address }}</span>
                    </div>
                </div>

                <div class="info-card">
                    <h3>{{ __('Property Details') }}</h3>
                    @if($lead->property)
                        <div class="property-info">
                            <img src="{{ $lead->property->image_thumb }}" alt="{{ $lead->property->name }}">
                            <div>
                                <h4><a href="{{ $lead->property->url }}" target="_blank">{{ $lead->property->name }}</a></h4>
                                <p>{{ $lead->property->location }}</p>
                                <p class="price">{{ $lead->property->price_formatted }}</p>
                            </div>
                        </div>
                    @elseif($lead->project)
                        <div class="property-info">
                            <img src="{{ $lead->project->image_thumb }}" alt="{{ $lead->project->name }}">
                            <div>
                                <h4><a href="{{ $lead->project->url }}" target="_blank">{{ $lead->project->name }}</a></h4>
                                <p>{{ $lead->project->location }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="info-card">
                    <h3>{{ __('Message') }}</h3>
                    <p>{{ $lead->content }}</p>
                </div>

                @if(auth('account')->user()->hasPackageFeature('lead notes'))
                    <div class="info-card">
                        <h3>{{ __('Notes') }}</h3>
                        <div class="notes-list">
                            @php
                                $notes = $lead->notes ? json_decode($lead->notes, true) : [];
                            @endphp
                            @forelse($notes as $note)
                                <div class="note-item">
                                    <div class="note-header">
                                        <strong>{{ $note['created_by'] ?? 'You' }}</strong>
                                        <span
                                            class="note-date">{{ \Carbon\Carbon::parse($note['created_at'])->diffForHumans() }}</span>
                                    </div>
                                    <p>{{ $note['text'] }}</p>
                                </div>
                            @empty
                                <p class="text-muted">{{ __('No notes yet') }}</p>
                            @endforelse
                        </div>

                        <form id="add-note-form" class="mt-3">
                            <div class="form-group">
                                <textarea name="note" class="form-control" rows="3" placeholder="{{ __('Add a note...') }}"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('Add Note') }}
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="lead-sidebar">
                <div class="info-card">
                    <h3>{{ __('Lead Status') }}</h3>
                    <select id="lead-status" class="form-select">
                        <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                        <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>{{ __('Contacted') }}
                        </option>
                        <option value="interested" {{ $lead->status == 'interested' ? 'selected' : '' }}>
                            {{ __('Interested') }}</option>
                        <option value="not_interested" {{ $lead->status == 'not_interested' ? 'selected' : '' }}>
                            {{ __('Not Interested') }}</option>
                        <option value="converted" {{ $lead->status == 'converted' ? 'selected' : '' }}>{{ __('Converted') }}
                        </option>
                    </select>
                </div>

                <div class="info-card">
                    <h3>{{ __('Lead Score') }}</h3>
                    <div class="score-display">
                        <div
                            class="score-circle score-{{ $lead->score >= 70 ? 'high' : ($lead->score >= 40 ? 'medium' : 'low') }}">
                            {{ $lead->score }}
                        </div>
                        <p class="text-muted">{{ __('Out of 100') }}</p>
                    </div>
                </div>

                @if(auth('account')->user()->hasPackageFeature('follow-up reminders'))
                    <div class="info-card">
                        <h3>{{ __('Follow-up Reminder') }}</h3>
                        <input type="datetime-local" id="follow-up-date" class="form-control"
                            value="{{ $lead->follow_up_date ? $lead->follow_up_date->format('Y-m-d\TH:i') : '' }}">
                        <button type="button" id="set-reminder" class="btn btn-primary mt-2 w-100">
                            <i class="fas fa-bell"></i> {{ __('Set Reminder') }}
                        </button>
                    </div>
                @endif

                <div class="info-card">
                    <h3>{{ __('Timeline') }}</h3>
                    <div class="timeline">
                        <div class="timeline-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>{{ __('Lead Received') }}</strong>
                                <span>{{ $lead->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if($lead->contacted_at)
                            <div class="timeline-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>{{ __('Contacted') }}</strong>
                                    <span>{{ $lead->contacted_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Update status
                $('#lead-status').on('change', function () {
                    const status = $(this).val();

                    $.ajax({
                        url: '{{ route("public.account.leads.update-status", $lead->id) }}',
                        method: 'POST',
                        data: {
                            status: status,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            toastr.success('{{ __("Status updated successfully") }}');
                        }
                    });
                });

                // Add note
                $('#add-note-form').on('submit', function (e) {
                    e.preventDefault();
                    const note = $(this).find('textarea[name="note"]').val();

                    $.ajax({
                        url: '{{ route("public.account.leads.add-note", $lead->id) }}',
                        method: 'POST',
                        data: {
                            note: note,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            toastr.success('{{ __("Note added successfully") }}');
                            location.reload();
                        }
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .lead-detail-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
            }

            .lead-detail-grid {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 20px;
            }

            .info-card {
                background: white;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .info-card h3 {
                margin-top: 0;
                margin-bottom: 15px;
                font-size: 18px;
            }

            .info-row {
                display: flex;
                padding: 10px 0;
                border-bottom: 1px solid #f0f0f0;
            }

            .info-row label {
                font-weight: 600;
                width: 120px;
            }

            .property-info {
                display: flex;
                gap: 15px;
            }

            .property-info img {
                width: 100px;
                height: 100px;
                object-fit: cover;
                border-radius: 8px;
            }

            .note-item {
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
                margin-bottom: 10px;
            }

            .note-header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
            }

            .note-date {
                color: #6c757d;
                font-size: 12px;
            }

            .score-display {
                text-align: center;
            }

            .score-circle {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                font-weight: bold;
                margin: 0 auto 10px;
            }

            .score-circle.score-high {
                background: #d4edda;
                color: #155724;
            }

            .score-circle.score-medium {
                background: #fff3cd;
                color: #856404;
            }

            .score-circle.score-low {
                background: #f8d7da;
                color: #721c24;
            }

            .timeline {
                position: relative;
            }

            .timeline-item {
                display: flex;
                gap: 15px;
                margin-bottom: 20px;
            }

            .timeline-item i {
                width: 40px;
                height: 40px;
                background: #db1d23;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .timeline-item div {
                flex: 1;
            }

            .timeline-item strong {
                display: block;
            }

            .timeline-item span {
                color: #6c757d;
                font-size: 13px;
            }
        </style>
    @endpush
@endsection