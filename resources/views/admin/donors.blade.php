@extends('layout.app')

@section('title', 'Manage Donors')

@section('content')
<div class="admin-page-container">
    <div class="page-header">
        <h1 class="page-title">Manage Donors</h1>
        <p class="page-subtitle">View and manage all registered blood donors</p>
    </div>

    <div class="data-table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Blood Type</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Can Donate</th>
                        <th>Last Donation</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donors as $donor)
                        <tr data-testid="donor-{{ $donor->id }}">
                            <td>#{{ $donor->id }}</td>
                            <td>
                                <div class="user-cell">
                                    <i class="fas fa-user-circle"></i>
                                    <div>
                                        <strong>{{ $donor->user->name }}</strong>
                                        <small>{{ $donor->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="blood-badge blood-{{ str_replace(['+', '-'], ['pos', 'neg'], $donor->blood_type) }}">
                                    {{ $donor->blood_type }}
                                </span>
                            </td>
                            <td>{{ $donor->phone }}</td>
                            <td>
                                <div class="location-cell">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ Str::limit($donor->address, 30) }}
                                </div>
                            </td>
                            <td>
                                <span class="availability-badge {{ $donor->is_available ? 'available' : 'unavailable' }}">
                                    {{ $donor->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td>
                                @if($donor->canDonate())
                                    <span class="can-donate-badge yes"><i class="fas fa-check-circle"></i> Yes</span>
                                @else
                                    <span class="can-donate-badge no"><i class="fas fa-times-circle"></i> No</span>
                                @endif
                            </td>
                            <td>{{ $donor->last_donation_date?->format('M d, Y') ?? 'Never' }}</td>
                            <td>{{ $donor->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-table-cell">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <p>No donors found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($donors->hasPages())
            <div class="pagination">
                {{ $donors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection