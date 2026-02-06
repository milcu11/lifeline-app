@extends('layout.app')

@section('title', 'Manage Users')

@section('content')
<div class="admin-page-container">
    <div class="page-header">
        <h1 class="page-title">Manage Users</h1>
        <p class="page-subtitle">View and manage all system users</p>
    </div>

    <div class="data-table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr data-testid="user-{{ $user->id }}">
                            <td>#{{ $user->id }}</td>
                            <td>
                                <div class="user-cell">
                                    <i class="fas fa-user-circle"></i>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge role-{{ $user->role }}">
                                    <i class="fas fa-{{ $user->role == 'admin' ? 'user-shield' : ($user->role == 'hospital' ? 'hospital' : 'user') }}"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="user-status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-icon {{ $user->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}" 
                                                data-testid="toggle-user-{{ $user->id }}">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-table-cell">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <p>No users found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="pagination">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection