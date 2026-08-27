// Pesantrends — interaksi ringan sisi klien

document.addEventListener('DOMContentLoaded', () => {
    // Toggle menu mobile
    const menuBtn = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            menuBtn.setAttribute('aria-expanded', mobileMenu.classList.contains('hidden') ? 'false' : 'true');
        });
    }

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
            // pertahankan kursor sederhana
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
        if (panel && ! panel.classList.contains('hidden')) {
            if (! e.target.closest('[data-compare-add]') && ! e.target.closest('#compare-add-panel')) {
                panel.classList.add('hidden');
            }
        }
    });
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
