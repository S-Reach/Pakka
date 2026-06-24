@extends('layout.master')

@section('title', 'Reported Content')

@section('content')

<link rel="stylesheet" href="{{ asset('css/reportedcontent.css') }}">
<script src="{{ asset('js/reportedcontent.js') }}" defer></script>

<!-- HEADER -->
<div class="page-header">
    <h1>Reported Content</h1>
    <p>Review and manage user reports</p>
</div>

<!-- STATS -->
<div class="stats">
    <div class="card"><p>Pending Reports</p><h3>{{ $pendingCount }}</h3></div>
    <div class="card"><p>Story Reports</p><h3>{{ $storyCount }}</h3></div>
    <div class="card"><p>Chapter Reports</p><h3>{{ $chapterCount }}</h3></div>
    <div class="card"><p>Comment Reports</p><h3>{{ $commentCount }}</h3></div>
</div>

<!-- FILTERS -->
<form method="GET" class="filters">

    <select name="language" onchange="this.form.submit()">
        <option value="">All Languages</option>
        <option value="english" {{ request('language')=='english'?'selected':'' }}>English</option>
        <option value="khmer" {{ request('language')=='khmer'?'selected':'' }}>Khmer</option>
    </select>

    <select name="type" onchange="this.form.submit()">
        <option value="">All Types</option>
        <option value="story">Story</option>
        <option value="chapter">Chapter</option>
        <option value="comment">Comment</option>
    </select>

    <select name="status" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="safe">Safe</option>
        <option value="actioned">Actioned</option>
    </select>

</form>

<!-- REPORT LIST -->
<div class="report-box">

@forelse($allReports as $report)

<div class="report-item">

    <div>

        <div class="report-title">
            <h3>{{ $report['title'] }}</h3>

            <span class="badge blue">{{ $report['type'] }}</span>

            <span class="badge gray">
                {{ ucfirst($report['language'] ?? 'N/A') }}
            </span>

            @if($report['is_new_report_after_safe'])
                <span class="badge red">NEW REPORT</span>
            @endif
            @if($report['status'] == 'pending')
                <small style="color:#f59e0b;">Under review</small>
            @elseif($report['status'] == 'safe')
                <small style="color:#16a34a;">No action needed</small>
            @else
                <small style="color:#6b7280;">Action taken</small>
            @endif

            <span class="badge orange">
                {{ $report['report_count'] }} reports
            </span>
        </div>

        <div class="meta">
            By {{ $report['author'] }} • 
            <span style="color:#ef4444">{{ $report['report_reason'] }}</span> • 
            Reported by {{ $report['reported_by_list']->join(', ') }} • 
            {{ \Carbon\Carbon::parse($report['created_at'])->format('Y-m-d H:i') }}
        </div>

        <div>{{ $report['details'] ?? '-' }}</div>

        <div class="preview">
            {{ \Illuminate\Support\Str::limit($report['preview'] ?? '', 120) }}
        </div>

    </div>

    <div class="actions">

        <button class="btn review" onclick="openModal(@js($report))">
            Review
        </button>

    </div>

</div>

@empty
<div style="padding:20px;text-align:center;color:#6b7280;">
    No reports found
</div>
@endforelse

</div>
<!--Pagination-->
<div class="simple-pagination">
    {{ $allReports->onEachSide(0)->links('pagination::bootstrap-5') }}
</div>

<!--Modal-->
<div id="reportModal" class="modal"> 
    <div class="modal-content"> 
        <div class="modal-header"> 
            <h2>Report Details</h2> 
            <span class="close" onclick="closeModal()">&times;</span> 
        </div> 

        <div class="detail-group"> 
            <div class="detail-label">Report ID:</div> 
            <div class="detail-value" id="m_id"></div> 
        </div> 

        <div class="detail-group"> 
            <div class="detail-label">Report Type:</div> 
            <div class="detail-value" id="m_type"></div> 
        </div> 

        <div class="detail-group"> 
            <div class="detail-label">Story Title:</div> 
            <div class="detail-value" id="m_title"></div> 
        </div> 
        
        <div class="detail-group"> 
            <div class="detail-label">Chapter Title:</div> 
            <div class="detail-value" id="m_chapter_title"></div> 
        </div> 

        <div class="detail-group"> 
            <div class="detail-label">Content Author:</div> 
            <div class="detail-value" id="m_author"></div> 
        </div> 
        
        <div class="detail-group"> 
            <div class="detail-label">Language:</div> 
            <div class="detail-value" id="m_language"></div> 
        </div> 

        <div class="detail-group">
            <div class="detail-label">Payment Status:</div>
            <div class="detail-value" id="m_payment"></div>
        </div>
        
        <div class="detail-group"> 
            <div class="detail-label">Reported By:</div> 
            <div class="detail-value" id="m_reported_by_list"></div> 
        </div> 
        
        <div class="detail-group"> 
            <div class="detail-label">Report Reason:</div> 
            <div class="detail-value reason" id="m_report_reason"></div> 
        </div> 
        
        <div class="detail-group"> 
            <div class="detail-label">Report Details:</div> 
            <div class="detail-value" id="m_details"></div> 
        </div> 
        
        <div class="detail-group"> 
            <div class="detail-label">Reported Date:</div> 
            <div class="detail-value" id="m_date"></div> 
        </div> 
        
        <div class="detail-group"> 
            <div class="detail-label">Content Preview:</div> 
            <div class="preview-box" id="m_preview"></div> 
        </div> 
        
        <div class="modal-actions"> 
            <button class="btn-close" onclick="closeModal()"> CLOSE </button> 
            <form id="safeForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="safe">
                <button type="submit" class="btn safe">Mark Safe</button>
            </form>
        
            <!-- REVISION (ONLY STORY + CHAPTER) -->
            <form id="notifyForm" method="POST">
                @csrf
                <button type="submit" class="btn review">
                    Send Revision
                </button>
            </form>

            <!-- HIDE -->
            <form id="hideForm" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn hide-btn">Hide</button>
            </form>

            <!-- UNHIDE -->
            <form id="unhideForm" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn unhide-btn">Unhide</button>
            </form>
        </div> 
    </div> 
</div>

@endsection