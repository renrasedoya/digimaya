<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $project->name }}
                    @php
                        $statusColors = [
                            'active'    => 'bg-green-100 text-green-800',
                            'paused'    => 'bg-yellow-100 text-yellow-800',
                            'completed' => 'bg-gray-100 text-gray-800',
                        ];
                    @endphp
                    <span class="ml-2 inline-flex px-2 py-1 text-xs rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $project->status_label }}
                    </span>
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Projects', 'url' => route('admin.projects.index')],
                        ['label' => $project->name]
                    ]" />
                </div>
            </div>
            @php
                $canEdit = in_array(auth()->user()->role, [\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ADMIN])
                    || (auth()->user()->isAccountManager() && ($project->client->account_manager_id ?? null) === auth()->id());
                $canDelete = auth()->user()->role === \App\Models\User::ROLE_SUPER_ADMIN;
            @endphp
            @if($canEdit || $canDelete)
                <div class="flex items-center gap-2">
                    @if($canEdit)
                        <a href="{{ route('admin.projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Edit
                        </a>
                    @endif
                    @if($canDelete)
                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="inline" onsubmit="return confirm('Delete this project? Reports linked to this project will also be lost.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT COLUMN: Info --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">

                            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Project Info</h3>

                            <dl class="grid grid-cols-1 gap-4 mb-8">
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $project->name }}</dd>
                                </div>
                                @if($project->account_url)
                                    <div>
                                        <dt class="text-xs uppercase text-gray-500">Account URL</dt>
                                        <dd class="mt-1 text-sm">
                                            <a href="{{ $project->account_url }}" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-900 break-all">
                                                {{ $project->account_url }} ↗
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $project->status_label }}</dd>
                                </div>
                            </dl>

                            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Client Info</h3>

                            <dl class="grid grid-cols-1 gap-4 mb-8">
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Client</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($project->client)
                                            <a href="{{ route('admin.clients.show', $project->client) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $project->client->business_name }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Client not found</span>
                                        @endif
                                    </dd>
                                </div>
                                @if($project->client && $project->client->industry)
                                    <div>
                                        <dt class="text-xs uppercase text-gray-500">Industry</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $project->client->industry }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Account Manager</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($project->client && $project->client->accountManager)
                                            {{ $project->client->accountManager->name }}
                                        @else
                                            <span class="text-gray-400 italic">Unassigned</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>

                            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Assignment</h3>

                            <dl class="grid grid-cols-1 gap-4 mb-8">
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Advertiser</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $project->advertiser->name ?? '-' }}
                                        @if($project->advertiser)
                                            <span class="ml-1 inline-flex px-2 py-0.5 text-xs rounded-full bg-cyan-100 text-cyan-800">{{ $project->advertiser->role_label }}</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>

                            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Lifecycle</h3>

                            <dl class="grid grid-cols-1 gap-4">
                                @if($project->started_at)
                                    <div>
                                        <dt class="text-xs uppercase text-gray-500">Started At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $project->started_at->format('d M Y') }}</dd>
                                    </div>
                                @endif
                                @if($project->ended_at)
                                    <div>
                                        <dt class="text-xs uppercase text-gray-500">Ended At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $project->ended_at->format('d M Y') }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $project->created_at->format('d M Y, H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $project->updated_at->format('d M Y, H:i') }}</dd>
                                </div>
                            </dl>

                            @if($project->notes)
                                <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Internal Notes</h3>
                                <div class="text-sm text-gray-900 whitespace-pre-line">{{ $project->notes }}</div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Reports --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">

                            @php
                                $canSubmit = auth()->user()->isAdvertiser()
                                    && $project->advertiser_id === auth()->id()
                                    && $project->isActive();
                                $canReview = in_array(auth()->user()->role, [\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ADMIN])
                                    || (auth()->user()->isAccountManager() && ($project->client->account_manager_id ?? null) === auth()->id());
                                $healthColors = [
                                    'healthy'         => 'bg-green-100 text-green-800',
                                    'needs_attention' => 'bg-yellow-100 text-yellow-800',
                                    'critical'        => 'bg-red-100 text-red-800',
                                ];
                                $statusColorsR = [
                                    'open'        => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'resolved'    => 'bg-green-100 text-green-800',
                                ];
                                // Build category->subs map for inline form Alpine
                                $categorySubMap = $issueCategories->mapWithKeys(function ($cat) {
                                    return [(int) $cat->id => $cat->activeSubCategories->map(fn ($s) => [
                                        'id' => (int) $s->id,
                                        'name' => $s->name,
                                    ])->values()->toArray()];
                                })->toArray();
                            @endphp

                            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Project Reports</h3>

                            {{-- Submit Report Inline Form (Advertiser only) --}}
                            @if($canSubmit)
                                <div x-data="{
                                        open: false,
                                        health: 'healthy',
                                        categoryId: '',
                                        subCategoryId: '',
                                        categorySubMap: @js($categorySubMap),
                                        get isIssueRequired() { return this.health !== 'healthy'; },
                                        get availableSubCategories() {
                                            if (!this.categoryId) return [];
                                            return this.categorySubMap[this.categoryId] || [];
                                        },
                                        onCategoryChange() {
                                            const allowed = this.availableSubCategories.map(s => s.id);
                                            if (this.subCategoryId && !allowed.includes(parseInt(this.subCategoryId))) {
                                                this.subCategoryId = '';
                                            }
                                        }
                                     }" class="mb-6">
                                    <button type="button" @click="open = !open" class="text-sm text-indigo-600 hover:text-indigo-900 mb-2">
                                        <span x-show="!open">+ Submit Report</span>
                                        <span x-show="open" x-cloak>− Cancel</span>
                                    </button>

                                    <div x-show="open" x-cloak class="border border-gray-200 rounded-md p-4 bg-gray-50">
                                        <form method="POST" action="{{ route('admin.projects.reports.store', $project) }}">
                                            @csrf

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                                <div>
                                                    <x-input-label for="period_start" value="Period Start *" />
                                                    <x-text-input id="period_start" name="period_start" type="date" class="mt-1 block w-full text-sm" :value="old('period_start', now()->subDays(7)->format('Y-m-d'))" required />
                                                    <p class="mt-1 text-xs text-gray-500">Periode kondisi yang dilaporkan (bukan billing period). Pilih rentang tanggal yang merepresentasikan kondisi project yang ingin di-report.</p>
                                                    <x-input-error :messages="$errors->get('period_start')" class="mt-2" />
                                                </div>
                                                <div>
                                                    <x-input-label for="period_end" value="Period End *" />
                                                    <x-text-input id="period_end" name="period_end" type="date" class="mt-1 block w-full text-sm" :value="old('period_end', now()->format('Y-m-d'))" required />
                                                    <x-input-error :messages="$errors->get('period_end')" class="mt-2" />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <x-input-label for="health" value="Health *" />
                                                <select id="health" name="health" x-model="health" required class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                                    @foreach(\App\Models\ProjectReport::HEALTHS as $key => $label)
                                                        <option value="{{ $key }}" {{ old('health', 'healthy') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="mt-1.5 text-xs text-gray-500 space-y-0.5">
                                                    <div><span class="font-medium text-green-700">Healthy:</span> Project running smooth, KPI on track, no major issue.</div>
                                                    <div><span class="font-medium text-yellow-700">Needs Attention:</span> Ada masalah ringan-medium yang perlu di-monitor (e.g. performance turun, complaint, delay).</div>
                                                    <div><span class="font-medium text-red-700">Critical:</span> Bermasalah berat, butuh action segera (e.g. risk churn, miss target signifikan).</div>
                                                </div>
                                                <x-input-error :messages="$errors->get('health')" class="mt-2" />
                                            </div>

                                            <div x-show="isIssueRequired" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                                <div>
                                                    <x-input-label for="issue_category_id" value="Category *" />
                                                    <select id="issue_category_id" name="issue_category_id" x-model="categoryId" @change="onCategoryChange()" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                                        <option value="">-- Select Category --</option>
                                                        @foreach($issueCategories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('issue_category_id')" class="mt-2" />
                                                </div>
                                                <div>
                                                    <x-input-label for="issue_sub_category_id" value="Sub-category *" />
                                                    <select id="issue_sub_category_id" name="issue_sub_category_id" x-model="subCategoryId" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                                        <option value="">-- Select Sub-category --</option>
                                                        <template x-for="sub in availableSubCategories" :key="sub.id">
                                                            <option :value="String(sub.id)" x-text="sub.name"></option>
                                                        </template>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('issue_sub_category_id')" class="mt-2" />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <x-input-label for="summary" value="Summary *" />
                                                <textarea id="summary" name="summary" rows="4" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2" placeholder="Narasi singkat performance project di periode ini.">{{ old('summary') }}</textarea>
                                                <div class="mt-1.5 text-xs text-gray-500 space-y-1">
                                                    <p>Jelaskan <span class="font-medium">konteks</span> + <span class="font-medium">insight</span> + <span class="font-medium">next step</span>.</p>
                                                    <p class="text-gray-400"><span class="text-red-600 font-medium">Hindari:</span> "Health needs attention karena CPL naik." (re-state field di atas)</p>
                                                    <p class="text-gray-400"><span class="text-green-700 font-medium">Yang baik:</span> "CPL naik 35% minggu ini karena kompetitor menurunkan bid agresif di keyword utama. Sudah disesuaikan match type ke phrase + tambah negatif keyword 'gratis'. Akan monitor 3 hari ke depan."</p>
                                                </div>
                                                <x-input-error :messages="$errors->get('summary')" class="mt-2" />
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <x-primary-button>Submit Report</x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            @if(request('report_id'))
                                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md flex items-center justify-between">
                                    <span class="text-sm text-blue-800">
                                        Showing a specific report only. Other reports are hidden.
                                    </span>
                                    <a href="{{ route('admin.projects.show', $project) }}" class="text-sm text-blue-700 hover:text-blue-900 font-medium">
                                        Show all reports →
                                    </a>
                                </div>
                            @endif

                            {{-- Filter Bar --}}
                            <form method="GET" action="{{ route('admin.projects.show', $project) }}" class="mb-6 flex flex-wrap gap-2">
                                <select name="month" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                    <option value="0" {{ ($month ?? 0) == 0 ? 'selected' : '' }}>All Months</option>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ ($month ?? 0) == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                                    @endforeach
                                </select>
                                <select name="year" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                                        <option value="{{ $y }}" {{ ($year ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                <select name="health" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                    <option value="">All Health</option>
                                    @foreach(\App\Models\ProjectReport::HEALTHS as $key => $label)
                                        <option value="{{ $key }}" {{ request('health') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <select name="report_status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                    <option value="" {{ !request('report_status') ? 'selected' : '' }}>All Statuses ({{ $reportStatusCounts['total'] }})</option>
                                    @foreach(\App\Models\ProjectReport::STATUSES as $key => $label)
                                        <option value="{{ $key }}" {{ request('report_status') === $key ? 'selected' : '' }}>{{ $label }} ({{ $reportStatusCounts[$key] ?? 0 }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                                <a href="{{ route('admin.projects.show', $project) }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                            </form>

                            {{-- Reports List (minimalis, click expand) --}}
                            @if($reports->isEmpty())
                                <div class="text-center py-12 text-gray-500 text-sm">
                                    No reports match the current filter.
                                </div>
                            @else
                                <div class="space-y-2">
                                    @foreach($reports as $report)
                                        <div id="report-{{ $report->id }}" class="border border-gray-200 rounded-md text-sm" x-data="{ expanded: window.location.hash === '#report-{{ $report->id }}' }">
                                            {{-- Card Header (always visible, click to toggle) --}}
                                            <div class="p-3 cursor-pointer hover:bg-gray-50 flex items-center justify-between gap-2" @click="expanded = !expanded">
                                                <div class="flex items-center gap-2 flex-wrap min-w-0 flex-1">
                                                    <span class="font-medium text-gray-900 whitespace-nowrap">{{ $report->period_label }}</span>
                                                    <span class="inline-flex px-2 py-0.5 text-xs rounded-full {{ $healthColors[$report->health] ?? 'bg-gray-100 text-gray-800' }}">{{ $report->health_label }}</span>
                                                    <span class="inline-flex px-2 py-0.5 text-xs rounded-full {{ $statusColorsR[$report->status] ?? 'bg-gray-100 text-gray-800' }}">{{ $report->status_label }}</span>
                                                    <span class="text-xs text-gray-500">by {{ $report->submitter->name ?? 'Unknown' }}</span>
                                                </div>
                                                <span class="text-gray-400 text-xs flex-shrink-0">
                                                    <span x-show="!expanded">▼</span>
                                                    <span x-show="expanded" x-cloak>▲</span>
                                                </span>
                                            </div>

                                            {{-- Expanded content --}}
                                            <div x-show="expanded" x-cloak class="border-t border-gray-200 p-3 bg-gray-50/50">
                                                @if($report->issueCategory)
                                                    <div class="text-xs text-gray-600 mb-2">
                                                        <span class="font-medium">{{ $report->issueCategory->name }}</span>
                                                        @if($report->issueSubCategory)
                                                            <span class="text-gray-400">→ {{ $report->issueSubCategory->name }}</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="text-xs font-semibold text-gray-700 uppercase mb-1">Summary</div>
                                                <div class="text-gray-700 whitespace-pre-line mb-3">{{ $report->summary }}</div>

                                                @if($report->am_feedback)
                                                    <div class="p-3 bg-indigo-50 border border-indigo-200 rounded mb-3">
                                                        <div class="text-xs font-semibold text-indigo-900 uppercase mb-1">AM Feedback</div>
                                                        <div class="text-indigo-800 whitespace-pre-line text-sm">{{ $report->am_feedback }}</div>
                                                        @if($report->reviewer)
                                                            <div class="text-xs text-indigo-600 mt-2">— {{ $report->reviewer->name }} · {{ $report->reviewed_at?->diffForHumans() }}</div>
                                                        @endif
                                                    </div>
                                                @endif

                                                {{-- Acknowledgment State (Phase 14.6) --}}
                                                @php
                                                    $isOwnReport = auth()->id() === (int) $report->submitted_by;
                                                    $needsAck = $report->isPendingAcknowledgment();
                                                @endphp

                                                @if($report->isAcknowledged())
                                                    <div class="mb-3 p-2 bg-green-50 border border-green-200 rounded text-xs text-green-800">
                                                        <span class="font-medium">✓ Acknowledged</span>
                                                        <span class="text-green-600 ml-1">on {{ $report->acknowledged_at->format('d M Y, H:i') }}</span>
                                                    </div>
                                                @elseif($needsAck && $isOwnReport)
                                                    <div class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded" x-data="{ showConfirm: false }">
                                                        <div class="text-sm text-yellow-900 mb-2">
                                                            <span class="font-medium">AM has reviewed this report.</span>
                                                            Please acknowledge after reading the feedback above.
                                                        </div>
                                                        <button type="button" @click.stop="showConfirm = true" x-show="!showConfirm"
                                                            class="inline-flex items-center px-3 py-1.5 border border-green-400 text-green-700 hover:bg-green-50 text-xs font-medium rounded">
                                                            Acknowledge
                                                        </button>
                                                        <div x-show="showConfirm" x-cloak class="mt-2 p-3 bg-white border border-yellow-300 rounded">
                                                            <div class="text-xs text-gray-700 mb-2">
                                                                Confirm acknowledge? Once acknowledged, this cannot be undone. Make sure you have read the AM feedback.
                                                            </div>
                                                            <form method="POST" action="{{ route('admin.project-reports.acknowledge', $report) }}" class="inline" @click.stop>
                                                                @csrf
                                                                <button type="submit" class="px-3 py-1 border border-green-400 text-green-700 hover:bg-green-50 text-xs font-medium rounded">
                                                                    Yes, Acknowledge
                                                                </button>
                                                                <button type="button" @click.stop="showConfirm = false" class="px-3 py-1 text-xs text-gray-600 hover:text-gray-800">
                                                                    Cancel
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @elseif($needsAck)
                                                    <div class="mb-3 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800">
                                                        ⏳ Pending advertiser acknowledgment
                                                    </div>
                                                @endif

                                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 text-xs">
                                                    @if($canReview)
                                                        <button type="button"
                                                            @click.stop="
                                                                $dispatch('open-modal', 'review-report');
                                                                $dispatch('load-review', {
                                                                    id: {{ $report->id }},
                                                                    status: '{{ $report->status }}',
                                                                    feedback: @js($report->am_feedback ?: '')
                                                                });
                                                            "
                                                            class="text-indigo-600 hover:text-indigo-900">
                                                            {{ $report->reviewer ? 'Update Review' : 'Review' }}
                                                        </button>
                                                    @endif
                                                    @if($report->canBeEditedBy(auth()->user()))
                                                        <button type="button"
                                                            @click.stop="
                                                                $dispatch('open-modal', 'edit-report');
                                                                $dispatch('load-edit-report', {
                                                                    id: {{ $report->id }},
                                                                    period_start: '{{ $report->period_start?->format('Y-m-d') }}',
                                                                    period_end: '{{ $report->period_end?->format('Y-m-d') }}',
                                                                    summary: @js($report->summary),
                                                                    health: '{{ $report->health }}',
                                                                    category_id: '{{ $report->issue_category_id ?? '' }}',
                                                                    sub_category_id: '{{ $report->issue_sub_category_id ?? '' }}'
                                                                });
                                                            "
                                                            class="text-indigo-600 hover:text-indigo-900">Edit</button>

                                                        <form method="POST" action="{{ route('admin.project-reports.destroy', $report) }}" class="inline" onsubmit="return confirm('Delete this report?')" @click.stop>
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-12">{{ $reports->links() }}</div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- Edit Report Modal (Advertiser/AM/admin)                       --}}
    {{-- ============================================================== --}}
    <div x-data="{
            reportId: null,
            period_start: '',
            period_end: '',
            summary: '',
            health: 'healthy',
            categoryId: '',
            subCategoryId: '',
            initialSubCategoryId: '',
            categorySubMap: @js($categorySubMap),
            get isIssueRequired() { return this.health !== 'healthy'; },
            get availableSubCategories() {
                if (!this.categoryId) return [];
                return this.categorySubMap[this.categoryId] || [];
            },
            onCategoryChange() {
                const allowed = this.availableSubCategories.map(s => s.id);
                if (this.subCategoryId && !allowed.includes(parseInt(this.subCategoryId))) {
                    this.subCategoryId = '';
                }
            }
         }"
         x-on:load-edit-report.window="
            reportId = $event.detail.id;
            period_start = $event.detail.period_start;
            period_end = $event.detail.period_end;
            summary = $event.detail.summary;
            health = $event.detail.health;
            categoryId = $event.detail.category_id;
            initialSubCategoryId = $event.detail.sub_category_id;
            subCategoryId = $event.detail.sub_category_id;
            $nextTick(() => {
                if (initialSubCategoryId) {
                    const sel = document.querySelector('#edit_sub_category_id');
                    if (sel) sel.value = initialSubCategoryId;
                    subCategoryId = initialSubCategoryId;
                }
            });
         ">
        <x-modal name="edit-report" maxWidth="2xl" focusable>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Report</h3>

                <form method="POST" :action="`{{ url('admin/project-reports') }}/${reportId}`">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input-label for="edit_period_start" value="Period Start *" />
                            <input type="date" id="edit_period_start" name="period_start" x-model="period_start" required class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                            <p class="mt-1 text-xs text-gray-500">Periode kondisi yang dilaporkan (bukan billing period). Pilih rentang tanggal yang merepresentasikan kondisi project yang ingin di-report.</p>
                        </div>
                        <div>
                            <x-input-label for="edit_period_end" value="Period End *" />
                            <input type="date" id="edit_period_end" name="period_end" x-model="period_end" required class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-input-label for="edit_health" value="Health *" />
                        <select id="edit_health" name="health" x-model="health" required class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                            @foreach(\App\Models\ProjectReport::HEALTHS as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="mt-1.5 text-xs text-gray-500 space-y-0.5">
                            <div><span class="font-medium text-green-700">Healthy:</span> Project running smooth, KPI on track, no major issue.</div>
                            <div><span class="font-medium text-yellow-700">Needs Attention:</span> Ada masalah ringan-medium yang perlu di-monitor (e.g. performance turun, complaint, delay).</div>
                            <div><span class="font-medium text-red-700">Critical:</span> Bermasalah berat, butuh action segera (e.g. risk churn, miss target signifikan).</div>
                        </div>
                    </div>

                    <div x-show="isIssueRequired" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input-label for="edit_category_id" value="Category *" />
                            <select id="edit_category_id" name="issue_category_id" x-model="categoryId" @change="onCategoryChange()" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                <option value="">-- Select Category --</option>
                                @foreach($issueCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="edit_sub_category_id" value="Sub-category *" />
                            <select id="edit_sub_category_id" name="issue_sub_category_id" x-model="subCategoryId" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                <option value="">-- Select Sub-category --</option>
                                <template x-for="sub in availableSubCategories" :key="sub.id">
                                    <option :value="String(sub.id)" x-text="sub.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-input-label for="edit_summary" value="Summary *" />
                        <textarea id="edit_summary" name="summary" x-model="summary" rows="4" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"></textarea>
                        <div class="mt-1.5 text-xs text-gray-500 space-y-1">
                            <p>Jelaskan <span class="font-medium">konteks</span> + <span class="font-medium">insight</span> + <span class="font-medium">next step</span>.</p>
                            <p class="text-gray-400"><span class="text-red-600 font-medium">Hindari:</span> "Health needs attention karena CPL naik." (re-state field di atas)</p>
                            <p class="text-gray-400"><span class="text-green-700 font-medium">Yang baik:</span> "CPL naik 35% minggu ini karena kompetitor menurunkan bid agresif di keyword utama. Sudah disesuaikan match type ke phrase + tambah negatif keyword 'gratis'. Akan monitor 3 hari ke depan."</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t">
                        <button type="button" x-on:click="$dispatch('close-modal', 'edit-report')" class="text-sm text-gray-600 hover:text-gray-900 px-4 py-2">Cancel</button>
                        <x-primary-button>Update Report</x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>

    {{-- ============================================================== --}}
    {{-- AM Review Modal (AM only)                                     --}}
    {{-- ============================================================== --}}
    @if($canReview)
        <div x-data="{
                reportId: null,
                status: 'open',
                feedback: ''
             }"
             x-on:load-review.window="
                reportId = $event.detail.id;
                status = $event.detail.status;
                feedback = $event.detail.feedback;
             ">
            <x-modal name="review-report" maxWidth="2xl" focusable>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Review Report</h3>
                    <p class="text-sm text-gray-600 mb-4">Set status workflow + tambahkan feedback untuk advertiser.</p>

                    <form method="POST" :action="`{{ url('admin/project-reports') }}/${reportId}/review`">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="review_status" value="Status *" />
                            <select id="review_status" name="status" x-model="status" required class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                @foreach(\App\Models\ProjectReport::STATUSES as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                <span class="font-medium text-blue-700">Open</span> · 
                                <span class="font-medium text-yellow-700">In Progress</span> · 
                                <span class="font-medium text-green-700">Resolved</span> (advertiser tidak bisa edit lagi)
                            </p>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="review_feedback" value="AM Feedback" />
                            <textarea id="review_feedback" name="am_feedback" x-model="feedback" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2" placeholder="Catatan untuk advertiser (opsional)."></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t">
                            <button type="button" x-on:click="$dispatch('close-modal', 'review-report')" class="text-sm text-gray-600 hover:text-gray-900 px-4 py-2">Cancel</button>
                            <x-primary-button>Save Review</x-primary-button>
                        </div>
                    </form>
                </div>
            </x-modal>
        </div>
    @endif
</x-app-layout>