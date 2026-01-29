@extends('plugins/real-estate::themes.dashboard.layouts.master')

@section('content')
    <div class="dashboard-content-wrapper">
        <div class="dashboard-header">
            <h2>{{ __('Lead Management') }}</h2>
            @if(auth('account')->user()->hasPackageFeature('export leads'))
                <a href="{{ route('public.account.leads.export') }}" class="btn btn-primary">
                    <i class="fas fa-download"></i> {{ __('Export to CSV') }}
                </a>
            @endif
        </div>

        <div class="lead-stats-row">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $leads->where('status', 'new')->count() }}</h3>
                    <p>{{ __('New Leads') }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $leads->where('status', 'contacted')->count() }}</h3>
                    <p>{{ __('Contacted') }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $leads->where('status', 'converted')->count() }}</h3>
                    <p>{{ __('Converted') }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-secondary">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $leads->count() }}</h3>
                    <p>{{ __('Total Leads') }}</p>
                </div>
            </div>
        </div>

        <div class="leads-table-wrapper">
            <table class="table leads-table">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Contact') }}</th>
                        <th>{{ __('Property') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Score') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr data-lead-id="{{ $lead->id }}">
                            <td>{{ $lead->created_at->format('M d, Y') }}</td>
                            <td>
                                <strong>{{ $lead->name }}</strong>
                                @if($lead->created_at->diffInHours() < 24)
                                    <span class="badge badge-new">New</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $lead->email }}</div>
                                <div class="text-muted">{{ $lead->phone }}</div>
                            </td>
                            <td>
                                @if($lead->property)
                                    <a href="{{ $lead->property->url }}" target="_blank">
                                        {{ $lead->property->name }}
                                    </a>
                                @elseif($lead->project)
                                    <a href="{{ $lead->project->url }}" target="_blank">
                                        {{ $lead->project->name }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                <select class="form-select status-select" data-lead-id="{{ $lead->id }}">
                                    <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                                    <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>
                                        {{ __('Contacted') }}</option>
                                    <option value="interested" {{ $lead->status == 'interested' ? 'selected' : '' }}>
                                        {{ __('Interested') }}</option>
                                    <option value="not_interested" {{ $lead->status == 'not_interested' ? 'selected' : '' }}>
                                        {{ __('Not Interested') }}</option>
                                    <option value="converted" {{ $lead->status == 'converted' ? 'selected' : '' }}>
                                        {{ __('Converted') }}</option>
                                </select>
                            </td>
                            <td>
                                <span
                                    class="lead-score score-{{ $lead->score >= 70 ? 'high' : ($lead->score >= 40 ? 'medium' : 'low') }}">
                                    {{ $lead->score }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('public.account.leads.show', $lead->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> {{ __('View') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p>{{ __('No leads yet. Leads will appear here when someone contacts you about your properties.') }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $leads->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.status-select').on('change', function () {
                    const leadId = $(this).data('lead-id');
                    const status = $(this).val();
                    const $select = $(this);

                    $.ajax({
                        url: `/account/leads/${leadId}/status`,
                        method: 'POST',
                        data: {
                            status: status,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            toastr.success('{{ __("Lead status updated successfully") }}');
                        },
                        error: function () {
                            toastr.error('{{ __("Failed to update status") }}');
                            $select.val($select.data('original-value'));
                        }
                    });
                });

                // Store original values
                $('.status-select').each(function () {
                    $(this).data('original-value', $(this).val());
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .lead-stats-row {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }

            .stat-card {
                background: white;
                border-radius: 8px;
                padding: 20px;
                display: flex;
                align-items: center;
                gap: 15px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 24px;
            }

            .stat-icon.bg-primary {
                background: #db1d23;
            }

            .stat-icon.bg-info {
                background: #17a2b8;
            }

            .stat-icon.bg-success {
                background: #28a745;
            }

            .stat-icon.bg-secondary {
                background: #6c757d;
            }

            .stat-content h3 {
                margin: 0;
                font-size: 28px;
                font-weight: bold;
            }

            .stat-content p {
                margin: 0;
                color: #6c757d;
            }

            .leads-table-wrapper {
                background: white;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .leads-table {
                width: 100%;
            }

            .leads-table th {
                background: #f8f9fa;
                padding: 12px;
                font-weight: 600;
            }

            .leads-table td {
                padding: 12px;
                border-bottom: 1px solid #dee2e6;
            }

            .badge-new {
                background: #ffc107;
                color: #000;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 11px;
                margin-left: 5px;
            }

            .lead-score {
                padding: 4px 12px;
                border-radius: 12px;
                font-weight: 600;
            }

            .lead-score.score-high {
                background: #d4edda;
                color: #155724;
            }

            .lead-score.score-medium {
                background: #fff3cd;
                color: #856404;
            }

            .lead-score.score-low {
                background: #f8d7da;
                color: #721c24;
            }

            .dashboard-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
            }
        </style>
    @endpush
@endsection