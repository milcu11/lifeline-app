@extends('layout.app')

@section('title', 'Manage Donations')

@section('content')
<div class="admin-page-container">
    <div class="page-header">
        <h1 class="page-title">Manage Donations</h1>
        <p class="page-subtitle">View all completed and pending blood donations</p>
    </div>

    <div class="data-table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Donor</th>
                        <th>Blood Type</th>
                        <th>Quantity</th>
                        <th>Request ID</th>
                        <th>Donation Date</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                        <tr data-testid="donation-{{ $donation->id }}">
                            <td>#{{ $donation->id }}</td>
                            <td>
                                <div class="user-cell">
                                    <i class="fas fa-user-circle"></i>
                                    <div>
                                        <strong>{{ $donation->donor->user->name }}</strong>
                                        <small>{{ $donation->donor->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="blood-badge blood-{{ str_replace(['+', '-'], ['pos', 'neg'], $donation->donor->blood_type) }}">
                                    {{ $donation->donor->blood_type }}
                                </span>
                            </td>
                            <td>{{ $donation->request->quantity }} units</td>
                            <td>
                                <a href="#" class="link-primary">#{{ $donation->request_id }}</a>
                            </td>
                            <td>{{ $donation->donation_date?->format('M d, Y') ?? 'Not set' }}</td>
                            <td>
                                <span class="status-badge status-{{ $donation->status }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td>{{ $donation->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-table-cell">
                                <div class="empty-state">
                                    <i class="fas fa-heart"></i>
                                    <p>No donations found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($donations->hasPages())
            <div class="pagination">
                {{ $donations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection