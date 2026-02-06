@extends('layout.app')

@section('title', 'Geolocation Map')

@section('content')
<div class="map-page-container">
    <div class="page-header">
        <h1 class="page-title">Geolocation Map</h1>
        <p class="page-subtitle">Real-time donor and request locations</p>
    </div>

    <div class="map-controls">
        <div class="map-legend">
            <div class="legend-item">
                <span class="legend-marker donor-marker"></span>
                <span>Available Donors ({{ $donors->count() }})</span>
            </div>
            <div class="legend-item">
                <span class="legend-marker request-marker"></span>
                <span>Pending Requests ({{ $requests->count() }})</span>
            </div>
        </div>
    </div>

    <div class="map-container" data-testid="map-container">
        <div id="map" style="height: 600px; width: 100%; border-radius: 12px;"></div>
    </div>

    <div class="map-data-grid">
        <!-- Donors List -->
        <div class="map-data-card">
            <h3 class="card-title"><i class="fas fa-users"></i> Available Donors</h3>
            <div class="map-list">
                @forelse($donors as $donor)
                    <div class="map-list-item" data-testid="donor-marker-{{ $donor->id }}">
                        <div class="blood-badge blood-{{ str_replace(['+', '-'], ['pos', 'neg'], $donor->blood_type) }}">
                            {{ $donor->blood_type }}
                        </div>
                        <div class="map-item-info">
                            <h4>{{ $donor->user->name }}</h4>
                            <p><i class="fas fa-map-marker-alt"></i> {{ Str::limit($donor->address, 40) }}</p>
                            <p><i class="fas fa-phone"></i> {{ $donor->phone }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-sm">
                        <p>No available donors</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Requests List -->
        <div class="map-data-card">
            <h3 class="card-title"><i class="fas fa-hand-holding-medical"></i> Pending Requests</h3>
            <div class="map-list">
                @forelse($requests as $request)
                    <div class="map-list-item" data-testid="request-marker-{{ $request->id }}">
                        <div>
                            <span class="blood-badge blood-{{ str_replace(['+', '-'], ['pos', 'neg'], $request->blood_type) }}">
                                {{ $request->blood_type }}
                            </span>
                            <span class="urgency-badge urgency-{{ $request->urgency_level }}">
                                {{ ucfirst($request->urgency_level) }}
                            </span>
                        </div>
                        <div class="map-item-info">
                            <h4>{{ $request->hospital->name }}</h4>
                            <p><i class="fas fa-droplet"></i> {{ $request->quantity }} units needed</p>
                            <p><i class="fas fa-map-marker-alt"></i> {{ Str::limit($request->location, 40) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-sm">
                        <p>No pending requests</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map (centered on Philippines as default)
    const map = L.map('map').setView([14.5995, 120.9842], 6);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add donor markers
    const donorIcon = L.divIcon({
        className: 'custom-marker donor-map-marker',
        html: '<div style="background: #dc2626; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-user" style="font-size: 14px;"></i></div>',
        iconSize: [30, 30]
    });

    @foreach($donors as $donor)
        @if($donor->latitude && $donor->longitude)
            L.marker([{{ $donor->latitude }}, {{ $donor->longitude }}], { icon: donorIcon })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width: 200px;">
                        <h4 style="margin: 0 0 8px 0; color: #dc2626;"><i class="fas fa-user-circle"></i> {{ $donor->user->name }}</h4>
                        <p style="margin: 4px 0;"><strong>Blood Type:</strong> <span class="blood-badge">{{ $donor->blood_type }}</span></p>
                        <p style="margin: 4px 0;"><strong>Phone:</strong> {{ $donor->phone }}</p>
                        <p style="margin: 4px 0;"><strong>Address:</strong> {{ $donor->address }}</p>
                    </div>
                `);
        @endif
    @endforeach

    // Add request markers
    const requestIcon = L.divIcon({
        className: 'custom-marker request-map-marker',
        html: '<div style="background: #ea580c; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-hospital" style="font-size: 14px;"></i></div>',
        iconSize: [30, 30]
    });

    @foreach($requests as $request)
        @if($request->latitude && $request->longitude)
            L.marker([{{ $request->latitude }}, {{ $request->longitude }}], { icon: requestIcon })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width: 200px;">
                        <h4 style="margin: 0 0 8px 0; color: #ea580c;"><i class="fas fa-hospital"></i> {{ $request->hospital->name }}</h4>
                        <p style="margin: 4px 0;"><strong>Blood Type:</strong> <span class="blood-badge">{{ $request->blood_type }}</span></p>
                        <p style="margin: 4px 0;"><strong>Quantity:</strong> {{ $request->quantity }} units</p>
                        <p style="margin: 4px 0;"><strong>Urgency:</strong> <span class="urgency-badge urgency-{{ $request->urgency_level }}">{{ ucfirst($request->urgency_level) }}</span></p>
                        <p style="margin: 4px 0;"><strong>Location:</strong> {{ $request->location }}</p>
                    </div>
                `);
        @endif
    @endforeach

    // Auto-fit bounds if markers exist
    const allMarkers = [];
    @foreach($donors as $donor)
        @if($donor->latitude && $donor->longitude)
            allMarkers.push([{{ $donor->latitude }}, {{ $donor->longitude }}]);
        @endif
    @endforeach
    @foreach($requests as $request)
        @if($request->latitude && $request->longitude)
            allMarkers.push([{{ $request->latitude }}, {{ $request->longitude }}]);
        @endif
    @endforeach

    if (allMarkers.length > 0) {
        const bounds = L.latLngBounds(allMarkers);
        map.fitBounds(bounds, { padding: [50, 50] });
    }
});
</script>
@endpush
@endsection