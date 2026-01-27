<div class="inner_content_table_actions d-flex flex-column flex-md-row mt-4 justify-content-between align-items-center">
    <h4 class="inner_content_title">
        @if($isParentSchedule)
            Recurring Schedule #{{ $schedule->id }} Details
        @else
            Run Schedule #{{ $schedule->id }} Details
            @if($schedule->parent)
                <small class="text-muted">(from Recurring Schedule #{{ $schedule->parent->id }})</small>
            @endif
        @endif
    </h4>
    <div>
        @if(!$isParentSchedule && $schedule->parent)
            <a href="{{ route('stripe-invoice-scheduler.show', ['id' => $schedule->parent->id]) }}" class="btn btn-info btn-sm">View Parent Schedule</a>
        @endif
        <a href="{{ route('stripe-invoice-scheduler.index') }}" class="btn btn-secondary btn-sm">Back to Schedules</a>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h5>Schedule Summary</h5>
        @php
            $summaryActiveSchedule = $isParentSchedule
                ? (($activeSchedule ?? null) ?: (($schedule->is_active && $schedule->status === 'active') ? $schedule : null))
                : null;
            $summaryStatus = $summaryActiveSchedule ? 'active' : $schedule->status;
            $summaryNextRunAt = $summaryActiveSchedule?->next_run_at ?? $schedule->next_run_at;
            $summaryLastRunAt = $schedule->last_run_at;
            if ($schedule->latestLog?->run_at && (!$summaryLastRunAt || $schedule->latestLog->run_at->gt($summaryLastRunAt))) {
                $summaryLastRunAt = $schedule->latestLog->run_at;
            }
        @endphp
        <p class="mb-1"><strong>Status:</strong> {{ ucfirst($summaryStatus) }} @if($isParentSchedule && !$summaryActiveSchedule) (inactive) @endif</p>
        @if($isParentSchedule)
            <p class="mb-1"><strong>Recurring Days:</strong> {{ $schedule->recurring_days ?? 'N/A' }} days</p>
            <p class="mb-1"><strong>Last Run:</strong> {{ $summaryLastRunAt ? $summaryLastRunAt->format('Y-m-d H:i:s') : 'Never' }}</p>
            <p class="mb-1"><strong>Next Run:</strong> {{ $summaryNextRunAt ? $summaryNextRunAt->format('Y-m-d H:i:s') : 'N/A' }}</p>
        @else
            <p class="mb-1"><strong>Run At:</strong> {{ $schedule->run_at ? $schedule->run_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
            <p class="mb-1"><strong>Date Range:</strong> 
                @if($schedule->range_from && $schedule->range_to)
                    {{ $schedule->range_from->format('Y-m-d') }} to {{ $schedule->range_to->format('Y-m-d') }}
                @else
                    N/A
                @endif
            </p>
        @endif
        @if(is_array($schedule->result_summary))
            <p class="mb-1">
                <strong>Result Summary:</strong>
                processed={{ $schedule->result_summary['processed_users'] ?? 0 }},
                sent={{ $schedule->result_summary['sent'] ?? 0 }},
                skipped={{ $schedule->result_summary['skipped'] ?? 0 }},
                failed={{ $schedule->result_summary['failed'] ?? 0 }}
            </p>
        @endif
        @if($schedule->error)
            <p class="text-danger mb-0"><strong>Error:</strong> {{ $schedule->error }}</p>
        @endif
    </div>
</div>

{{-- Preview Table: Only show for schedules that haven't completed yet --}}
@if(isset($previewData) && $previewData && !in_array($schedule->status, ['completed', 'failed']))
<div class="card mt-4 border-info">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">📋 Preview Table: Uninvoiced Entries That Will Be Processed @if($isParentSchedule)in Next Cron Run @else (if schedule runs now) @endif</h5>
        <p class="mb-0 mt-1 small"><strong>Note:</strong> This shows rental entries that have NOT been invoiced yet and will be processed when the cron runs.</p>
    </div>
    <div class="card-body">
        <div class="mb-3">
            @if($isParentSchedule)
                <p class="mb-1"><strong>Next Run:</strong> {{ $previewData['next_run_at']->format('Y-m-d H:i:s') }}</p>
            @else
                <p class="mb-1"><strong>Run At:</strong> {{ $schedule->run_at ? $schedule->run_at->format('Y-m-d H:i:s') : ($previewData['next_run_at']->format('Y-m-d H:i:s') ?? 'N/A') }}</p>
            @endif
            <p class="mb-1"><strong>Date Range:</strong> {{ $previewData['range_from']->format('Y-m-d') }} to {{ $previewData['range_to']->format('Y-m-d') }} (last {{ $previewData['recurring_days'] }} days)</p>
            <p class="mb-1"><strong>Total Users:</strong> {{ $previewData['total_users'] }} <span class="text-muted">(Each user will receive ONE consolidated invoice)</span></p>
            <p class="mb-1"><strong>Total Entries:</strong> {{ $previewData['total_entries'] }} <span class="text-muted">(Multiple entries per user will be summed)</span></p>
            <p class="mb-0"><strong>Amount charged to users (rental only):</strong> 
                <strong>${{ number_format($previewData['total_rental'], 2) }}</strong>
                <span class="text-muted"> — Commission ${{ number_format($previewData['total_commission'], 2) }} retained by platform (Dixwix)</span>
            </p>
            <div class="alert alert-info mt-2 mb-0">
                <small>
                    <strong>What is this Preview Table?</strong><br>
                    • This shows <strong>uninvoiced rental entries</strong> that will be processed when the cron runs<br>
                    • These entries have NOT been sent to Stripe yet<br>
                    • If a user has multiple entries, they will be <strong>automatically summed together</strong> and sent as <strong>ONE invoice</strong> to Stripe<br>
                    • The "Entry Count" column shows how many entries will be consolidated for each user<br>
                    • <strong>Different from Results Table below:</strong> Results show invoices that were already created and sent
                </small>
            </div>
        </div>

        @if(count($previewData['user_totals']) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Entry Count<br><small class="text-muted">(Will be summed)</small></th>
                        <th>Rental Total<br><small class="text-muted">(Sum of all entries)</small></th>
                        <th>Commission (→ platform)<br><small class="text-muted">(Deducted, goes to Dixwix)</small></th>
                        <th>Amount charged<br><small class="text-muted">(Rental - Commission, ONE invoice per user)</small></th>
                        <th>Stripe Customer ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($previewData['user_totals'] as $userId => $userData)
                    <tr>
                        <td>{{ $userData['user']->name ?? ('User #' . $userId) }}</td>
                        <td>{{ $userData['user']->email ?? 'N/A' }}</td>
                        <td>{{ $userData['entry_count'] }}</td>
                        <td>${{ number_format($userData['subtotal'], 2) }}</td>
                        <td>${{ number_format($userData['commission'], 2) }}</td>
                        <td><strong>${{ number_format($userData['total'], 2) }}</strong></td>
                        <td>
                            @if($userData['user'] && $userData['user']->stripe_customer_id)
                                <span class="badge bg-success">{{ $userData['user']->stripe_customer_id }}</span>
                            @else
                                <span class="badge bg-warning">Missing</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <th colspan="3">TOTALS</th>
                        <th>${{ number_format(collect($previewData['user_totals'])->sum('subtotal'), 2) }}</th>
                        <th>${{ number_format(collect($previewData['user_totals'])->sum('commission'), 2) }}</th>
                        <th><strong>${{ number_format(collect($previewData['user_totals'])->sum('total'), 2) }}</strong></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($previewData['point_entries']->count() > 0)
        <div class="mt-3">
            <a href="#previewEntriesDetail" class="btn btn-sm btn-outline-secondary" id="togglePreviewEntries" role="button" aria-expanded="false" aria-controls="previewEntriesDetail" data-entry-count="{{ $previewData['point_entries']->count() }}">
                Show Detailed Entry List ({{ $previewData['point_entries']->count() }} entries)
            </a>
            <div class="collapse mt-2" id="previewEntriesDetail">
                <div class="card card-body">
                    <h6>All Point Entries That Will Be Processed:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Commission</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($previewData['point_entries'] as $entry)
                                <tr>
                                    <td>{{ $entry->id }}</td>
                                    <td>{{ $entry->user->name ?? ('User #' . $entry->user_id) }}</td>
                                    <td>${{ number_format($entry->amount, 2) }}</td>
                                    <td>${{ number_format($entry->system_fee ?? 0, 2) }}</td>
                                    <td>{{ Str::limit($entry->description, 50) }}</td>
                                    <td>{{ $entry->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Toggle collapse functionality
            document.addEventListener('DOMContentLoaded', function() {
                const toggleBtn = document.getElementById('togglePreviewEntries');
                const collapseDiv = document.getElementById('previewEntriesDetail');
                
                if (toggleBtn && collapseDiv) {
                    toggleBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const entryCount = this.getAttribute('data-entry-count');
                        const isExpanded = collapseDiv.classList.contains('show');
                        
                        if (isExpanded) {
                            collapseDiv.classList.remove('show');
                            this.setAttribute('aria-expanded', 'false');
                            this.textContent = 'Show Detailed Entry List (' + entryCount + ' entries)';
                        } else {
                            collapseDiv.classList.add('show');
                            this.setAttribute('aria-expanded', 'true');
                            this.textContent = 'Hide Detailed Entry List';
                        }
                    });
                }
            });
        </script>
        @endif
        @else
        <div class="alert alert-info mb-0">
            <strong>No entries found</strong> - There are no uninvoiced rental entries in the date range {{ $previewData['range_from']->format('Y-m-d') }} to {{ $previewData['range_to']->format('Y-m-d') }}.
        </div>
        @endif
    </div>
</div>
@elseif($isParentSchedule && $schedule->is_active && $schedule->status === 'active')
<div class="card mt-4">
    <div class="card-body">
        <p class="text-muted mb-0">
            <strong>No preview available</strong> - All entries in the date range have already been processed by the cron. 
            The preview will appear again when new uninvoiced entries are available for the next run.
        </p>
    </div>
</div>
@endif

{{-- For Completed Schedules: Show Details (both parent and run schedules) --}}
@if(in_array($schedule->status, ['completed', 'failed']) && $schedule->items->count() > 0)
<div class="card mt-4 border-success">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">✅ Completed Results Table: Invoices Already Created & Sent</h5>
        <p class="mb-0 mt-1 small"><strong>What is this Results Table?</strong> This shows invoices that were <strong>already created and sent to Stripe</strong>. These entries are already invoiced and won't appear in the Preview table above.</p>
    </div>
    <div class="card-body">
        <div class="alert alert-success mb-3">
            <small>
                <strong>Key Difference:</strong><br>
                • <strong>Preview Table (above):</strong> Shows uninvoiced entries that WILL be processed<br>
                • <strong>Results Table (this one):</strong> Shows invoices that WERE already processed and sent to Stripe<br>
                • Once an entry is invoiced, it moves from Preview to Results and won't appear in Preview again
            </small>
        </div>
        @if($schedule->items->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Rental</th>
                        <th>Commission (→ platform)</th>
                        <th>Amount charged</th>
                        <th>Stripe Invoice ID</th>
                        <th>Status</th>
                        <th>Error</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedule->items as $item)
                    @php
                        $userEntries = isset($entriesByUser[$item->user_id]) ? $entriesByUser[$item->user_id] : collect();
                        $entryCount = $userEntries->count();
                    @endphp
                    <tr>
                        <td>
                            {{ $item->user->name ?? ('User #' . $item->user_id) }}
                            @if($entryCount > 1)
                                <br><small class="text-muted">({{ $entryCount }} entries summed)</small>
                            @endif
                        </td>
                        <td>{{ $item->user->email ?? 'N/A' }}</td>
                        <td>${{ number_format($item->subtotal_amount, 2) }}</td>
                        <td>${{ number_format($item->commission_amount, 2) }}</td>
                        <td>${{ number_format($item->total_amount, 2) }}</td>
                        <td>{{ $item->stripe_invoice_id ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status === 'completed' ? 'success' : ($item->status === 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>{{ $item->error ?? '-' }}</td>
                        <td>
                            @if($entryCount > 0)
                                <a href="#userEntries{{ $schedule->id }}_{{ $item->user_id }}" class="btn btn-sm btn-outline-info" id="toggleUserEntries{{ $schedule->id }}_{{ $item->user_id }}" role="button">
                                    View ({{ $entryCount }})
                                </a>
                            @endif
                        </td>
                    </tr>
                    @if($entryCount > 0)
                    <tr>
                        <td colspan="9" class="p-0 border-0">
                            <div class="collapse" id="userEntries{{ $schedule->id }}_{{ $item->user_id }}">
                                <div class="card card-body bg-light m-2">
                                    <h6 class="mb-2">Detailed Entries for {{ $item->user->name ?? ('User #' . $item->user_id) }}</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Entry ID</th>
                                                    <th>Amount</th>
                                                    <th>Commission</th>
                                                    <th>Description</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($userEntries as $entry)
                                                <tr>
                                                    <td>{{ $entry->id }}</td>
                                                    <td>${{ number_format($entry->amount, 2) }}</td>
                                                    <td>${{ number_format($entry->system_fee ?? 0, 2) }}</td>
                                                    <td>{{ Str::limit($entry->description, 60) }}</td>
                                                    <td>{{ $entry->created_at->format('Y-m-d H:i:s') }}</td>
                                                </tr>
                                                @endforeach
                                                <tr class="table-info">
                                                    <th>TOTAL</th>
                                                    <th>${{ number_format($userEntries->sum('amount'), 2) }}</th>
                                                    <th>${{ number_format($userEntries->sum('system_fee'), 2) }}</th>
                                                    <th><strong>${{ number_format($userEntries->sum('amount') - $userEntries->sum('system_fee'), 2) }}</strong><br><small class="text-muted">(Charged: Rental - Commission)</small></th>
                                                    <th></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-muted">No invoices were created in this run.</p>
        @endif
    </div>
</div>
@endif

{{-- For Parent Schedules: Show Run Schedules (if any) --}}
@if($isParentSchedule && isset($runSchedules) && $runSchedules->count() > 0)
<div class="card mt-4">
    <div class="card-body">
        <h5 class="mb-3">All Run Schedules ({{ $runSchedules->count() }} total)</h5>
        <p class="text-muted small mb-3">Each run creates a separate schedule entry. Click on a run schedule to view its details.</p>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Run Schedule ID</th>
                        <th>Run At</th>
                        <th>Status</th>
                        <th>Users Invoiced</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($runSchedules as $runSchedule)
                    <tr>
                        <td>#{{ $runSchedule->id }}</td>
                        <td>{{ $runSchedule->run_at ? $runSchedule->run_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $runSchedule->status === 'completed' ? 'success' : ($runSchedule->status === 'failed' ? 'danger' : ($runSchedule->status === 'running' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($runSchedule->status) }}
                            </span>
                        </td>
                        <td>{{ $runSchedule->items->count() ?? 0 }}</td>
                        <td>
                            <a href="{{ route('stripe-invoice-scheduler.show', ['id' => $runSchedule->id]) }}" class="btn btn-sm btn-primary">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
{{-- Old logs section removed - each run now creates a separate schedule entry --}}

<script>
    // Handle collapse for user entry details
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#userEntries"]').forEach(function(toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const collapseDiv = document.getElementById(targetId);
                
                if (collapseDiv) {
                    const isExpanded = collapseDiv.classList.contains('show');
                    const originalText = this.textContent;
                    
                    if (isExpanded) {
                        collapseDiv.classList.remove('show');
                        this.setAttribute('aria-expanded', 'false');
                        this.textContent = originalText.replace('Hide', 'View');
                    } else {
                        collapseDiv.classList.add('show');
                        this.setAttribute('aria-expanded', 'true');
                        this.textContent = originalText.replace('View', 'Hide');
                    }
                }
            });
        });
    });
</script>
