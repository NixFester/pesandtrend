/**
 * Pesantrends Leaflet Map Module
 * Lazy-loaded by map pages.
 */
export function initMap(elementId, schools = [], options = {}) {
    const defaultCenter = [-6.5, 106.8]; // Bogor area
    const defaultZoom = 10;

    const map = L.map(elementId).setView(
        options.center || defaultCenter,
        options.zoom || defaultZoom
    );

    L.tileLayer(
        window.__MAP_TILE_URL || 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 18,
        }
    ).addTo(map);

    // Add school markers
    schools.forEach((school) => {
        if (!school.latitude || !school.longitude) return;

        const marker = L.marker([school.latitude, school.longitude]).addTo(map);

        const popup = `
            <div style="min-width:180px">
                <strong style="font-size:14px">${school.name}</strong>
                <p style="margin:4px 0;font-size:12px;color:#666">${school.city}, ${school.province}</p>
                <p style="margin:4px 0;font-size:12px">SPP: Rp ${Number(school.spp_monthly).toLocaleString('id-ID')}/bln</p>
                <a href="/sekolah/${school.slug}" style="color:#12462a;font-size:12px;font-weight:600">Lihat Detail →</a>
            </div>
        `;

        marker.bindPopup(popup);
    });

    // Fit bounds if markers exist
    const markers = schools.filter(s => s.latitude && s.longitude);
    if (markers.length > 0) {
        const bounds = L.latLngBounds(markers.map(s => [s.latitude, s.longitude]));
        map.fitBounds(bounds, { padding: [30, 30] });
    }

    return map;
}
