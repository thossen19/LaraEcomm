<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'role' => 'required|in:super_admin,admin,manager,staff,customer',
            'status' => 'required|in:active,inactive,blocked',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $data = $validated;
        $data['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['orders' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'role' => 'required|in:super_admin,admin,manager,staff,customer',
            'status' => 'required|in:active,inactive,blocked',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $data = $validated;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $userIds = $request->input('users', []);
        
        if (empty($userIds)) {
            return back()->with('error', 'No users selected.');
        }

        $users = User::whereIn('id', $userIds)->get();
        
        foreach ($users as $user) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->delete();
        }

        return back()->with('success', 'Selected users deleted successfully.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = 'password123';
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password reset to: {$newPassword}");
    }

    public function verifyEmail(User $user)
    {
        $user->update(['email_verified_at' => now()]);

        return back()->with('success', 'Email verified successfully.');
    }

    public function saveNotes(Request $request, User $user)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $user->update(['admin_notes' => $validated['admin_notes']]);

        return back()->with('success', 'Notes saved successfully.');
    }

    public function import()
    {
        return view('admin.users.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $users = [];
        $errors = [];
        
        if (($handle = fopen($path, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ',');
            $rowNumber = 2;
            
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                try {
                    $userData = array_combine($header, $data);
                    
                    $validated = [
                        'first_name' => $userData['first_name'] ?? '',
                        'last_name' => $userData['last_name'] ?? '',
                        'email' => $userData['email'] ?? '',
                        'phone' => $userData['phone'] ?? null,
                        'role' => $userData['role'] ?? 'customer',
                        'status' => $userData['status'] ?? 'active',
                        'password' => Hash::make('password123'),
                    ];
                    
                    $users[] = $validated;
                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
                $rowNumber++;
            }
            fclose($handle);
        }

        return view('admin.users.import', compact('users', 'errors'));
    }

    public function confirmImport(Request $request)
    {
        $users = $request->input('users', []);
        
        foreach ($users as $userData) {
            User::create($userData);
        }

        return redirect()->route('admin.users.index')->with('success', 'Users imported successfully.');
    }

    public function export()
    {
        $users = User::all();
        
        $csvData = [];
        $csvData[] = ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Role', 'Status', 'Created At'];
        
        foreach ($users as $user) {
            $csvData[] = [
                $user->id,
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->phone,
                $user->role,
                $user->status,
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }
        
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ];
        
        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function downloadTemplate()
    {
        $templateData = [
            ['first_name', 'last_name', 'email', 'phone', 'role', 'status']
        ];
        
        $filename = 'users_template.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ];
        
        $callback = function () use ($templateData) {
            $file = fopen('php://output', 'w');
            foreach ($templateData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function bulkActivate(Request $request)
    {
        $userIds = $request->input('users', []);
        
        User::whereIn('id', $userIds)->update(['status' => 'active']);
        
        return back()->with('success', 'Selected users activated successfully.');
    }

    public function bulkDeactivate(Request $request)
    {
        $userIds = $request->input('users', []);
        
        User::whereIn('id', $userIds)->update(['status' => 'inactive']);
        
        return back()->with('success', 'Selected users deactivated successfully.');
    }

    public function bulkBlock(Request $request)
    {
        $userIds = $request->input('users', []);
        
        User::whereIn('id', $userIds)->update(['status' => 'blocked']);
        
        return back()->with('success', 'Selected users blocked successfully.');
    }
}
