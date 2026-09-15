/* ============================================================================
   theme-boot.js — runs in <head> before any stylesheet is applied.
   Restores the saved colour theme and sidebar state so there is no flash of
   the wrong theme on load. Deliberately dependency-free (no jQuery/Bootstrap).
   ========================================================================== */
(function () {
  'use strict';

  var THEME_KEY = 'nc-theme';
  var SIDEBAR_KEY = 'nc-sidebar-collapsed';
  var root = document.documentElement;

  function read(key) {
    try {
      return window.localStorage.getItem(key);
    } catch (e) {
      return null;
    }
  }

  // --- Colour theme -------------------------------------------------------
  var theme = read(THEME_KEY);
  if (theme !== 'light' && theme !== 'dark') {
    theme =
      window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
        ? 'dark'
        : 'light';
  }
  root.setAttribute('data-bs-theme', theme);

  // Follow the OS only while the user has not made an explicit choice.
  if (!read(THEME_KEY) && window.matchMedia) {
    var mq = window.matchMedia('(prefers-color-scheme: dark)');
    var follow = function (e) {
      try {
        if (!window.localStorage.getItem(THEME_KEY)) {
          root.setAttribute('data-bs-theme', e.matches ? 'dark' : 'light');
        }
      } catch (err) {
        /* private mode — ignore */
      }
    };
    if (mq.addEventListener) mq.addEventListener('change', follow);
    else if (mq.addListener) mq.addListener(follow);
  }

  // --- Sidebar rail -------------------------------------------------------
  if (read(SIDEBAR_KEY) === '1' && window.innerWidth >= 992) {
    root.classList.add('sidebar-collapsed');
  }
})();
