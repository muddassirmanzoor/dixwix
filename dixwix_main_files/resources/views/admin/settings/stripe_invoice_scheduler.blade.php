@if(session()->has('success'))
<div class="alert alert-success">
    {{ session()->get('success') }}
</div>
@endif
@if(session()->has('error'))
<div class="alert alert-danger">
    {{ session()->get('error') }}
</div>
@endif

<div class="inner_content_table_actions d-flex flex-column flex-md-row mt-4 justify-content-between align-items-center">
    <h4 class="inner_content_title">{{ $data['title'] }}</h4>
</div>

<div class="card mt-3">
    <div class="card-body">
        <form method="POST" action="{{ route('stripe-invoice-scheduler.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Recurring Days</label>
                    <input type="number" name="recurring_days" class="form-control" required min="1" max="31" value="{{ old('recurring_days', 30) }}" placeholder="e.g., 15, 20, 30">
                    <small class="text-muted">Schedule will run automatically every X days (max 31 days)</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Next Run Date</label>
                    <input type="text" class="form-control" readonly value="{{ now()->addDays(old('recurring_days', 30))->format('Y-m-d H:i:s') }}" id="next_run_preview">
                    <small class="text-muted">This will be calculated automatically</small>
                </div>
            </div>
            <script>
                document.querySelector('input[name="recurring_days"]').addEventListener('input', function(e) {
                    const days = parseInt(e.target.value) || 30;
                    const nextRun = new Date();
                    nextRun.setDate(nextRun.getDate() + days);
                    document.getElementById('next_run_preview').value = nextRun.toISOString().slice(0, 19).replace('T', ' ');
                });
            </script>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Schedule Stripe Invoices</button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Recent Schedules</h5>
            <div>
                <a href="{{ route('stripe-invoice-scheduler.index', ['show_deleted' => request('show_deleted') == '1' ? '0' : '1']) }}" 
                   class="btn btn-sm {{ request('show_deleted') == '1' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                    {{ request('show_deleted') == '1' ? 'Show Active' : 'Show Deleted' }}
                </a>
            </div>
        </div>
        <style>
            /* keep dates/ranges/buttons from wrapping awkwardly */
            .nowrap { white-space: nowrap; }
            .actions-cell form { display: inline-block; margin-right: 6px; }
            .actions-cell form:last-child { margin-right: 0; }
        </style>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Recurring Days</th>
                        <th>Next Run</th>
                        <th>Last Run</th>
                        <th>Run At</th>
                        <th>Summary</th>
                        <th>Error</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $s)
                    @php
                        $isParent = is_null($s->parent_schedule_id);
                    @endphp
                    <tr class="{{ $s->trashed() ? 'table-secondary' : '' }}">
                        <td>
                            {{ $s->id }}
                            @if($s->trashed())
                                <span class="badge bg-danger">Deleted</span>
                            @endif
                        </td>
                        <td>
                            @if($isParent)
                                <span class="badge bg-primary">Recurring</span>
                            @else
                                <span class="badge bg-secondary">Run</span>
                                <br><small class="text-muted">Parent: #{{ $s->parent_schedule_id }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $s->status === 'active' ? 'success' : ($s->status === 'completed' ? 'info' : ($s->status === 'running' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td>{{ ($s->recurring_days ?? 'N/A') . ($s->recurring_days ? ' days' : '') }}</td>
                        <td class="nowrap">{{ $s->next_run_at ? $s->next_run_at->format('Y-m-d H:i') : 'N/A' }}</td>
                        <td class="nowrap">{{ $s->last_run_at ? $s->last_run_at->format('Y-m-d H:i') : 'Never' }}</td>
                        <td class="nowrap">
                            @if($isParent)
                                {{ $s->next_run_at ? $s->next_run_at->format('Y-m-d') : 'N/A' }}
                            @else
                                {{ $s->run_at ? $s->run_at->format('Y-m-d') : 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @if(is_array($s->result_summary))
                                processed={{ $s->result_summary['processed_users'] ?? 0 }},
                                sent={{ $s->result_summary['sent'] ?? 0 }},
                                skipped={{ $s->result_summary['skipped'] ?? 0 }},
                                failed={{ $s->result_summary['failed'] ?? 0 }}
                            @endif
                        </td>
                        <td>{{ $s->error }}</td>
                        <td class="nowrap actions-cell">
                            <a href="{{ route('stripe-invoice-scheduler.show', ['id' => $s->id]) }}" class="btn btn-sm btn-primary mb-1">View</a>

                            @if($s->trashed())
                                <form method="POST" action="{{ route('stripe-invoice-scheduler.restore', ['id' => $s->id]) }}" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success mb-1" onclick="return confirm('Restore this schedule?')">Restore</button>
                                </form>
                                <form method="POST" action="{{ route('stripe-invoice-scheduler.destroy', ['id' => $s->id]) }}" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Permanently delete this schedule? This cannot be undone!')">Force Delete</button>
                                </form>
                            @else
                                @if($s->is_active && $s->status === 'active')
                                    <form method="POST" action="{{ route('stripe-invoice-scheduler.cancel', ['id' => $s->id]) }}" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning mb-1" onclick="return confirm('Deactivate this recurring schedule?')">Deactivate</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('stripe-invoice-scheduler.destroy', ['id' => $s->id]) }}" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('{{ $s->is_active && $s->status === 'active' ? 'This schedule is active. Are you sure you want to delete it? It will be deactivated first.' : 'Delete this schedule? It will be soft deleted and can be restored.' }}')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10">No schedules yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


