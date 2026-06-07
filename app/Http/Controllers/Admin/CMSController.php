<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Banner;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    // Pages Management
    public function pages()
    {
        $pages = Page::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.cms.pages', compact('pages'));
    }

    public function createPage()
    {
        return view('admin.cms.pages.create');
    }

    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'is_homepage' => 'boolean'
        ]);

        Page::create($validated);

        return redirect()->route('admin.cms.pages.index')->with('success', 'Page created successfully.');
    }

    public function editPage(Page $page)
    {
        return view('admin.cms.pages.edit', compact('page'));
    }

    public function updatePage(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'is_homepage' => 'boolean'
        ]);

        $page->update($validated);

        return redirect()->route('admin.cms.pages.index')->with('success', 'Page updated successfully.');
    }

    public function deletePage(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.cms.pages.index')->with('success', 'Page deleted successfully.');
    }

    // Banners Management
    public function banners()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->paginate(20);
        return view('admin.cms.banners', compact('banners'));
    }

    public function createBanner()
    {
        return view('admin.cms.banners.create');
    }

    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'position' => 'required|in:homepage,main_banner,side_banner_1,side_banner_2,category,product',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($validated);

        return redirect()->route('admin.cms.banners.index')->with('success', 'Banner created successfully.');
    }

    public function editBanner(Banner $banner)
    {
        return view('admin.cms.banners.edit', compact('banner'));
    }

    public function updateBanner(Request $request, Banner $banner)
    {
        // DEBUG: Log incoming data
        \Log::info('Banner update request:', [
            'request_data' => $request->all(),
            'banner_id' => $banner->id,
            'current_banner_data' => $banner->toArray()
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'position' => 'required|in:homepage,main_banner,side_banner_1,side_banner_2,category,product',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        // DEBUG: Log validated data
        \Log::info('Banner validated data:', $validated);

        if ($request->hasFile('image')) {
            if ($banner->image) {
                \Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = $request->file('image')->store('banners', 'public');
            
            // DEBUG: Log image upload
            \Log::info('Banner image uploaded:', $validated['image']);
        }

        // DEBUG: Log before update
        \Log::info('Banner before update:', $banner->toArray());

        $banner->update($validated);

        // DEBUG: Log after update
        \Log::info('Banner after update:', $banner->fresh()->toArray());

        return redirect()->route('admin.cms.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function deleteBanner(Banner $banner)
    {
        if ($banner->image) {
            \Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.cms.banners.index')->with('success', 'Banner deleted successfully.');
    }

    public function toggleBannerStatus(Banner $banner)
    {
        // DEBUG: Log current status
        \Log::info('Banner toggle attempt:', [
            'banner_id' => $banner->id,
            'current_status' => $banner->status,
            'request_data' => request()->all()
        ]);

        $newStatus = $banner->status === 'active' ? 'inactive' : 'active';
        
        $banner->update([
            'status' => $newStatus
        ]);

        // DEBUG: Log after update
        \Log::info('Banner toggle completed:', [
            'banner_id' => $banner->id,
            'new_status' => $newStatus,
            'banner_after_update' => $banner->fresh()->toArray()
        ]);

        return back()->with('success', 'Banner status updated successfully.');
    }

    // Blog Management
    public function blog()
    {
        $posts = BlogPost::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.cms.blog', compact('posts'));
    }

    public function createPost()
    {
        return view('admin.cms.blog.create');
    }

    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $validated['author_id'] = auth()->id();

        BlogPost::create($validated);

        return redirect()->route('admin.cms.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function editPost(BlogPost $post)
    {
        return view('admin.cms.blog.edit', compact('post'));
    }

    public function updatePost(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $post->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $post->update($validated);

        return redirect()->route('admin.cms.blog.index')->with('success', 'Blog post updated successfully.');
    }

    public function deletePost(BlogPost $post)
    {
        if ($post->featured_image) {
            \Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.cms.blog.index')->with('success', 'Blog post deleted successfully.');
    }

    public function togglePostStatus(BlogPost $post)
    {
        $post->update([
            'status' => $post->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'Post status updated successfully.');
    }

    // About Page Management
    public function about()
    {
        $aboutContent = \App\Models\Setting::get('about_content', '');
        $aboutTitle = \App\Models\Setting::get('about_title', 'About Us');
        $aboutMetaTitle = \App\Models\Setting::get('about_meta_title', 'About Us - Our Company');
        $aboutMetaDescription = \App\Models\Setting::get('about_meta_description', 'Learn more about our company and our mission.');
        
        return view('admin.cms.about', compact('aboutContent', 'aboutTitle', 'aboutMetaTitle', 'aboutMetaDescription'));
    }

    public function updateAbout(Request $request)
    {
        $validated = $request->validate([
            'about_title' => 'required|string|max:255',
            'about_content' => 'required|string',
            'about_meta_title' => 'nullable|string|max:255',
            'about_meta_description' => 'nullable|string|max:500',
        ]);

        \App\Models\Setting::set('about_title', $validated['about_title']);
        \App\Models\Setting::set('about_content', $validated['about_content']);
        \App\Models\Setting::set('about_meta_title', $validated['about_meta_title']);
        \App\Models\Setting::set('about_meta_description', $validated['about_meta_description']);

        return back()->with('success', 'About page updated successfully.');
    }

    // Contact Page Management
    public function contact()
    {
        $contactTitle = \App\Models\Setting::get('contact_title', 'Contact Us');
        $contactContent = \App\Models\Setting::get('contact_content', 'Get in touch with us for any inquiries.');
        $contactEmail = \App\Models\Setting::get('contact_email', 'contact@example.com');
        $contactPhone = \App\Models\Setting::get('contact_phone', '+1234567890');
        $contactAddress = \App\Models\Setting::get('contact_address', '123 Main St, City, Country');
        $contactMetaTitle = \App\Models\Setting::get('contact_meta_title', 'Contact Us - Get in Touch');
        $contactMetaDescription = \App\Models\Setting::get('contact_meta_description', 'Contact us for any questions or support.');
        
        return view('admin.cms.contact', compact('contactTitle', 'contactContent', 'contactEmail', 'contactPhone', 'contactAddress', 'contactMetaTitle', 'contactMetaDescription'));
    }

    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'contact_title' => 'required|string|max:255',
            'contact_content' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'contact_address' => 'required|string|max:500',
            'contact_meta_title' => 'nullable|string|max:255',
            'contact_meta_description' => 'nullable|string|max:500',
        ]);

        \App\Models\Setting::set('contact_title', $validated['contact_title']);
        \App\Models\Setting::set('contact_content', $validated['contact_content']);
        \App\Models\Setting::set('contact_email', $validated['contact_email']);
        \App\Models\Setting::set('contact_phone', $validated['contact_phone']);
        \App\Models\Setting::set('contact_address', $validated['contact_address']);
        \App\Models\Setting::set('contact_meta_title', $validated['contact_meta_title']);
        \App\Models\Setting::set('contact_meta_description', $validated['contact_meta_description']);

        return back()->with('success', 'Contact page updated successfully.');
    }
}
