/**
 * Admin Sidebar Toggle
 * - Toggles 'sidebar-collapsed' class on <body>
 * - Persists state in localStorage
 * - Works with a button that has [data-sidebar-toggle] or #sidebarToggle
 * - Optional keyboard shortcut: Ctrl/Cmd + B
 */
(function () {
  'use strict';

  var STORAGE_KEY = 'adminSidebarCollapsed';

  function getToggleButton() {
    var selectors = ['[data-sidebar-toggle]', '#sidebarToggle'];
    for (var i = 0; i < selectors.length; i++) {
      var el = document.querySelector(selectors[i]);
      if (el) return el;
    }
    return null;
  }

  function isCollapsed() {
    return document.body.classList.contains('sidebar-collapsed');
  }

  function setCollapsed(collapsed) {
    if (collapsed) {
      document.body.classList.add('sidebar-collapsed');
    } else {
      document.body.classList.remove('sidebar-collapsed');
    }
    try {
      localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
    } catch (e) {
      // ignore storage errors
    }
  }

  function toggleCollapsed(ev) {
    if (ev) ev.preventDefault();
    setCollapsed(!isCollapsed());
  }

  document.addEventListener('DOMContentLoaded', function () {
    // Restore state
    try {
      var saved = localStorage.getItem(STORAGE_KEY);
      if (saved === '1') {
        document.body.classList.add('sidebar-collapsed');
      }
    } catch (e) {
      // ignore storage errors
    }

    var btn = getToggleButton();
    if (btn) {
      btn.addEventListener('click', toggleCollapsed);
    }

    // Keyboard shortcut: Ctrl/Cmd + B
    document.addEventListener('keydown', function (e) {
      var isMeta = e.ctrlKey || e.metaKey;
      var key = e.key || '';
      if (isMeta && (key.toLowerCase() === 'b')) {
        toggleCollapsed(e);
      }
    });
  });
})();