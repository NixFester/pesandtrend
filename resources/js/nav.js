/**
 * Pesantrends — navbar mobile menu controller
 *
 * Responsibilities:
 *   • Toggle mobile menu open/closed
 *   • Lock body scroll when menu is open
 *   • Morph hamburger → X icon
 *   • Trap focus within menu when open
 *   • Close on Esc, outside click, and resize past md (768px)
 *
 * Registers itself on DOMContentLoaded. No Alpine, no dependencies.
 */

(function () {
    'use strict';

    const MENU_OPEN_CLASS = 'is-open';
    const SCROLL_LOCK_CLASS = 'overflow-hidden';
    const MD_BREAKPOINT = 768;

    let menuToggle = null;
    let menuPanel = null;
    let menuLinks = null;
    let iconMenu = null;
    let iconClose = null;
    let isOpen = false;
    let previouslyFocused = null;
    let resizeTimer = null;

    function init() {
        menuToggle = document.getElementById('menu-toggle');
        menuPanel = document.getElementById('mobile-menu');
        menuLinks = document.querySelector('[data-menu-links]');

        if (!menuToggle || !menuPanel) return;

        iconMenu = menuToggle.querySelector('[data-icon-menu]');
        iconClose = menuToggle.querySelector('[data-icon-close]');

        menuToggle.addEventListener('click', toggleMenu);

        // Close on click inside menu links
        menuLinks?.addEventListener('click', closeMenu);

        // Close on outside click
        document.addEventListener('click', handleOutsideClick);

        // Close on Escape
        document.addEventListener('keydown', handleKeydown);

        // Auto-close on resize past md
        window.addEventListener('resize', handleResize);
    }

    function toggleMenu() {
        isOpen ? closeMenu() : openMenu();
    }

    function openMenu() {
        isOpen = true;
        previouslyFocused = document.activeElement;

        menuPanel.classList.add(MENU_OPEN_CLASS);
        document.body.classList.add(SCROLL_LOCK_CLASS);

        // Icon morph: hamburger → X
        if (iconMenu) iconMenu.classList.add('hidden');
        if (iconClose) iconClose.classList.remove('hidden');

        menuToggle.setAttribute('aria-expanded', 'true');

        // Focus the first menu link
        const firstLink = menuLinks?.querySelector('a');
        firstLink?.focus();

        // Set up focus trap
        menuPanel.addEventListener('keydown', handlePanelKeydown);
    }

    function closeMenu() {
        if (!isOpen) return;
        isOpen = false;

        menuPanel.classList.remove(MENU_OPEN_CLASS);
        document.body.classList.remove(SCROLL_LOCK_CLASS);

        // Icon morph: X → hamburger
        if (iconMenu) iconMenu.classList.remove('hidden');
        if (iconClose) iconClose.classList.add('hidden');

        menuToggle.setAttribute('aria-expanded', 'false');

        // Return focus to the toggle button
        menuToggle?.focus();

        // Restore previously focused element
        if (previouslyFocused) {
            previouslyFocused.focus();
            previouslyFocused = null;
        }

        menuPanel.removeEventListener('keydown', handlePanelKeydown);
    }

    /** Trap focus within the open menu */
    function handlePanelKeydown(e) {
        if (e.key !== 'Tab') return;

        const focusable = menuPanel.querySelectorAll(
            'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'
        );
        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (e.shiftKey) {
            if (document.activeElement === first) {
                e.preventDefault();
                last.focus();
            }
        } else {
            if (document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    }

    function handleOutsideClick(e) {
        if (!isOpen) return;
        if (
            !e.target.closest('[data-menu-toggle]') &&
            !e.target.closest('[data-menu-panel]')
        ) {
            closeMenu();
        }
    }

    function handleKeydown(e) {
        if (e.key === 'Escape' && isOpen) {
            e.preventDefault();
            closeMenu();
        }
    }

    function handleResize() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth >= MD_BREAKPOINT && isOpen) {
                closeMenu();
            }
        }, 100);
    }

    document.addEventListener('DOMContentLoaded', init);
})();
