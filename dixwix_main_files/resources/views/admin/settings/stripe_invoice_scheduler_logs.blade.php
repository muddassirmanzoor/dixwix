<div class="inner_content_table_actions d-flex flex-column flex-md-row mt-4 justify-content-between align-items-center">
    <h4 class="inner_content_title">Schedule #{{ $schedule->id }} - Run Logs</h4>
    <div>
        <a href="{{ route('stripe-invoice-scheduler.show', ['id' => $schedule->id]) }}" class="btn btn-secondary btn-sm">Back to Schedule</a>
        <a href="{{ route('stripe-invoice-scheduler.index') }}" class="btn btn-secondary btn-sm">All Schedules</a>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h5>Schedule Info</h5>
        <p class="mb-1"><strong>ID:</strong> {{ $schedule->id }}</p>
        <p class="mb-1"><strong>Recurring Days:</strong> {{ $schedule->recurring_days ?? 'N/A' }} days</p>
        <p class="mb-1"><strong>Status:</strong> {{ ucfirst($schedule->status) }}</p>
        @if($schedule->trashed())
            <p class="mb-0 text-danger"><strong>Status:</strong> Deleted (soft delete)</p>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="mb-3">All Run Logs ({{ $logs->total() }} total)</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Run At</th>
                        <th>Status</th>
                        <th>Date Range</th>
                        <th>Result Summary</th>
                        <th>Error</th>
                        <th>Completed At</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->run_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <span class="badge bg-{{ $log->status === 'completed' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td>
                            @if($log->range_from && $log->range_to)
                                {{ $log->range_from->format('Y-m-d') }}<br>
                                to {{ $log->range_to->format('Y-m-d') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if(is_array($log->result_summary))
                                <small>
                                    processed: {{ $log->result_summary['processed_users'] ?? 0 }}<br>
                                    sent: {{ $log->result_summary['sent'] ?? 0 }}<br>
                                    skipped: {{ $log->result_summary['skipped'] ?? 0 }}<br>
                                    failed: {{ $log->result_summary['failed'] ?? 0 }}
                                </small>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($log->error)
                                <small class="text-danger">{{ Str::limit($log->error, 100) }}</small>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $log->completed_at ? $log->completed_at->format('Y-m-d H:i:s') : '-' }}</td>
                        <td>
                            @if($log->completed_at)
                                {{ $log->run_at->diffForHumans($log->completed_at, true) }}
                            @else
                                <span class="text-warning">Running...</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">No logs found for this schedule.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
        <div class="mt-3">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

