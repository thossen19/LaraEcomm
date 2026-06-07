@extends('dashboard.admin')

@section('title', 'System Settings')

@section('content')
<div class="content-card">
    <h2>System Settings</h2>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- General Settings -->
            <div>
                <h3 style="margin-bottom: 20px; color: #2c3e50;">General Settings</h3>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="site_name" style="display: block; margin-bottom: 5px; font-weight: 500;">Site Name</label>
                    <input type="text" id="site_name" name="site_name" value="{{ $settings['site_name'] }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="site_email" style="display: block; margin-bottom: 5px; font-weight: 500;">Site Email</label>
                    <input type="email" id="site_email" name="site_email" value="{{ $settings['site_email'] }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="site_phone" style="display: block; margin-bottom: 5px; font-weight: 500;">Site Phone</label>
                    <input type="tel" id="site_phone" name="site_phone" value="{{ $settings['site_phone'] }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="site_address" style="display: block; margin-bottom: 5px; font-weight: 500;">Site Address</label>
                    <textarea id="site_address" name="site_address" rows="3"
                              style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ $settings['site_address'] }}</textarea>
                </div>
            </div>
            
            <!-- System Settings -->
            <div>
                <h3 style="margin-bottom: 20px; color: #2c3e50;">System Settings</h3>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="currency" style="display: block; margin-bottom: 5px; font-weight: 500;">Currency</label>
                    <select id="currency" name="currency" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="USD" {{ $settings['currency'] == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ $settings['currency'] == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="GBP" {{ $settings['currency'] == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                        <option value="JPY" {{ $settings['currency'] == 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="timezone" style="display: block; margin-bottom: 5px; font-weight: 500;">Timezone</label>
                    <select id="timezone" name="timezone" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="America/New_York" {{ $settings['timezone'] == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                        <option value="America/Los_Angeles" {{ $settings['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles</option>
                        <option value="Europe/London" {{ $settings['timezone'] == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                        <option value="Asia/Tokyo" {{ $settings['timezone'] == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="default_user_role" style="display: block; margin-bottom: 5px; font-weight: 500;">Default User Role</label>
                    <select id="default_user_role" name="default_user_role" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="customer" {{ $settings['default_user_role'] == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="seller" {{ $settings['default_user_role'] == 'seller' ? 'selected' : '' }}>Seller</option>
                        <option value="admin" {{ $settings['default_user_role'] == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Feature Toggles -->
        <div style="margin-top: 30px;">
            <h3 style="margin-bottom: 20px; color: #2c3e50;">Feature Settings</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ $settings['maintenance_mode'] ? 'checked' : '' }}
                           style="margin-right: 8px;">
                    Maintenance Mode
                </label>
                
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="enable_registration" value="1" {{ $settings['enable_registration'] ? 'checked' : '' }}
                           style="margin-right: 8px;">
                    Enable Registration
                </label>
                
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="email_verification" value="1" {{ $settings['email_verification'] ? 'checked' : '' }}
                           style="margin-right: 8px;">
                    Email Verification
                </label>
            </div>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>
    </form>
</div>

<!-- Header and Footer Logo Settings -->
<div class="content-card" style="margin-top: 30px;">
    <h3>Header and Footer Logo Settings</h3>
    
    <form method="POST" action="{{ route('admin.settings.update.logo') }}" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Header Logo -->
            <div>
                <h4 style="margin-bottom: 15px; color: #2c3e50;">Header Logo</h4>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="header_logo" style="display: block; margin-bottom: 5px; font-weight: 500;">Upload Header Logo</label>
                    <input type="file" id="header_logo" name="header_logo" accept="image/*"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <small style="color: #7f8c8d; display: block; margin-top: 5px;">Recommended size: 200x80 pixels, formats: PNG, JPG, GIF</small>
                </div>
                
                @if($headerLogo)
                <div style="margin-top: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Current Header Logo</label>
                    <img src="{{ asset('storage/' . $headerLogo) }}" alt="Header Logo" style="max-width: 200px; height: auto; border-radius: 4px;">
                </div>
                @endif
            </div>
            
            <!-- Footer Logo -->
            <div>
                <h4 style="margin-bottom: 15px; color: #2c3e50;">Footer Logo</h4>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="footer_logo" style="display: block; margin-bottom: 5px; font-weight: 500;">Upload Footer Logo</label>
                    <input type="file" id="footer_logo" name="footer_logo" accept="image/*"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <small style="color: #7f8c8d; display: block; margin-top: 5px;">Recommended size: 200x80 pixels, formats: PNG, JPG, GIF</small>
                </div>
                
                @if($footerLogo)
                <div style="margin-top: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Current Footer Logo</label>
                    <img src="{{ asset('storage/' . $footerLogo) }}" alt="Footer Logo" style="max-width: 200px; height: auto; border-radius: 4px;">
                </div>
                @endif
            </div>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Logo Settings
            </button>
        </div>
    </form>
</div>

<!-- System Actions -->
<div class="content-card" style="margin-top: 30px;">
    <h3>System Actions</h3>
    <p style="color: #7f8c8d; margin-bottom: 20px;">Perform system maintenance tasks.</p>
    
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
        <form action="{{ route('admin.settings.backup') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary" style="background: #27ae60;"
                    onclick="return confirm('Are you sure you want to create a database backup?')">
                <i class="fas fa-download"></i> Create Backup
            </button>
        </form>
        
        <form action="{{ route('admin.settings.clear-cache') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary" style="background: #f39c12;"
                    onclick="return confirm('Are you sure you want to clear the application cache?')">
                <i class="fas fa-broom"></i> Clear Cache
            </button>
        </form>
    </div>
<!-- Homepage Banner Slider -->
<div class="content-card" style="margin-top: 30px;">
    <h3>Homepage Banner Slider</h3>
    <p style="color: #7f8c8d; margin-bottom: 20px;">Manage homepage banner slider images</p>
    
    <!-- Add New Banner -->
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h4 style="margin-bottom: 15px;">Add New Banner</h4>
        <form method="POST" action="{{ route('admin.settings.banners.store') }}" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Title</label>
                    <input type="text" name="title" placeholder="Banner title"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Link URL</label>
                    <input type="url" name="link_url" placeholder="https://example.com"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Description</label>
                    <textarea name="description" rows="2" placeholder="Banner description"
                              style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Banner Image *</label>
                    <input type="file" name="image" accept="image/*" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="grid-column: 1 / -1;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked style="margin-right: 8px;">
                        Active
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">
                <i class="fas fa-plus"></i> Add Banner
            </button>
        </form>
    </div>
    
    <!-- Existing Banners -->
    <div>
        <h4 style="margin-bottom: 15px;">Current Banners</h4>
        @if($bannerImages->count() > 0)
            <div id="banners-container" style="display: grid; gap: 15px;">
                @foreach($bannerImages as $banner)
                    <div class="banner-item" data-banner-id="{{ $banner->id }}" 
                         style="display: grid; grid-template-columns: 200px 1fr auto; gap: 15px; padding: 15px; background: white; border: 1px solid #ddd; border-radius: 8px; align-items: center;">
                        <div>
                            @if($banner->image_path)
                                <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title ?? 'Banner' }}"
                                     style="width: 100%; height: 80px; object-fit: cover; border-radius: 4px;"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/200x80/f8f9fa/7f8c8d?text=No+Image';">
                            @else
                                <div style="width: 100%; height: 80px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #7f8c8d;">
                                    No Image
                                </div>
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 600; margin-bottom: 5px;">{{ $banner->title ?? 'Untitled Banner' }}</div>
                            <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" target="_blank" style="color: #3498db;">{{ $banner->link_url }}</a>
                                @else
                                    No link
                                @endif
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <span style="background: {{ $banner->is_active ? '#27ae60' : '#e74c3c' }}; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                    {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span style="color: #7f8c8d; font-size: 12px;">Order: {{ $banner->sort_order }}</span>
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <button class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;"
                                    onclick="editBanner({{ $banner->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.settings.banners.delete', $banner) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px; background: #e74c3c;"
                                        onclick="return confirm('Are you sure you want to delete this banner?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; color: #7f8c8d;">
                <i class="fas fa-image" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <p>No banner images added yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
