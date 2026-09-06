// Pesantrends — interaksi ringan sisi klien

import './nav.js';
import './sheet.js';

document.addEventListener('DOMContentLoaded', () => {
    // Format angka Rupiah pada kalkulator biaya
    document.querySelectorAll('[data-rupiah-input]').forEach((el) => {
        const fmt = (value) => {
            const digits = value.replace(/[^0-9]/g, '');
            return digits ? new Intl.NumberFormat('id-ID').format(parseInt(digits, 10)) : '';
        };
        el.addEventListener('input', () => {
            const pos = el.selectionStart;
            const before = el.value;
            el.value = fmt(el.value);
            if (pos === before.length) el.setSelectionRange(el.value.length, el.value.length);
        });
    });

    // Dropdown tambah sekolah (bandingkan)
    document.querySelectorAll('[data-compare-add]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const panel = document.getElementById('compare-add-panel');
            if (panel) panel.classList.toggle('hidden');
        });
    });

    // Tutup panel tambah sekolah bila klik di luar
    document.addEventListener('click', (e) => {
        const panel = document.getElementById('compare-add-panel');
        if (panel && !panel.classList.contains('hidden')) {
            if (
                !e.target.closest('[data-compare-add]') &&
                !e.target.closest('#compare-add-panel')
            ) {
                panel.classList.add('hidden');
            }
        }
    });

    // Navbar scroll effect untuk transparent di hero section
    const header = document.getElementById('main-header');
    if (header && header.dataset.isHome === 'true') {
        const onScroll = () => {
            if (window.scrollY > 20) {
                header.dataset.scrolled = 'true';
                header.classList.remove('bg-transparent', 'border-transparent');
                header.classList.add('bg-white/90', 'backdrop-blur-md', 'border-b', 'border-forest-100/70');
            } else {
                header.dataset.scrolled = 'false';
                header.classList.add('bg-transparent', 'border-transparent');
                header.classList.remove('bg-white/90', 'backdrop-blur-md', 'border-b', 'border-forest-100/70');
            }
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // initial check
    }
});

// Preset sekolah pada kalkulator biaya
const presetSelect = document.querySelector('[data-school-preset]');
if (presetSelect) {
    presetSelect.addEventListener('change', () => {
        const opt = presetSelect.selectedOptions[0];
        const data = opt ? opt.dataset.preset : null;
        if (data) {
            const params = new URLSearchParams(JSON.parse(data));
            window.location = presetSelect.dataset.schoolPreset + '?' + params.toString();
        }
    });
}
