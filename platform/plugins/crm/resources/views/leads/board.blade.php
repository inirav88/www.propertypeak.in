@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">CRM Pipeline Board</h4>
            <a href="{{ route('crm.leads.index') }}" class="btn btn-sm btn-outline-secondary">List view</a>
        </div>
        <div class="card-body">
            <div class="row g-3 crm-board" id="crm-board">
                @foreach($stages as $stage)
                    <div class="col-md-4 col-xl-2">
                        <div class="card h-100" data-stage="{{ $stage }}">
                            <div class="card-header text-capitalize fw-bold">
                                {{ str_replace('_', ' ', $stage) }}
                            </div>
                            <div class="card-body stage-column min-vh-25" data-stage="{{ $stage }}">
                                @foreach($leadsByStage[$stage] as $lead)
                                    <div class="card mb-2 crm-lead-card" draggable="true" data-lead-id="{{ $lead->id }}">
                                        <div class="card-body p-2">
                                            <div class="fw-semibold">{{ $lead->name }}</div>
                                            <div class="small text-muted">{{ $lead->email }}</div>
                                            <div class="small text-muted">{{ $lead->phone ?: '-' }}</div>
                                            <div class="small mt-1">
                                                Assigned: {{ $lead->assigned_to ?: 'Unassigned' }}
                                            </div>
                                            @if($lead->follow_up_at)
                                                <div class="small text-warning">
                                                    Follow up: {{ $lead->follow_up_at->format('Y-m-d H:i') }}
                                                </div>
                                            @endif
                                            @if(in_array($stage, ['won', 'lost'], true))
                                                <span class="badge bg-secondary mt-1">Closed</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <input type="hidden" id="crm-csrf-token" value="{{ csrf_token() }}">
@endsection

@push('footer')
<script>
(() => {
    const cards = document.querySelectorAll('.crm-lead-card');
    const columns = document.querySelectorAll('.stage-column');
    const csrfToken = document.getElementById('crm-csrf-token')?.value || '';
    let draggedCard = null;

    cards.forEach((card) => {
        card.addEventListener('dragstart', () => {
            draggedCard = card;
            card.classList.add('opacity-50');
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('opacity-50');
        });
    });

    columns.forEach((column) => {
        column.addEventListener('dragover', (event) => {
            event.preventDefault();
        });

        column.addEventListener('drop', async (event) => {
            event.preventDefault();

            if (!draggedCard) {
                return;
            }

            const leadId = draggedCard.dataset.leadId;
            const stage = column.dataset.stage;

            try {
                const response = await fetch(`{{ route('crm.leads.stage', ['lead' => '__LEAD__']) }}`.replace('__LEAD__', leadId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ stage }),
                });

                if (!response.ok) {
                    throw new Error('Failed to move lead stage.');
                }

                column.appendChild(draggedCard);
            } catch (error) {
                console.error(error);
                window.alert('Unable to move lead stage. Please try again.');
            }
        });
    });
})();
</script>
@endpush
