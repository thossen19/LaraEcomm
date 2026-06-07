@extends('dashboard.admin')

@section('title', 'Users Report')

@section('content')
<div class="content-card">
    <h2>Users Report</h2>
    
    <!-- User Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Users</h3>
            <div class="number">{{ $userStats['total'] }}</div>
            <div class="change">{{ $userStats['active'] }} active</div>
        </div>
        <div class="stat-card">
            <h3>Admin Users</h3>
            <div class="number">{{ $userStats['admin'] }}</div>
            <div class="change">System administrators</div>
        </div>
        <div class="stat-card">
            <h3>Sellers</h3>
            <div class="number">{{ $userStats['seller'] }}</div>
            <div class="change">Shop owners</div>
        </div>
        <div class="stat-card">
            <h3>Customers</h3>
            <div class="number">{{ $userStats['customer'] }}</div>
            <div class="change">{{ $userStats['inactive'] }} inactive</div>
        </div>
    </div>

    <!-- Back to Reports -->
    <div style="margin: 20px 0;">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>

    <!-- Users Table -->
    <div class="content-card">
        <h3>All Users</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <th style="padding: 10px; text-align: left;">ID</th>
                        <th style="padding: 10px; text-align: left;">Name</th>
                        <th style="padding: 10px; text-align: left;">Email</th>
                        <th style="padding: 10px; text-align: left;">Roles</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                        <th style="padding: 10px; text-align: left;">Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 10px;">#{{ $user->id }}</td>
                            <td style="padding: 10px;">
                                <div style="font-weight: 500;">{{ $user->name }}</div>
                            </td>
                            <td style="padding: 10px;">{{ $user->email }}</td>
                            <td style="padding: 10px;">
                                @if($user->roles && $user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span style="background: {{ $role->name == 'admin' ? '#e74c3c' : ($role->name == 'seller' ? '#3498db' : '#27ae60') }}; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-right: 2px;">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span style="color: #7f8c8d; font-size: 11px;">No roles</span>
                                @endif
                            </td>
                            <td style="padding: 10px;">
                                @if($user->is_active)
                                    <span style="background: #27ae60; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        Active
                                    </span>
                                @else
                                    <span style="background: #7f8c8d; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 10px;">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center; color: #7f8c8d;">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
            <div style="margin-top: 20px; text-align: center;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
