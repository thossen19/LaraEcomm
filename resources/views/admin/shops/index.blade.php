@extends('dashboard.admin')

@section('title', 'Shops Management')

@section('content')
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Shops Management</h2>
        <a href="{{ route('admin.shops.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Shop
        </a>
    </div>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: white;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; font-weight: 600;">ID</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600;">Name</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600;">Owner</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600;">Email</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600;">Phone</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600;">Status</th>
                    <th style="padding: 12px; text-align: center; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shops as $shop)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">{{ $shop->id }}</td>
                        <td style="padding: 12px; font-weight: 500;">{{ $shop->name }}</td>
                        <td style="padding: 12px;">
                            {{ $shop->owner ? $shop->owner->name : 'N/A' }}
                            <br>
                            <small style="color: #7f8c8d;">{{ $shop->owner ? $shop->owner->email : '' }}</small>
                        </td>
                        <td style="padding: 12px;">{{ $shop->email }}</td>
                        <td style="padding: 12px;">{{ $shop->phone ?? 'N/A' }}</td>
                        <td style="padding: 12px;">
                            @if($shop->is_active)
                                <span style="background: #27ae60; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                    Active
                                </span>
                            @else
                                <span style="background: #e74c3c; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.shops.edit', $shop) }}" 
                                   style="background: #3498db; color: white; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" 
                                            style="background: {{ $shop->is_active ? '#f39c12' : '#27ae60' }}; color: white; padding: 6px 10px; border-radius: 4px; border: none; cursor: pointer; font-size: 12px;"
                                            onclick="return confirm('Are you sure you want to {{ $shop->is_active ? 'deactivate' : 'activate' }} this shop?')">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.shops.destroy', $shop) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="background: #e74c3c; color: white; padding: 6px 10px; border-radius: 4px; border: none; cursor: pointer; font-size: 12px;"
                                            onclick="return confirm('Are you sure you want to delete this shop?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            No shops found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $shops->links() }}
    </div>
</div>
@endsection
