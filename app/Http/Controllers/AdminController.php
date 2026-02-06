<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_donors' => Donor::count(),
            'active_donors' => Donor::where('is_available', true)->count(),
            'total_requests' => BloodRequest::count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'total_donations' => Donation::where('status', 'completed')->count(),
            'total_users' => User::count(),
        ];

        $recentRequests = BloodRequest::with('hospital')
            ->latest()
            ->take(5)
            ->get();

        $recentDonations = Donation::with('donor.user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRequests', 'recentDonations'));
    }

    public function donors()
    {
        $donors = Donor::with('user')
            ->latest()
            ->paginate(20);
        
        return view('admin.donors', compact('donors'));
    }

    public function requests()
    {
        $requests = BloodRequest::with('hospital')
            ->latest()
            ->paginate(20);
        
        return view('admin.requests', compact('requests'));
    }

    public function donations()
    {
        $donations = Donation::with(['donor.user', 'request'])
            ->latest()
            ->paginate(20);
        
        return view('admin.donations', compact('donations'));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        
        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User has been {$status}.");
    }

    public function map()
    {
        $donors = Donor::with('user')
            ->where('is_available', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $requests = BloodRequest::with('hospital')
            ->where('status', 'pending')
            ->get();

        return view('admin.map', compact('donors', 'requests'));
    }
}