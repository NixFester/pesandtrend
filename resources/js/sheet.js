/**
 * Pesantrends — mobile sheet controller
 *
 * Opens/closes native <dialog> elements as bottom sheets.
 * Browser handles focus-trap, Esc, backdrop click, and safe-area natively.
 *
 * Usage:
 *   <x-mobile-sheet id="my-sheet">
 *     <!-- content -->
 *   </x-mobile-sheet>
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Open buttons: <button data-sheet-open="sheet-id">
        document.querySelectorAll('[data-sheet-open]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = btn.getAttribute('data-sheet-open');
                var dialog = document.getElementById(id);
                if (dialog) dialog.showModal();
            });
        });

        // Close buttons: <button data-sheet-close>
        document.querySelectorAll('[data-sheet-close]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var dialog = btn.closest('dialog');
                if (dialog) dialog.close();
            });
        });

        // Close on backdrop click
        document.querySelectorAll('dialog[id]').forEach(function (dialog) {
            dialog.addEventListener('click', function (e) {
                if (e.target === dialog) dialog.close();
            });
        });
    });
})();
