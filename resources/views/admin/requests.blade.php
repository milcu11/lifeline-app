@extends('layout.app')

@section('title', 'Manage Requests')

@section('content')
<div class="admin-page-container">
    <div class="page-header">
        <h1 class="page-title">Manage Blood Requests</h1>
        <p class="page-subtitle">View and monitor all blood donation requests</p>
    </div>

    <div class="data-table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hospital</th>
                        <th>Blood Type</th>
                        <th>Quantity</th>
                        <th>Urgency</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Needed By</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr data-testid="request-{{ $request->id }}">
                            <td>#{{ $request->id }}</td>
                            <td>
                                <div class="hospital-cell">
                                    <i class="fas fa-hospital"></i>
                                    {{ $request->hospital->name }}
                                </div>
                            </td>
                            <td>
                                <span class="blood-badge blood-{{ str_replace(['+', '-'], ['pos', 'neg'], $request->blood_type) }}">
                                    {{ $request->blood_type }}
                                </span>
                            </td>
                            <td>{{ $request->quantity }} units</td>
                            <td>
                                <span class="urgency-badge urgency-{{ $request->urgency_level }}">
                                    {{ ucfirst($request->urgency_level) }}
                                </span>
                            </td>
                            <td>
                                <div class="location-cell">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ Str::limit($request->location, 30) }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $request->status }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->needed_by ? \Carbon\Carbon::parse($request->needed_by)->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-table-cell">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No requests found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="pagination">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection