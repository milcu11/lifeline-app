<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\Matching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $donor = $user->donor;
        
        return view('donor.profile', compact('donor'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'emergency_contact' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        $donor = $user->donor()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'blood_type', 'phone', 'address', 'date_of_birth', 
                'gender', 'latitude', 'longitude', 'emergency_contact', 
                'medical_conditions'
            ])
        );

        return redirect()->route('donor.profile')->with('success', 'Profile updated successfully!');
    }

    public function history()
    {
        $user = Auth::user();
        $donor = $user->donor;
        
        if (!$donor) {
            return redirect()->route('donor.profile')->with('info', 'Please complete your profile first.');
        }

        $donations = $donor->donations()->with('request')->latest()->paginate(10);
        
        return view('donor.history', compact('donor', 'donations'));
    }

    public function updateAvailability(Request $request)
    {
        $user = Auth::user();
        $donor = $user->donor;
        
        if (!$donor) {
            return redirect()->route('donor.profile')->with('error', 'Please complete your profile first.');
        }

        $donor->update([
            'is_available' => $request->boolean('is_available')
        ]);

        $status = $request->boolean('is_available') ? 'available' : 'unavailable';
        return redirect()->back()->with('success', "You are now marked as {$status}.");
    }

    public function viewRequests()
    {
        $user = Auth::user();
        $donor = $user->donor;
        
        if (!$donor) {
            return redirect()->route('donor.profile')->with('info', 'Please complete your profile first.');
        }

        $matches = $donor->matchings()
            ->with('request.hospital')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
        
        return view('donor.requests', compact('matches'));
    }

    public function respondToMatch(Request $request, Matching $match)
    {
        $request->validate([
            'response' => 'required|in:accepted,rejected'
        ]);

        $match->update([
            'status' => $request->response,
            'responded_at' => now()
        ]);

        $message = $request->response === 'accepted' 
            ? 'You have accepted this blood donation request.' 
            : 'You have declined this blood donation request.';

        return redirect()->back()->with('success', $message);
    }
}