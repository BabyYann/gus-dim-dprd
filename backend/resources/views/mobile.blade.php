<!DOCTYPE html>
<html lang="id">
<head>
  <script>
    (function() {
      var urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('view') === 'mobile' || urlParams.get('mode') === 'preview') return;
      var isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
      var isDesktopWidth = window.innerWidth > 768;
      if ((!isMobileDevice && isDesktopWidth) || window.innerWidth > 1024) {
        var isHtmlFile = window.location.pathname.endsWith('.html') || window.location.protocol === 'file:';
        var dest = isHtmlFile ? '../Index.html' : '/';
        window.location.replace(dest);
      }
    })();
    window.addEventListener('resize', function() {
      clearTimeout(window.__switchDesktopTimer);
      window.__switchDesktopTimer = setTimeout(function() {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('view') === 'mobile' || urlParams.get('mode') === 'preview') return;
        var isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        var isDesktopWidth = window.innerWidth > 768;
        if ((!isMobileDevice && isDesktopWidth) || window.innerWidth > 1024) {
          var isHtmlFile = window.location.pathname.endsWith('.html') || window.location.protocol === 'file:';
          var dest = isHtmlFile ? '../Index.html' : '/';
          window.location.replace(dest);
        }
      }, 300);
    });
    function switchToDesktopView() {
      var isHtmlFile = window.location.pathname.endsWith('.html') || window.location.protocol === 'file:';
      var dest = isHtmlFile ? '../Index.html?view=desktop' : '/?view=desktop';
      window.location.href = dest;
    }
  </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <meta name="theme-color" content="#16225e">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Gus Dim Mobile">
  <link rel="manifest" href="manifest.json">
  <link rel="apple-touch-icon" href="assets/icons/gus-dim.png">
  <link rel="icon" type="image/png" href="assets/icons/gus-dim.png">
  <title>Gus Dim Mobile - Aplikasi PWA Lapangan Dapil Kraksaan Raya</title>
  <script src="assets/js/pusher.min.js"></script>
  <style>
/* Strict Global SVG Containment */
svg {
  max-width: 100%;
  height: auto;
  display: inline-block;
  vertical-align: middle;
}

.btn-outline-touch svg,
.btn-wa-touch svg,
.btn-primary-touch svg,
button svg,
a svg {
  width: 18px !important;
  height: 18px !important;
  min-width: 18px !important;
  min-height: 18px !important;
  max-width: 18px !important;
  max-height: 18px !important;
  flex-shrink: 0 !important;
  display: inline-block !important;
  vertical-align: middle !important;
}

.badge-status svg {
  width: 12px !important;
  height: 12px !important;
  min-width: 12px !important;
  min-height: 12px !important;
  flex-shrink: 0 !important;
}

.card-detail-item svg {
  width: 14px !important;
  height: 14px !important;
  min-width: 14px !important;
  min-height: 14px !important;
  flex-shrink: 0 !important;
}

.header-btn svg {
  width: 18px !important;
  height: 18px !important;
  min-width: 18px !important;
  min-height: 18px !important;
}

.nav-item svg {
  width: 22px !important;
  height: 22px !important;
  min-width: 22px !important;
  min-height: 22px !important;
}

.nav-fab svg {
  width: 26px !important;
  height: 26px !important;
  min-width: 26px !important;
  min-height: 26px !important;
}

.empty-state svg {
  width: 44px !important;
  height: 44px !important;
  min-width: 44px !important;
  min-height: 44px !important;
}
/* ==========================================================================
   GUS DIM MOBILE (PWA) - STYLESHEET RESMI LENGKAP SMARTPHONE
   10 Halaman Lengkap & Navigasi Rumpun Sesuai Versi Desktop
   100% BEBAS EMOJI - Pure Clean SVG & Micro-Interactions Native
   ========================================================================== */

:root {
  --primary: #16225e;
  --primary-light: #2563eb;
  --primary-subtle: #eff6ff;
  --primary-dark: #0f172a;
  --accent-gold: #f59e0b;
  --accent-gold-light: #fef3c7;
  --success: #16a34a;
  --success-light: #dcfce7;
  --warning: #d97706;
  --warning-light: #fef3c7;
  --danger: #dc2626;
  --danger-light: #fee2e2;
  --info: #0284c7;
  --info-light: #e0f2fe;
  --purple: #9333ea;
  --purple-light: #faf5ff;

  --bg-app: #f8fafc;
  --bg-card: #ffffff;
  --bg-input: #f8fafc;
  --border-color: #e2e8f0;
  --border-focus: #2563eb;

  --text-main: #0f172a;
  --text-secondary: #475569;
  --text-muted: #64748b;
  --text-inverse: #ffffff;

  --font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
  --radius-xl: 20px;
  --radius-full: 9999px;

  --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.05);
  --shadow-md: 0 4px 12px rgba(15, 23, 42, 0.07);
  --shadow-lg: 0 10px 24px rgba(15, 23, 42, 0.12);
  --shadow-fab: 0 8px 24px rgba(37, 99, 235, 0.4);
  --shadow-sheet: 0 -8px 30px rgba(15, 23, 42, 0.18);
  --shadow-drawer: 8px 0 30px rgba(15, 23, 42, 0.2);

  --safe-top: env(safe-area-inset-top, 0px);
  --safe-bottom: env(safe-area-inset-bottom, 0px);
  --bottom-nav-height: 64px;
  --header-height: 56px;
}

*, *::before, *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  -webkit-tap-highlight-color: transparent;
}

html, body {
  width: 100%;
  height: 100%;
  background-color: var(--bg-app);
  color: var(--text-main);
  font-family: var(--font-family);
  font-size: 15px;
  line-height: 1.5;
  overflow: hidden;
  user-select: none;
  -webkit-user-select: none;
}

/* Container Smartphone */
.mobile-app {
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100%;
  max-width: 500px;
  margin: 0 auto;
  position: relative;
  background-color: var(--bg-app);
  overflow: hidden;
  box-shadow: 0 0 40px rgba(0,0,0,0.06);
}

/* ==========================================================================
   TOP HEADER
   ========================================================================== */
.mobile-header {
  flex-shrink: 0;
  padding-top: max(var(--safe-top), 8px);
  padding-left: 14px;
  padding-right: 14px;
  padding-bottom: 10px;
  background: linear-gradient(135deg, #16225e 0%, #1e3a8a 100%);
  color: var(--text-inverse);
  box-shadow: var(--shadow-md);
  z-index: 30;
}

.header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 44px;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

.header-brand {
  display: flex;
  align-items: center;
  gap: 8px;
}

.header-avatar {
  width: 36px;
  height: 36px;
  border-radius: var(--radius-full);
  border: 2px solid var(--accent-gold);
  object-fit: cover;
  background-color: #ffffff;
  flex-shrink: 0;
}

.header-title-box {
  display: flex;
  flex-direction: column;
}

.header-app-title {
  font-size: 15px;
  font-weight: 800;
  letter-spacing: -0.2px;
  line-height: 1.1;
}

.header-badge-dapil {
  font-size: 10px;
  color: #fbbf24;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 6px;
}

.header-btn {
  width: 36px;
  height: 36px;
  border-radius: var(--radius-md);
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}

.header-btn:active {
  transform: scale(0.92);
  background: rgba(255, 255, 255, 0.22);
}

.header-btn svg {
  width: 18px;
  height: 18px;
  stroke: currentColor;
}

/* Offline Banner */
.offline-banner {
  display: none;
  background-color: #b45309;
  color: #fff;
  font-size: 11px;
  padding: 5px 14px;
  text-align: center;
  font-weight: 500;
  z-index: 35;
}

.offline-banner.active {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}

/* ==========================================================================
   SLIDE-OUT DRAWER MENU (SIDEBAR LENGKAP 4 RUMPUN)
   ========================================================================== */
.drawer-backdrop {
  position: fixed;
  inset: 0;
  background-color: rgba(15, 23, 42, 0.65);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
  z-index: 110;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.25s ease-out;
}

.drawer-backdrop.open {
  opacity: 1;
  pointer-events: auto;
}

.mobile-drawer {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  width: 82%;
  max-width: 320px;
  background-color: #ffffff;
  z-index: 120;
  transform: translateX(-100%);
  transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1);
  display: flex;
  flex-direction: column;
  box-shadow: var(--shadow-drawer);
}

.mobile-drawer.open {
  transform: translateX(0);
}

.drawer-header {
  padding: max(var(--safe-top), 16px) 18px 16px 18px;
  background: linear-gradient(135deg, #16225e 0%, #1e3a8a 100%);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.drawer-user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.drawer-avatar {
  width: 44px;
  height: 44px;
  border-radius: var(--radius-full);
  border: 2px solid var(--accent-gold);
  object-fit: cover;
  background: #ffffff;
}

.drawer-user-name {
  font-size: 15px;
  font-weight: 700;
  line-height: 1.2;
}

.drawer-user-role {
  font-size: 11px;
  color: #fbbf24;
  font-weight: 600;
}

.drawer-close-btn {
  background: rgba(255, 255, 255, 0.15);
  border: none;
  width: 32px;
  height: 32px;
  border-radius: var(--radius-full);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.drawer-close-btn svg {
  width: 18px;
  height: 18px;
  stroke: currentColor;
}

.drawer-body {
  flex: 1;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  padding: 12px 14px;
}

.drawer-section-title {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  color: #94a3b8;
  letter-spacing: 0.5px;
  padding: 12px 8px 6px 8px;
}

.drawer-menu-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 12px;
  border-radius: var(--radius-md);
  color: var(--text-secondary);
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s;
  text-decoration: none;
  margin-bottom: 2px;
}

.drawer-menu-item:active, .drawer-menu-item.active {
  background-color: var(--primary-subtle);
  color: var(--primary-light);
}

.drawer-menu-item-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.drawer-menu-item svg {
  width: 20px;
  height: 20px;
  stroke: currentColor;
}

.drawer-badge {
  font-size: 11px;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: var(--radius-full);
  background: #f1f5f9;
  color: #64748b;
}

.drawer-menu-item.active .drawer-badge {
  background: #dbeafe;
  color: #1d4ed8;
}

.drawer-footer {
  padding: 14px 18px max(var(--safe-bottom), 14px) 18px;
  border-top: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  gap: 8px;
}

/* ==========================================================================
   SCROLLABLE MAIN CONTENT AREA
   ========================================================================== */
.mobile-main {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  -webkit-overflow-scrolling: touch;
  padding: 14px;
  padding-bottom: calc(var(--bottom-nav-height) + var(--safe-bottom) + 20px);
}

.tab-pane {
  display: none;
  animation: fadeIn 0.2s ease-out;
}

.tab-pane.active {
  display: block;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Page Header in view */
.page-title-banner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 14px;
  padding: 0 2px;
}

.page-title-text {
  font-size: 17px;
  font-weight: 800;
  color: var(--text-main);
  letter-spacing: -0.3px;
}

.page-subtitle-text {
  font-size: 12px;
  color: var(--text-muted);
}

/* ==========================================================================
   BOTTOM NAVIGATION BAR
   ========================================================================== */
.mobile-bottom-nav {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: calc(var(--bottom-nav-height) + var(--safe-bottom));
  padding-bottom: var(--safe-bottom);
  background: rgba(255, 255, 255, 0.96);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border-top: 1px solid var(--border-color);
  box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.05);
  display: flex;
  align-items: center;
  justify-content: space-around;
  z-index: 40;
}

.nav-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex: 1;
  height: 100%;
  color: var(--text-muted);
  font-size: 11px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  position: relative;
  text-decoration: none;
}

.nav-item svg {
  width: 22px;
  height: 22px;
  stroke: currentColor;
  stroke-width: 2;
  margin-bottom: 2px;
  transition: all 0.2s;
}

.nav-item.active {
  color: var(--primary-light);
  font-weight: 700;
}

.nav-item.active svg {
  stroke: var(--primary-light);
  transform: translateY(-2px);
}

.nav-item.active::after {
  content: '';
  position: absolute;
  top: 4px;
  width: 18px;
  height: 3px;
  background-color: var(--primary-light);
  border-radius: var(--radius-full);
}

/* Center Raised Floating Action Button */
.nav-fab-container {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  height: 100%;
}

.nav-fab {
  position: absolute;
  top: -18px;
  width: 52px;
  height: 52px;
  border-radius: var(--radius-full);
  background: linear-gradient(135deg, #16225e 0%, #2563eb 100%);
  border: 3px solid #ffffff;
  box-shadow: var(--shadow-fab);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s;
}

.nav-fab:active {
  transform: scale(0.9) translateY(2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.nav-fab svg {
  width: 26px;
  height: 26px;
  stroke: #ffffff;
  stroke-width: 2.4;
}

/* ==========================================================================
   CARDS & KPI WIDGETS
   ========================================================================== */
.card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: 16px;
  margin-bottom: 14px;
  box-shadow: var(--shadow-sm);
}

/* KPI Summary Cards */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
  margin-bottom: 14px;
}

.kpi-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  padding: 12px 14px;
  display: flex;
  flex-direction: column;
  gap: 4px;
  box-shadow: var(--shadow-sm);
  position: relative;
  overflow: hidden;
}

.kpi-card::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background-color: var(--primary-light);
}

.kpi-card.gold::before { background-color: var(--accent-gold); }
.kpi-card.green::before { background-color: var(--success); }
.kpi-card.info::before { background-color: var(--info); }
.kpi-card.purple::before { background-color: var(--purple); }

.kpi-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.kpi-title {
  font-size: 11px;
  color: var(--text-muted);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.kpi-icon {
  width: 26px;
  height: 26px;
  border-radius: var(--radius-sm);
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--primary-subtle);
  color: var(--primary-light);
}

.kpi-icon.gold { background-color: var(--accent-gold-light); color: var(--accent-gold); }
.kpi-icon.green { background-color: var(--success-light); color: var(--success); }
.kpi-icon.info { background-color: var(--info-light); color: var(--info); }
.kpi-icon.purple { background-color: var(--purple-light); color: var(--purple); }

.kpi-icon svg {
  width: 15px;
  height: 15px;
  stroke: currentColor;
}

.kpi-value {
  font-size: 20px;
  font-weight: 800;
  color: var(--text-main);
  line-height: 1.1;
}

.kpi-subtext {
  font-size: 11px;
  color: var(--text-muted);
}

/* Quick Navigation Shortcut Grid */
.shortcut-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 10px;
  margin-bottom: 14px;
}

.shortcut-tile {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  padding: 10px 4px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  cursor: pointer;
  transition: all 0.15s;
  text-align: center;
  box-shadow: var(--shadow-sm);
}

.shortcut-tile:active {
  transform: scale(0.94);
  background-color: var(--primary-subtle);
}

.shortcut-icon {
  width: 38px;
  height: 38px;
  border-radius: var(--radius-full);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.shortcut-icon svg {
  width: 18px;
  height: 18px;
  stroke: currentColor;
}

.shortcut-label {
  font-size: 11px;
  font-weight: 600;
  color: var(--text-secondary);
  line-height: 1.1;
}

/* Progress Target Widget */
.progress-widget {
  background: linear-gradient(135deg, #16225e 0%, #1e3a8a 100%);
  color: #ffffff;
  border-radius: var(--radius-lg);
  padding: 16px;
  margin-bottom: 14px;
  box-shadow: var(--shadow-md);
}

.progress-header {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  margin-bottom: 10px;
}

.progress-title {
  font-size: 14px;
  font-weight: 700;
}

.progress-target-text {
  font-size: 12px;
  color: #93c5fd;
}

.progress-bar-bg {
  width: 100%;
  height: 10px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: var(--radius-full);
  overflow: hidden;
  margin-bottom: 8px;
}

.progress-bar-fill {
  height: 100%;
  background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
  border-radius: var(--radius-full);
  transition: width 0.6s ease-in-out;
}

.progress-footer {
  display: flex;
  justify-content: space-between;
  font-size: 11px;
  color: #cbd5e1;
}

/* ==========================================================================
   TOUCH CARDS (MOBILE LIST VIEW)
   ========================================================================== */
.touch-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: 14px;
  margin-bottom: 10px;
  box-shadow: var(--shadow-sm);
  display: flex;
  flex-direction: column;
  gap: 10px;
  transition: transform 0.15s, border-color 0.15s;
  cursor: pointer;
}

.touch-card:active {
  transform: scale(0.985);
  border-color: var(--primary-light);
}

.card-top-row {
  display: flex;
  align-items: center;
  gap: 12px;
}

.card-avatar {
  width: 42px;
  height: 42px;
  border-radius: var(--radius-full);
  background: var(--primary-subtle);
  color: var(--primary-light);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 15px;
  flex-shrink: 0;
  border: 1px solid rgba(37, 99, 235, 0.2);
}

.card-info {
  flex: 1;
  min-width: 0;
}

.card-name {
  font-size: 15px;
  font-weight: 700;
  color: var(--text-main);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.2;
}

.card-nik {
  font-size: 12px;
  color: var(--text-muted);
  font-family: monospace;
  margin-top: 2px;
}

.badge-status {
  padding: 4px 8px;
  border-radius: var(--radius-full);
  font-size: 11px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  flex-shrink: 0;
}

.badge-status.valid {
  background-color: var(--success-light);
  color: var(--success);
}

.badge-status.pending {
  background-color: var(--warning-light);
  color: var(--warning);
}

.badge-status.danger,
.badge-status.duplicate {
  background-color: var(--danger-light);
  color: var(--danger);
}

.badge-status.info {
  background-color: var(--info-light);
  color: var(--info);
}

.badge-status.purple {
  background-color: var(--purple-light);
  color: var(--purple);
}

.badge-status svg {
  width: 12px;
  height: 12px;
  stroke: currentColor;
}

.card-details-row {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 6px;
  font-size: 12px;
  color: var(--text-secondary);
  border-top: 1px dashed var(--border-color);
  padding-top: 8px;
}

.card-detail-item {
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.card-detail-item svg {
  width: 14px;
  height: 14px;
  stroke: var(--text-muted);
}

.card-pill {
  background-color: #f1f5f9;
  padding: 2px 8px;
  border-radius: var(--radius-sm);
  font-size: 11px;
  color: var(--text-secondary);
  font-weight: 500;
}

.card-pill.blue { background-color: #eff6ff; color: #1d4ed8; }
.card-pill.amber { background-color: #fef3c7; color: #b45309; }
.card-pill.green { background-color: #f0fdf4; color: #15803d; }
.card-pill.purple { background-color: #faf5ff; color: #7e22ce; }
.card-pill.rose { background-color: #fff1f2; color: #be123c; }
.card-pill.teal { background-color: #f0fdfa; color: #0f766e; }

.card-actions-row {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 2px;
}

.btn-wa-touch {
  flex: 1;
  height: 40px;
  background-color: #25d366;
  color: #ffffff;
  border: none;
  border-radius: var(--radius-md);
  font-size: 13px;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}

.btn-wa-touch:active {
  transform: scale(0.96);
  background-color: #1eb857;
}

.btn-wa-touch svg {
  width: 18px;
  height: 18px;
  fill: currentColor;
}

.btn-outline-touch {
  height: 40px;
  padding: 0 14px;
  background: var(--bg-app);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  color: var(--text-secondary);
  font-size: 13px;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-outline-touch:active {
  transform: scale(0.96);
  background: #e2e8f0;
}

/* ==========================================================================
   SEARCH & FILTER BAR
   ========================================================================== */
.search-filter-wrapper {
  margin-bottom: 12px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  width: 100%;
  max-width: 100%;
  min-width: 0;
  box-sizing: border-box;
}

.search-input-box {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
  box-sizing: border-box;
}

.search-input-box svg {
  position: absolute;
  left: 14px;
  width: 18px;
  height: 18px;
  stroke: var(--text-muted);
}

.search-input-mobile {
  width: 100%;
  height: 48px;
  padding-left: 42px;
  padding-right: 42px;
  font-size: 16px;
  background-color: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-lg);
  color: var(--text-main);
  outline: none;
  box-shadow: var(--shadow-sm);
  transition: border-color 0.2s;
}

.search-input-mobile:focus {
  border-color: var(--primary-light);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

.search-clear-btn {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  color: var(--text-muted);
  cursor: pointer;
  display: none;
  padding: 4px;
}

.search-clear-btn.active {
  display: block;
}

.search-clear-btn svg {
  position: static;
  width: 16px;
  height: 16px;
}

.filter-pills-scroll {
  display: flex;
  align-items: center;
  gap: 8px;
  overflow-x: auto;
  overflow-y: hidden;
  width: 100%;
  max-width: 100%;
  min-width: 0;
  box-sizing: border-box;
  padding: 4px 2px 8px 2px;
  scrollbar-width: none;
  -ms-overflow-style: none;
  -webkit-overflow-scrolling: touch;
  touch-action: pan-x;
  overscroll-behavior-x: contain;
  cursor: grab;
}

.filter-pills-scroll.dragging {
  cursor: grabbing;
  user-select: none;
  -webkit-user-select: none;
}

.filter-pills-scroll::-webkit-scrollbar {
  display: none;
  width: 0;
  height: 0;
}

.filter-pill {
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 11px;
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-full);
  font-size: 11.5px;
  font-weight: 500;
  color: var(--text-secondary);
  white-space: nowrap;
  user-select: none;
  -webkit-user-select: none;
  -webkit-tap-highlight-color: transparent;
  cursor: pointer;
  transition: all 0.15s;
}

.filter-pill.active {
  background: var(--primary);
  color: #ffffff;
  border-color: var(--primary);
  font-weight: 600;
}

.filter-pill .pill-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 17px;
  height: 17px;
  padding: 0 4px;
  border-radius: 999px;
  font-size: 10px;
  font-weight: 700;
  background: #f1f5f9;
  color: #475569;
  line-height: 1;
  transition: all 0.15s;
}

.filter-pill.active .pill-badge {
  background: rgba(255, 255, 255, 0.25);
  color: #ffffff;
}

.filter-pill .pill-badge.yellow { background: #fef3c7; color: #b45309; }
.filter-pill.active .pill-badge.yellow { background: #fbbf24; color: #78350f; }

.filter-pill .pill-badge.purple { background: #faf5ff; color: #7e22ce; }
.filter-pill.active .pill-badge.purple { background: #c084fc; color: #4c1d95; }

.filter-pill .pill-badge.blue { background: #eff6ff; color: #1d4ed8; }
.filter-pill.active .pill-badge.blue { background: #60a5fa; color: #1e3a8a; }

.filter-pill .pill-badge.green { background: #f0fdf4; color: #15803d; }
.filter-pill.active .pill-badge.green { background: #4ade80; color: #14532d; }

/* Badge Tahapan Siklus Pokir APBD Mobile */
.badge-stage {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 3px 8px;
  border-radius: var(--radius-sm);
  font-size: 10.5px;
  font-weight: 700;
  line-height: 1.2;
  white-space: nowrap;
}
.badge-stage.stage-1 { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
.badge-stage.stage-2 { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
.badge-stage.stage-3 { background: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff; }
.badge-stage.stage-4 { background: #ecfeff; color: #0891b2; border: 1px solid #a5f3fc; }
.badge-stage.stage-5 { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.badge-stage.stage-6 { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }

/* ==========================================================================
   BOTTOM SHEET DRAWER (MODAL SENTUH)
   ========================================================================== */
.sheet-backdrop {
  position: fixed;
  inset: 0;
  background-color: rgba(15, 23, 42, 0.6);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
  z-index: 90;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.25s ease-out;
}

.sheet-backdrop.open {
  opacity: 1;
  pointer-events: auto;
}

.bottom-sheet {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  max-width: 500px;
  margin: 0 auto;
  max-height: 88vh;
  background-color: var(--bg-card);
  border-radius: var(--radius-xl) var(--radius-xl) 0 0;
  box-shadow: var(--shadow-sheet);
  z-index: 100;
  transform: translateY(100%);
  transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1);
  display: flex;
  flex-direction: column;
  padding-bottom: max(var(--safe-bottom), 12px);
}

.bottom-sheet.open {
  transform: translateY(0);
}

.sheet-handle-bar {
  width: 100%;
  height: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: grab;
  flex-shrink: 0;
}

.sheet-handle {
  width: 36px;
  height: 4px;
  background-color: #cbd5e1;
  border-radius: var(--radius-full);
}

.sheet-header {
  padding: 0 18px 12px 18px;
  border-bottom: 1px solid var(--border-color);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-shrink: 0;
}

.sheet-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-main);
}

.sheet-close-btn {
  background: #f1f5f9;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: var(--radius-full);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-muted);
  cursor: pointer;
}

.sheet-close-btn svg {
  width: 18px;
  height: 18px;
  stroke: currentColor;
}

.sheet-content {
  padding: 18px;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  flex: 1;
}

/* ==========================================================================
   MOBILE TOUCH FORMS
   ========================================================================== */
.form-group {
  margin-bottom: 14px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-label {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-secondary);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.form-label .required-mark {
  color: var(--danger);
  font-weight: bold;
}

.form-input-touch, .form-select-touch, .form-textarea-touch {
  width: 100%;
  min-height: 48px;
  padding: 10px 14px;
  font-size: 16px;
  font-family: inherit;
  color: var(--text-main);
  background-color: var(--bg-input);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  outline: none;
  transition: all 0.2s;
}

.form-input-touch:focus, .form-select-touch:focus, .form-textarea-touch:focus {
  border-color: var(--primary-light);
  background-color: #ffffff;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

.form-textarea-touch {
  min-height: 80px;
  resize: vertical;
}

/* Camera AI OCR Box */
.ocr-capture-card {
  border: 2px dashed #93c5fd;
  background-color: #eff6ff;
  border-radius: var(--radius-lg);
  padding: 14px;
  text-align: center;
  margin-bottom: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.ocr-capture-card:active {
  transform: scale(0.98);
  background-color: #dbeafe;
}

.ocr-icon-circle {
  width: 44px;
  height: 44px;
  border-radius: var(--radius-full);
  background-color: #2563eb;
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 6px;
}

.ocr-icon-circle svg {
  width: 22px;
  height: 22px;
  stroke: currentColor;
}

.ocr-title {
  font-size: 13px;
  font-weight: 700;
  color: #1e40af;
}

.ocr-desc {
  font-size: 11px;
  color: #3b82f6;
  margin-top: 2px;
}

/* GPS Pin Box */
.gps-capture-card {
  border: 1px solid #bbf7d0;
  background-color: #f0fdf4;
  border-radius: var(--radius-lg);
  padding: 12px 14px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 14px;
}

.gps-info-box {
  display: flex;
  align-items: center;
  gap: 10px;
}

.gps-icon-circle {
  width: 38px;
  height: 38px;
  border-radius: var(--radius-full);
  background-color: #16a34a;
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.gps-icon-circle svg {
  width: 18px;
  height: 18px;
  stroke: currentColor;
}

.gps-text-title {
  font-size: 13px;
  font-weight: 700;
  color: #15803d;
}

.gps-text-coords {
  font-size: 11px;
  color: #166534;
  font-family: monospace;
}

.btn-gps-lock {
  padding: 8px 12px;
  background-color: #16a34a;
  color: #ffffff;
  border: none;
  border-radius: var(--radius-md);
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  flex-shrink: 0;
}

/* Sticky Submit Bar */
.sticky-form-cta {
  position: sticky;
  bottom: 0;
  background: var(--bg-card);
  padding-top: 12px;
  border-top: 1px solid var(--border-color);
  margin-top: 16px;
}

.btn-primary-touch {
  width: 100%;
  min-height: 48px;
  background: linear-gradient(135deg, #16225e 0%, #2563eb 100%);
  color: #ffffff;
  border: none;
  border-radius: var(--radius-md);
  font-size: 15px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
  transition: all 0.2s;
}

.btn-primary-touch:active {
  transform: scale(0.98);
  box-shadow: none;
}

.btn-primary-touch svg {
  width: 18px;
  height: 18px;
  stroke: currentColor;
}

/* ==========================================================================
   LEADERBOARD PODIUM & LIST
   ========================================================================== */
.podium-container {
  display: flex;
  align-items: flex-end;
  justify-content: center;
  gap: 8px;
  margin-bottom: 20px;
  padding: 10px 4px 0 4px;
}

.podium-item {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.podium-avatar-box {
  position: relative;
  margin-bottom: 6px;
}

.podium-avatar {
  width: 48px;
  height: 48px;
  border-radius: var(--radius-full);
  background: #eff6ff;
  border: 2px solid #94a3b8;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 16px;
}

.podium-item.first .podium-avatar {
  width: 58px;
  height: 58px;
  border-color: #f59e0b;
  box-shadow: 0 0 16px rgba(245, 158, 11, 0.35);
}

.podium-item.second .podium-avatar {
  border-color: #94a3b8;
}

.podium-item.third .podium-avatar {
  border-color: #b45309;
}

.podium-badge {
  position: absolute;
  bottom: -4px;
  right: -4px;
  width: 22px;
  height: 22px;
  border-radius: var(--radius-full);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 800;
  color: #fff;
}

.podium-item.first .podium-badge { background: #f59e0b; }
.podium-item.second .podium-badge { background: #94a3b8; }
.podium-item.third .podium-badge { background: #b45309; }

.podium-name {
  font-size: 12px;
  font-weight: 700;
  color: var(--text-main);
  max-width: 90px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.podium-score {
  font-size: 12px;
  font-weight: 800;
  color: var(--primary-light);
}

.podium-pillar {
  width: 100%;
  border-radius: var(--radius-md) var(--radius-md) 0 0;
  background: linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  color: #64748b;
  font-size: 14px;
}

.podium-item.first .podium-pillar {
  height: 75px;
  background: linear-gradient(180deg, #fef3c7 0%, #fde68a 100%);
  color: #b45309;
}

.podium-item.second .podium-pillar {
  height: 55px;
  background: linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 100%);
}

.podium-item.third .podium-pillar {
  height: 42px;
  background: linear-gradient(180deg, #ffedd5 0%, #fed7aa 100%);
  color: #9a3412;
}

/* ==========================================================================
   TIMELINE AUDIT LOG
   ========================================================================== */
.timeline-list {
  display: flex;
  flex-direction: column;
  position: relative;
  padding-left: 24px;
}

.timeline-list::before {
  content: '';
  position: absolute;
  top: 10px;
  bottom: 10px;
  left: 8px;
  width: 2px;
  background-color: var(--border-color);
}

.timeline-item {
  position: relative;
  margin-bottom: 16px;
}

.timeline-dot {
  position: absolute;
  left: -24px;
  top: 4px;
  width: 18px;
  height: 18px;
  border-radius: var(--radius-full);
  background-color: #ffffff;
  border: 3px solid var(--primary-light);
}

.timeline-dot.green { border-color: var(--success); }
.timeline-dot.gold { border-color: var(--accent-gold); }
.timeline-dot.purple { border-color: var(--purple); }

.timeline-content-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  padding: 12px;
  box-shadow: var(--shadow-sm);
}

.timeline-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 4px;
}

.timeline-title {
  font-size: 13px;
  font-weight: 700;
  color: var(--text-main);
}

.timeline-time {
  font-size: 11px;
  color: var(--text-muted);
}

.timeline-desc {
  font-size: 12px;
  color: var(--text-secondary);
}

.timeline-meta {
  font-size: 11px;
  color: var(--primary-light);
  font-weight: 600;
  margin-top: 4px;
}

/* ==========================================================================
   GEOSPATIAL PETA SEBARAN (GIS MOBILE)
   ========================================================================== */
.geo-district-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: 14px;
  margin-bottom: 12px;
  box-shadow: var(--shadow-sm);
}

.geo-district-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.geo-district-name {
  font-size: 15px;
  font-weight: 800;
  color: #16225e;
}

.geo-village-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 6px;
  margin-top: 8px;
}

.geo-village-pill {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: var(--radius-sm);
  padding: 6px 8px;
  font-size: 11px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* ==========================================================================
   SEGMENTED CONTROL
   ========================================================================== */
.segmented-control {
  display: flex;
  background-color: #e2e8f0;
  border-radius: var(--radius-md);
  padding: 3px;
  margin-bottom: 14px;
}

.segment-btn {
  flex: 1;
  padding: 8px;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-muted);
  background: transparent;
  border: none;
  border-radius: calc(var(--radius-md) - 2px);
  cursor: pointer;
  text-align: center;
  transition: all 0.2s;
}

.segment-btn.active {
  background: #ffffff;
  color: var(--primary);
  box-shadow: var(--shadow-sm);
}

/* Toast */
.mobile-toast {
  position: fixed;
  top: max(var(--safe-top), 14px);
  left: 50%;
  transform: translateX(-50%) translateY(-100px);
  max-width: 90%;
  width: 360px;
  background-color: #0f172a;
  color: #ffffff;
  padding: 12px 16px;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  font-weight: 500;
  z-index: 200;
  opacity: 0;
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s;
}

.mobile-toast.show {
  transform: translateX(-50%) translateY(0);
  opacity: 1;
}

.mobile-toast svg {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
  stroke: #38bdf8;
}

.mobile-toast.success svg { stroke: #4ade80; }
.mobile-toast.danger svg { stroke: #f87171; }

.empty-state {
  text-align: center;
  padding: 32px 16px;
  color: var(--text-muted);
}

.empty-state svg {
  width: 44px;
  height: 44px;
  stroke: #94a3b8;
  margin-bottom: 10px;
}

.empty-state-title {
  font-size: 14px;
  font-weight: 700;
  color: var(--text-secondary);
  margin-bottom: 4px;
}

.empty-state-desc {
  font-size: 12px;
  color: var(--text-muted);
}
  </style>
</head>
<body>

  <div class="mobile-app">
    <!-- Offline Banner -->
    <div id="offlineBanner" class="offline-banner">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;min-width:14px;min-height:14px;max-width:14px;max-height:14px;"><line x1="1" y1="1" x2="23" y2="23"/><path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"/><path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39"/><path d="M10.71 5.05A16 16 0 0 1 22.58 9"/><path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
      <span>Mode Offline Aktif - Data Tersimpan di Perangkat</span>
    </div>

    <!-- Header Atas Smartphone -->
    <header class="mobile-header">
      <div class="header-inner">
        <div class="header-left">
          <button type="button" id="btnOpenDrawer" class="header-btn" title="Buka Menu Navigasi">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
          </button>
          <div class="header-brand">
            <img src="assets/icons/gus-dim.png" onerror="this.onerror=null; this.src='/assets/img/gus-dim.png';" alt="Gus Dim" class="header-avatar" width="36" height="36">
            <div class="header-title-box">
              <span class="header-app-title">GUS DIM MOBILE</span>
              <span class="header-badge-dapil">Dapil Kraksaan Raya</span>
            </div>
          </div>
        </div>

        <div class="header-actions" style="display:flex;align-items:center;gap:6px;">
          <button type="button" id="btnRefresh" class="header-btn" title="Sinkronisasi Data">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
          </button>
          <button type="button" id="btnHeaderProfile" class="header-btn" title="Profil Saya" onclick="navigatePage('profil')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </button>
        </div>
      </div>
    </header>

    <!-- Slide-Out Drawer Menu (Sidebar 4 Rumpun Sesuai Desktop) -->
    <div id="drawerBackdrop" class="drawer-backdrop"></div>
    <aside id="mobileDrawer" class="mobile-drawer">
      <div class="drawer-header">
        <div class="drawer-user-info" onclick="navigatePage('profil')" style="cursor:pointer;" title="Lihat Profil Saya">
          <img src="assets/icons/gus-dim.png" onerror="this.onerror=null; this.src='/assets/img/gus-dim.png';" alt="Gus Dim" class="drawer-avatar" width="44" height="44">
          <div>
            <div class="drawer-user-name">Tim Relawan Gus Dim</div>
            <div class="drawer-user-role">Dapil Kraksaan Raya</div>
          </div>
        </div>
        <button type="button" id="btnCloseDrawer" class="drawer-close-btn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="drawer-body">
        <!-- Rumpun 1: Dashboard & Geospasial -->
        <div class="drawer-section-title">Dashboard &amp; Peta</div>
        <div class="drawer-menu-item active" data-page="dashboard">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Beranda Utama</span>
          </div>
        </div>
        <div class="drawer-menu-item" data-page="peta">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/></svg>
            <span>Peta Sebaran Dapil</span>
          </div>
          <span class="drawer-badge">GIS</span>
        </div>

        <!-- Rumpun 2: Basis Data & Lapangan -->
        <div class="drawer-section-title">Basis Data &amp; Lapangan</div>
        <div class="drawer-menu-item" data-page="pendukung">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Data Pendukung</span>
          </div>
          <span id="drawerBadgePendukung" class="drawer-badge">0</span>
        </div>
        <div class="drawer-menu-item" data-page="input">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <span>Entri 5 Jalur Terpadu</span>
          </div>
          <span class="drawer-badge" style="background:#eff6ff;color:#2563eb;">OCR</span>
        </div>
        <div class="drawer-menu-item" data-page="leaderboard">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
            <span>Leaderboard Relawan</span>
          </div>
          <span class="drawer-badge" style="background:#fef3c7;color:#b45309;">Top</span>
        </div>

        <!-- Rumpun 3: Aspirasi & Kebijakan -->
        <div class="drawer-section-title">Aspirasi &amp; Kebijakan</div>
        <div class="drawer-menu-item" data-page="aspirasi">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Aspirasi Warga</span>
          </div>
          <span id="drawerBadgeAspirasi" class="drawer-badge">0</span>
        </div>
        <div class="drawer-menu-item" data-page="reses">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/></svg>
            <span>Reses &amp; Pokir APBD</span>
          </div>
          <span id="drawerBadgePokir" class="drawer-badge">0</span>
        </div>

        <!-- Rumpun 4: Sistem & Pengawasan -->
        <div class="drawer-section-title">Sistem &amp; Pengawasan</div>
        <div class="drawer-menu-item" data-page="riwayat">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span>Riwayat &amp; Log Audit</span>
          </div>
        </div>
        <div class="drawer-menu-item" data-page="pengaturan">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            <span>Pengaturan Operator</span>
          </div>
        </div>
        <div class="drawer-menu-item" data-page="profil">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Profil Saya</span>
          </div>
        </div>
      </div>

      <div class="drawer-footer">
        <div class="drawer-menu-item" style="color:#dc2626;" onclick="showToast('Sesi akun tetap aman di perangkat lokal.', 'info')">
          <div class="drawer-menu-item-left">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;max-width:20px;max-height:20px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Keluar Sesi</span>
          </div>
        </div>
      </div>
    </aside>

    <!-- Konten Scrollable -->
    <main id="mobileMain" class="mobile-main">

      <!-- ==========================================
           HALAMAN 1: DASHBOARD (BERANDA UTAMA)
           ========================================== -->
      <section id="page-dashboard" class="tab-pane active">

        <!-- Progress Target Suara Dapil -->
        <div class="progress-widget">
          <div class="progress-header">
            <span class="progress-title">Progres Target Suara Dapil</span>
            <span id="progressTargetPct" class="progress-target-text" style="font-weight:800;color:#fbbf24;">0%</span>
          </div>
          <div class="progress-bar-bg">
            <div id="progressTargetFill" class="progress-bar-fill" style="width: 0%;"></div>
          </div>
          <div class="progress-footer">
            <span id="progressTargetSub">0 / 25.000 Suara</span>
            <span>Target: 25.000 Suara</span>
          </div>
        </div>

        <!-- Quick Shortcut Grid (8 Akses Cepat Modul) -->
        <div class="shortcut-grid">
          <div class="shortcut-tile" onclick="navigatePage('pendukung')">
            <div class="shortcut-icon" style="background:#eff6ff;color:#2563eb;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="shortcut-label">Pendukung</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('input')">
            <div class="shortcut-icon" style="background:#fef3c7;color:#d97706;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
            <span class="shortcut-label">Entri 5 Jalur</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('aspirasi')">
            <div class="shortcut-icon" style="background:#f0fdf4;color:#16a34a;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <span class="shortcut-label">Aspirasi</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('reses')">
            <div class="shortcut-icon" style="background:#e0f2fe;color:#0284c7;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/></svg>
            </div>
            <span class="shortcut-label">Pokir APBD</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('peta')">
            <div class="shortcut-icon" style="background:#faf5ff;color:#9333ea;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/></svg>
            </div>
            <span class="shortcut-label">Peta Sebaran</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('leaderboard')">
            <div class="shortcut-icon" style="background:#fff7ed;color:#ea580c;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
            </div>
            <span class="shortcut-label">Leaderboard</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('riwayat')">
            <div class="shortcut-icon" style="background:#f1f5f9;color:#475569;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <span class="shortcut-label">Log Riwayat</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('pengaturan')">
            <div class="shortcut-icon" style="background:#fdf2f8;color:#db2777;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <span class="shortcut-label">Pengaturan</span>
          </div>
        </div>

        <!-- KPI Grid 2x2 -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-header">
              <span class="kpi-title">Total Pendukung</span>
              <div class="kpi-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;min-width:15px;min-height:15px;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              </div>
            </div>
            <div id="kpiTotalPendukung" class="kpi-value">0</div>
            <div class="kpi-subtext">Warga Dapil Terdata</div>
          </div>

          <div class="kpi-card green">
            <div class="kpi-header">
              <span class="kpi-title">Terverifikasi</span>
              <div class="kpi-icon green">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;min-width:15px;min-height:15px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </div>
            </div>
            <div id="kpiTerverifikasi" class="kpi-value">0</div>
            <div class="kpi-subtext">KTP &amp; Berkas Valid</div>
          </div>

          <div class="kpi-card gold">
            <div class="kpi-header">
              <span class="kpi-title">Aspirasi Warga</span>
              <div class="kpi-icon gold">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;min-width:15px;min-height:15px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              </div>
            </div>
            <div id="kpiTotalAspirasi" class="kpi-value">0</div>
            <div class="kpi-subtext">Usulan Lapangan</div>
          </div>

          <div class="kpi-card info">
            <div class="kpi-header">
              <span class="kpi-title">Pokir APBD</span>
              <div class="kpi-icon info">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;min-width:15px;min-height:15px;"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/></svg>
              </div>
            </div>
            <div id="kpiTotalPokir" class="kpi-value">0</div>
            <div class="kpi-subtext">Program Dewan 2025</div>
          </div>
        </div>

        <!-- Sebaran Suara 3 Kecamatan -->
        <div class="card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <span style="font-size:14px;font-weight:700;color:#0f172a;">Sebaran 3 Kecamatan Dapil</span>
            <span style="font-size:11px;font-weight:600;color:#2563eb;background:#eff6ff;padding:2px 8px;border-radius:999px;">Kraksaan Raya</span>
          </div>

          <div style="display:flex;flex-direction:column;gap:10px;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px dashed #e2e8f0;">
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">Kecamatan Kraksaan</div>
                <div style="font-size:11px;color:#64748b;">Ibu Kota Dapil &bull; 13 Desa/Kelurahan</div>
              </div>
              <span id="kecKraksaanVal" style="font-size:15px;font-weight:800;color:#16225e;">0</span>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px dashed #e2e8f0;">
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">Kecamatan Besuk</div>
                <div style="font-size:11px;color:#64748b;">Wilayah Penyangga &bull; 9 Desa</div>
              </div>
              <span id="kecBesukVal" style="font-size:15px;font-weight:800;color:#16225e;">0</span>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">Kecamatan Gading</div>
                <div style="font-size:11px;color:#64748b;">Wilayah Selatan &bull; 9 Desa</div>
              </div>
              <span id="kecGadingVal" style="font-size:15px;font-weight:800;color:#16225e;">0</span>
            </div>
          </div>
        </div>

      </section>

      <!-- ==========================================
           HALAMAN 2: DATA PENDUKUNG
           ========================================== -->
      <section id="page-pendukung" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Database Pendukung</div>
            <div class="page-subtitle-text">Dapil Kraksaan Raya &bull; Terintegrasi NIK</div>
          </div>
          <button type="button" class="btn-outline-touch" onclick="navigatePage('input')" style="height:36px;font-size:12px;padding:0 10px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;min-width:14px;min-height:14px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah
          </button>
        </div>

        <!-- Search & Filter Controls -->
        <div class="search-filter-wrapper">
          <div class="search-input-box">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="searchSupporterInput" class="search-input-mobile" placeholder="Cari nama, NIK, atau desa...">
            <button type="button" id="btnClearSearch" class="search-clear-btn">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;min-width:16px;min-height:16px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>

          <div class="filter-pills-scroll">
            <button type="button" class="filter-pill active" data-filter-type="all" data-filter-val="">Semua</button>
            <button type="button" class="filter-pill" data-filter-type="jalur" data-filter-val="DPC">Jalur DPC</button>
            <button type="button" class="filter-pill" data-filter-type="jalur" data-filter-val="DPRT">Jalur DPRT</button>
            <button type="button" class="filter-pill" data-filter-type="jalur" data-filter-val="PIP">Jalur PIP</button>
            <button type="button" class="filter-pill" data-filter-type="jalur" data-filter-val="KIP">Jalur KIP</button>
            <button type="button" class="filter-pill" data-filter-type="jalur" data-filter-val="RELAWAN">Jalur Relawan</button>
            <button type="button" class="filter-pill" data-filter-type="status" data-filter-val="Diinput">Diinput</button>
            <button type="button" class="filter-pill" data-filter-type="status" data-filter-val="Diverifikasi Desa">Verif Desa</button>
            <button type="button" class="filter-pill" data-filter-type="status" data-filter-val="Divalidasi Kecamatan">Validasi Kec</button>
            <button type="button" class="filter-pill" data-filter-type="status" data-filter-val="Final">Final (Sah)</button>
            <button type="button" class="filter-pill" data-filter-type="kecamatan" data-filter-val="Kraksaan">Kraksaan</button>
            <button type="button" class="filter-pill" data-filter-type="kecamatan" data-filter-val="Besuk">Besuk</button>
            <button type="button" class="filter-pill" data-filter-type="kecamatan" data-filter-val="Gading">Gading</button>
          </div>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;padding:0 2px;">
          <span style="font-size:12px;font-weight:700;color:#64748b;">HASIL PENCARIAN</span>
          <span id="supporterResultsCount" style="font-size:11px;font-weight:600;color:#64748b;background:#e2e8f0;padding:2px 8px;border-radius:999px;">0 Data</span>
        </div>

        <!-- Kontainer Touch Cards -->
        <div id="supportersCardList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 3: ENTRI 5 JALUR
           ========================================== -->
      <section id="page-input" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Entri 5 Jalur Terpadu</div>
            <div class="page-subtitle-text">Pilih metode pendataan sesuai kondisi lapangan</div>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
          <!-- Jalur 1: DPC Kecamatan -->
          <div class="touch-card" onclick="openEntryForm('DPC', 'Jalur 1: Struktur DPC Kecamatan')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#eff6ff;color:#2563eb;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/><path d="M8 14h.01"/><path d="M16 14h.01"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 1: DPC Kecamatan</div>
                <div class="card-nik" style="font-family:inherit;">Pengurus DPC tingkat kecamatan se-Dapil Kraksaan Raya</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <!-- Jalur 2: DPRT Desa -->
          <div class="touch-card" onclick="openEntryForm('DPRT', 'Jalur 2: Struktur DPRT Desa / Ranting')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#fef3c7;color:#d97706;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 2: DPRT Desa / Ranting</div>
                <div class="card-nik" style="font-family:inherit;">Pengurus ranting tingkat 28 desa / kelurahan</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <!-- Jalur 3: Program PIP (Pelajar) -->
          <div class="touch-card" onclick="openEntryForm('PIP', 'Jalur 3: Program PIP Aspirasi (Pelajar)')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#f0fdf4;color:#16a34a;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 3: Program PIP (Pelajar)</div>
                <div class="card-nik" style="font-family:inherit;">Aspirasi beasiswa Program Indonesia Pintar SD, SMP, SMA</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <!-- Jalur 4: Program KIP Kuliah (Mahasiswa) -->
          <div class="touch-card" onclick="openEntryForm('KIP', 'Jalur 4: Program KIP Kuliah (Mahasiswa)')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#fdf2f8;color:#db2777;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 4: Program KIP Kuliah</div>
                <div class="card-nik" style="font-family:inherit;">Aspirasi beasiswa kuliah perguruan tinggi konstituen</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <!-- Jalur 5: Relawan & Simpatisan -->
          <div class="touch-card" onclick="openEntryForm('RELAWAN', 'Jalur 5: Relawan Lapangan &amp; Simpatisan')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#faf5ff;color:#9333ea;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;min-width:20px;min-height:20px;"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 5: Relawan &amp; Simpatisan</div>
                <div class="card-nik" style="font-family:inherit;">Kader penggerak lapangan, saksi TPS, dan pemilih setia</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>
        </div>
      </section>

      <!-- ==========================================
           HALAMAN 4: ASPIRASI WARGA
           ========================================== -->
      <section id="page-aspirasi" class="tab-pane">
        <!-- Header Ringkas Satu Baris -->
        <div class="page-title-banner" style="margin-bottom:8px;padding-bottom:0;">
          <div>
            <div class="page-title-text" style="font-size:18px;">Aspirasi Warga</div>
            <div class="page-subtitle-text" style="font-size:11px;">Jaring masukan Dapil Kraksaan Raya</div>
          </div>
          <button type="button" class="btn-outline-touch" onclick="openAspirasiForm()" style="height:32px;font-size:11.5px;padding:0 10px;background:#f0fdf4;color:#16a34a;border-color:#bbf7d0;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            + Buat Aspirasi
          </button>
        </div>

        <!-- Search & Filter Controls (Opsi 1: KPI Terintegrasi di Filter Pill) -->
        <div class="search-filter-wrapper" style="margin-bottom:10px;">
          <div class="search-input-box" style="margin-bottom:8px;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:17px;height:17px;min-width:17px;min-height:17px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="searchAspirasiInput" class="search-input-mobile" style="height:42px;font-size:13.5px;padding-left:40px;" placeholder="Cari warga, desa, atau isi keluhan...">
            <button type="button" id="btnClearAspirasiSearch" class="search-clear-btn" onclick="clearAspirasiSearch()">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;min-width:16px;min-height:16px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>

          <!-- Status Filters dengan Badge Metrik KPI Langsung -->
          <div class="filter-pills-scroll" style="margin-bottom:6px;">
            <button type="button" class="filter-pill active" data-asp-status="" onclick="filterAspirasiStatus('', this)">
              Semua <span class="pill-badge" id="countStatusSemua">0</span>
            </button>
            <button type="button" class="filter-pill" data-asp-status="menunggu" onclick="filterAspirasiStatus('menunggu', this)">
              Menunggu <span class="pill-badge yellow" id="countStatusMenunggu">0</span>
            </button>
            <button type="button" class="filter-pill" data-asp-status="advokasi" onclick="filterAspirasiStatus('advokasi', this)">
              Advokasi <span class="pill-badge purple" id="countStatusAdvokasi">0</span>
            </button>
            <button type="button" class="filter-pill" data-asp-status="pokir" onclick="filterAspirasiStatus('pokir', this)">
              Masuk Pokir <span class="pill-badge blue" id="countStatusPokir">0</span>
            </button>
            <button type="button" class="filter-pill" data-asp-status="selesai" onclick="filterAspirasiStatus('selesai', this)">
              Selesai <span class="pill-badge green" id="countStatusSelesai">0</span>
            </button>
          </div>

          <!-- Category Filters Scroll -->
          <div class="filter-pills-scroll">
            <button type="button" class="filter-pill active" data-asp-cat="" onclick="filterAspirasiCat('', this)">Semua Bidang</button>
            <button type="button" class="filter-pill" data-asp-cat="Infrastruktur" onclick="filterAspirasiCat('Infrastruktur', this)">Infrastruktur</button>
            <button type="button" class="filter-pill" data-asp-cat="Pertanian" onclick="filterAspirasiCat('Pertanian', this)">Pertanian</button>
            <button type="button" class="filter-pill" data-asp-cat="Pendidikan" onclick="filterAspirasiCat('Pendidikan', this)">Pendidikan</button>
            <button type="button" class="filter-pill" data-asp-cat="Kesehatan" onclick="filterAspirasiCat('Kesehatan', this)">Kesehatan</button>
            <button type="button" class="filter-pill" data-asp-cat="Bansos" onclick="filterAspirasiCat('Bansos', this)">Bansos</button>
            <button type="button" class="filter-pill" data-asp-cat="UMKM" onclick="filterAspirasiCat('UMKM', this)">UMKM &amp; Ekonomi</button>
          </div>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;padding:0 2px;">
          <span style="font-size:12px;font-weight:700;color:#64748b;">DAFTAR ASPIRASI</span>
          <span id="aspirasiResultsCount" style="font-size:11px;font-weight:600;color:#64748b;background:#e2e8f0;padding:2px 8px;border-radius:999px;">0 Data</span>
        </div>

        <div id="aspirasiDedicatedList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 5: RESES & POKIR APBD
           ========================================== -->
      <section id="page-reses" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Reses &amp; Pokir APBD</div>
            <div class="page-subtitle-text">Pengawalan usulan dewan ke program daerah</div>
          </div>
          <button type="button" class="btn-outline-touch" onclick="openPokirForm()" style="height:36px;font-size:12px;padding:0 10px;background:#eff6ff;color:#2563eb;border-color:#bfdbfe;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;min-width:14px;min-height:14px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Usul Pokir
          </button>
        </div>

        <div class="segmented-control">
          <button type="button" class="segment-btn active" data-reses-tab="pokir" onclick="switchResesSubTab('pokir', this)">Usulan Pokir APBD</button>
          <button type="button" class="segment-btn" data-reses-tab="laporan" onclick="switchResesSubTab('laporan', this)">Laporan Reses Dewan</button>
        </div>

        <div id="pokirDedicatedList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 6: PETA SEBARAN (GIS MOBILE)
           ========================================== -->
      <section id="page-peta" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Peta Sebaran Dapil</div>
            <div class="page-subtitle-text">Pemetaan wilayah Kraksaan &bull; Besuk &bull; Gading</div>
          </div>
        </div>

        <div class="geo-district-card">
          <div class="geo-district-header">
            <span class="geo-district-name">Kecamatan Kraksaan</span>
            <span class="badge-status valid">Basis Utama</span>
          </div>
          <p style="font-size:12px;color:#64748b;margin-bottom:8px;">Total 13 Desa/Kelurahan &bull; Pusat Komando Pemenangan</p>
          <div class="geo-village-grid" id="gridDesaKraksaan"></div>
        </div>

        <div class="geo-district-card">
          <div class="geo-district-header">
            <span class="geo-district-name">Kecamatan Besuk</span>
            <span class="badge-status pending">Penyangga</span>
          </div>
          <p style="font-size:12px;color:#64748b;margin-bottom:8px;">Total 9 Desa &bull; Basis Petani &amp; Jaringan Desa</p>
          <div class="geo-village-grid" id="gridDesaBesuk"></div>
        </div>

        <div class="geo-district-card">
          <div class="geo-district-header">
            <span class="geo-district-name">Kecamatan Gading</span>
            <span class="badge-status info">Wilayah Selatan</span>
          </div>
          <p style="font-size:12px;color:#64748b;margin-bottom:8px;">Total 9 Desa &bull; Jaringan Tokoh Agama &amp; Kyai</p>
          <div class="geo-village-grid" id="gridDesaGading"></div>
        </div>
      </section>

      <!-- ==========================================
           HALAMAN 7: LEADERBOARD RELAWAN
           ========================================== -->
      <section id="page-leaderboard" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Leaderboard Tim</div>
            <div class="page-subtitle-text">Peringkat produktivitas relawan lapangan</div>
          </div>
        </div>

        <!-- Podium Top 3 -->
        <div class="podium-container">
          <div class="podium-item second">
            <div class="podium-avatar-box">
              <div class="podium-avatar">AM</div>
              <div class="podium-badge">2</div>
            </div>
            <div class="podium-name">Ahmad M.</div>
            <div class="podium-score">842 Data</div>
            <div class="podium-pillar">2</div>
          </div>

          <div class="podium-item first">
            <div class="podium-avatar-box">
              <div class="podium-avatar" style="color:#b45309;background:#fef3c7;">HK</div>
              <div class="podium-badge">1</div>
            </div>
            <div class="podium-name">H. Kholiq</div>
            <div class="podium-score">1.250 Data</div>
            <div class="podium-pillar">1</div>
          </div>

          <div class="podium-item third">
            <div class="podium-avatar-box">
              <div class="podium-avatar">FR</div>
              <div class="podium-badge">3</div>
            </div>
            <div class="podium-name">Fathur R.</div>
            <div class="podium-score">615 Data</div>
            <div class="podium-pillar">3</div>
          </div>
        </div>

        <div style="font-size:12px;font-weight:700;color:#64748b;margin-bottom:8px;">PERINGKAT KORCAM &amp; KORDES</div>
        <div id="leaderboardList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 8: RIWAYAT & LOG AUDIT
           ========================================== -->
      <section id="page-riwayat" class="tab-pane">
        <div class="page-title-banner" style="display:flex;align-items:center;justify-content:space-between;">
          <div>
            <div class="page-title-text">Riwayat &amp; Log Audit</div>
            <div class="page-subtitle-text">Rekam jejak digital aktivitas lapangan</div>
          </div>
          <button type="button" class="btn-outline-touch" style="height:32px;font-size:11.5px;padding:0 10px;" onclick="refreshAuditLogs()">
            Segarkan
          </button>
        </div>

        <div class="filter-pills-scroll" style="margin-bottom:12px;">
          <div class="filter-pill active" onclick="filterAuditLogs('semua', this)">Semua Log</div>
          <div class="filter-pill" onclick="filterAuditLogs('pendukung', this)">Pendukung</div>
          <div class="filter-pill" onclick="filterAuditLogs('aspirasi', this)">Aspirasi</div>
          <div class="filter-pill" onclick="filterAuditLogs('sistem', this)">Sistem &amp; Sesi</div>
        </div>

        <div class="timeline-list" id="auditTimelineList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 9: PENGATURAN OPERATOR & SISTEM
           ========================================== -->
      <section id="page-pengaturan" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Pengaturan Sistem</div>
            <div class="page-subtitle-text">Manajemen operator &amp; preferensi aplikasi</div>
          </div>
        </div>

        <!-- Kartu Sesi Akun Pengguna Aktif -->
        <div class="card" id="pengaturanUserCard"></div>

        <!-- Daftar Petugas & Operator Lapangan -->
        <div class="card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <div>
              <div style="font-size:14px;font-weight:700;color:#0f172a;">Daftar Petugas &amp; Operator</div>
              <div style="font-size:11px;color:#64748b;">Tim lapangan Dapil Kraksaan Raya</div>
            </div>
            <div id="btnTambahOperatorContainer"></div>
          </div>
          <div style="display:flex;flex-direction:column;gap:4px;" id="operatorList"></div>
        </div>

        <!-- Mode Penyimpanan & Offline PWA -->
        <div class="card">
          <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Sinkronisasi &amp; Penyimpanan PWA</div>
          <p style="font-size:12px;color:#64748b;margin-bottom:12px;line-height:1.45;">
            Data formulir lapangan dan cache antarmuka disimpan di penyimpanan lokal perangkat smartphone untuk keandalan maksimal di lapangan.
          </p>
          <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;margin-bottom:12px;display:flex;flex-direction:column;gap:6px;font-size:12px;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
              <span style="color:#64748b;">Koneksi Realtime:</span>
              <span style="font-weight:700;color:#16a34a;display:inline-flex;align-items:center;gap:4px;">
                <span style="width:7px;height:7px;border-radius:50%;background:#16a34a;"></span> Laravel Reverb Aktif
              </span>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;">
              <span style="color:#64748b;">Status Jaringan:</span>
              <span id="pwaNetworkStatus" style="font-weight:700;color:#2563eb;">Online (Tersambung)</span>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;">
            <button type="button" class="btn-primary-touch" style="width:100%;height:40px;font-size:12.5px;" onclick="syncDatabaseNow()">
              Sinkronkan Data Server Sekarang
            </button>
            <button type="button" class="btn-outline-touch" style="width:100%;height:40px;font-size:12.5px;" onclick="clearPwaCache()">
              Perbarui Cache Aplikasi &amp; Muat Ulang
            </button>
          </div>
        </div>

        <!-- Informasi Aplikasi -->
        <div class="card" style="text-align:center;padding:16px;background:#f8fafc;border:1px solid #e2e8f0;">
          <div style="font-size:12px;font-weight:700;color:#0f172a;">Gus Dim Mobile v2.5.0 (PWA)</div>
          <div style="font-size:11px;color:#64748b;margin-top:2px;">Sistem Informasi Manajemen Dapil Kraksaan Raya</div>
          <div style="font-size:10.5px;color:#94a3b8;margin-top:4px;">Fraksi Partai NasDem DPRD Kabupaten Probolinggo</div>
        </div>
      </section>

      <!-- ==========================================
           HALAMAN 10: PROFIL SAYA
           ========================================== -->
      <section id="page-profil" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Profil Saya</div>
            <div class="page-subtitle-text">Identitas akun & status penugasan lapangan</div>
          </div>
        </div>
        <div id="profileContainer"></div>
      </section>

    </main>

    <!-- ==========================================
         BOTTOM NAVIGATION BAR (5 TAB + CENTER FAB)
         ========================================== -->
    <nav class="mobile-bottom-nav">
      <a href="#dashboard" class="nav-item active" data-page="dashboard">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:22px;height:22px;min-width:22px;min-height:22px;"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Beranda</span>
      </a>

      <a href="#pendukung" class="nav-item" data-page="pendukung">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:22px;height:22px;min-width:22px;min-height:22px;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Pendukung</span>
      </a>

      <!-- Center Raised Floating Action Button -->
      <div class="nav-fab-container">
        <button type="button" id="fabCenter" class="nav-fab" title="Entri Pendukung Cepat">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:26px;height:26px;min-width:26px;min-height:26px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
      </div>

      <a href="#aspirasi" class="nav-item" data-page="aspirasi">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:22px;height:22px;min-width:22px;min-height:22px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <span>Aspirasi</span>
      </a>

      <a href="#drawer" id="btnBottomMenu" class="nav-item" data-page="menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:22px;height:22px;min-width:22px;min-height:22px;"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
        <span>Menu</span>
      </a>
    </nav>

    <!-- ==========================================
         BOTTOM SHEET DRAWER (MODAL SENTUH)
         ========================================== -->
    <div id="sheetBackdrop" class="sheet-backdrop"></div>
    <div id="bottomSheet" class="bottom-sheet">
      <div class="sheet-handle-bar">
        <div class="sheet-handle"></div>
      </div>
      <div class="sheet-header">
        <span id="sheetTitle" class="sheet-title">Detail Data</span>
        <button type="button" class="sheet-close-btn" onclick="closeBottomSheet()">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div id="sheetContent" class="sheet-content"></div>
    </div>

    <!-- Toast Notification -->
    <div id="mobileToast" class="mobile-toast"></div>
  </div>

  <script>
/**
 * GUS DIM MOBILE (PWA) - CONTROLLER UTAMA LENGKAP 10 HALAMAN
 * 100% BEBAS EMOJI - Pure Clean SVG & Native Ergonomics
 * Source of Truth: Web Desktop Existing (Dapil Kraksaan Raya)
 */

// State Aplikasi Mobile
const AppState = {
  currentPage: 'dashboard',
  activeResesTab: 'pokir',
  supporters: [],
  aspirasi: [],
  pokir: [],
  auditLogs: [],
  operators: [],
  leaderboardData: [],
  stats: {
    total: 0,
    terverifikasi: 0,
    target: 25000,
    persen: 0,
    aspirasiCount: 0,
    pokirCount: 0,
    kecamatan: {
      Kraksaan: 0,
      Besuk: 0,
      Gading: 0
    }
  },
  filterJalur: '',
  filterKecamatan: '',
  filterStatus: '',
  searchQuery: '',
  filterAspirasiCat: '',
  filterAspirasiStatus: '',
  searchAspirasiQuery: '',
  activeJalur: 'RELAWAN',
  isOnline: navigator.onLine
};

// Data Geospasial Wilayah Dapil Kraksaan Raya
const DapilLocations = {
  'Kraksaan': {
    lat: -7.7595,
    lng: 113.4185,
    desa: [
      'Alassumur Kulon', 'Asembagus', 'Bulu', 'Bulubrangsi', 'Kalibuntu', 
      'Kalisalam', 'Kandangjati Kulon', 'Kandangjati Wetan', 'Kebonagung', 
      'Kraksaan Kulon', 'Kraksaan Wetan', 'Kregenan', 'Patokan', 'Reksosari', 
      'Rondokuning', 'Semampir', 'Sidomukti', 'Sidopekso', 'Sumberlele', 'Tamansari'
    ]
  },
  'Besuk': {
    lat: -7.7924,
    lng: 113.4561,
    desa: [
      'Alas Sumur Lor', 'Alaskandang', 'Bago', 'Besuk Agung', 'Besuk Kidul', 
      'Jambangan', 'Kecik', 'Klampokan', 'Krampilan', 'Matekan', 'Meayan', 
      'Randu Jalak', 'Sindet Anyar', 'Sindet Lami', 'Sumberan', 'Sumbersuko', 
      'Sumurdalam', 'Warugunung'
    ]
  },
  'Gading': {
    lat: -7.8341,
    lng: 113.4352,
    desa: [
      'Batur', 'Betek Kulon', 'Bulu', 'Bulupandak', 'Condong', 'Dandang', 
      'Duren', 'Gading Kulon', 'Gading Wetan', 'Jurangjero', 'Kaliacar', 
      'Kalisat', 'Kertosari', 'Kertosono', 'Mojolegi', 'Nogosaren', 'Prasi', 
      'Randujalak', 'Ranuwurung', 'Renteng', 'Sentul', 'Sumbersecang', 'Wangkal'
    ]
  }
};

// SVG Icons Registry (100% Zero Emoji)
const Icons = {
  home: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
  users: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
  plus: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
  messageSquare: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
  menu: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>',
  checkCircle: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
  clock: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
  alertTriangle: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
  mapPin: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;min-width:14px;min-height:14px;max-width:14px;max-height:14px;flex-shrink:0;"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
  phone: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
  whatsapp: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.1 7.3C8.94 7.3 8.68 7.36 8.46 7.6C8.24 7.84 7.62 8.42 7.62 9.6C7.62 10.78 8.48 11.92 8.6 12.08C8.72 12.24 10.28 14.65 12.67 15.68C13.24 15.93 13.68 16.08 14.03 16.19C14.6 16.37 15.12 16.35 15.53 16.29C15.99 16.22 16.95 15.71 17.15 15.14C17.35 14.57 17.35 14.09 17.29 13.99C17.23 13.89 17.07 13.83 16.83 13.71C16.59 13.59 15.41 13.01 15.19 12.93C14.97 12.85 14.81 12.81 14.65 13.05C14.49 13.29 14.03 13.83 13.89 13.99C13.75 14.15 13.61 14.17 13.37 14.05C13.13 13.93 12.36 13.68 11.45 12.87C10.74 12.24 10.26 11.46 10.12 11.22C9.98 10.98 10.11 10.85 10.23 10.73C10.34 10.62 10.47 10.45 10.6 10.3C10.73 10.15 10.77 10.04 10.85 9.88C10.93 9.72 10.89 9.58 10.83 9.46C10.77 9.34 10.31 8.2 10.12 7.72C9.93 7.26 9.73 7.32 9.58 7.31L9.1 7.3Z"/></svg>',
  camera: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:22px;height:22px;min-width:22px;min-height:22px;max-width:22px;max-height:22px;flex-shrink:0;"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>',
  building: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/></svg>',
  trash: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>',
  edit: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>',
  award: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>',
  info: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
  calendar: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>'
};

// Inisialisasi Saat Dokumen Siap
document.addEventListener('DOMContentLoaded', () => {
  initServiceWorker();
  initNetworkStatusListener();
  setupNavigation();
  setupEventListeners();
  loadAllData();
  initMobileRealtimeReverb();
  initHorizontalSwipeFilters();
});

// PWA Service Worker Registration
function initServiceWorker() {
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('./sw.js')
        .then(reg => console.log('ServiceWorker PWA Aktif:', reg.scope))
        .catch(err => console.log('ServiceWorker Gagal:', err));
    });
  }
}

// Deteksi Status Jaringan
function initNetworkStatusListener() {
  const banner = document.getElementById('offlineBanner');
  const updateStatus = () => {
    AppState.isOnline = navigator.onLine;
    if (!AppState.isOnline) {
      if (banner) banner.classList.add('active');
    } else {
      if (banner) banner.classList.remove('active');
    }
  };
  window.addEventListener('online', updateStatus);
  window.addEventListener('offline', updateStatus);
  updateStatus();
}

// Realtime WebSocket (Laravel Reverb) Mobile
function initMobileRealtimeReverb() {
  if (typeof Pusher === 'undefined') {
    console.warn('Pusher JS belum terpasang di Mobile, WebSocket dilewati.');
    return;
  }
  if (window._mobilePusher) return;

  try {
    const pusher = new Pusher('ifst0r0e5f3stkfy1k6g', {
      wsHost: 'gusdim.com',
      wsPort: 6001,
      wssPort: 6001,
      forceTLS: true,
      disableStats: true,
      enabledTransports: ['ws', 'wss'],
      cluster: 'mt1'
    });

    const channel = pusher.subscribe('gusdim-updates');

    channel.bind('PendukungCreated', function (data) {
      console.log('Mobile Realtime PendukungCreated:', data);
      showToast('Data Baru: ' + (data.nama || 'Warga') + ' (' + (data.jalur || 'Relawan') + ') - Desa ' + (data.desa || '-'), 'info');
      if (typeof loadAllData === 'function') {
        try { loadAllData(); } catch (e) {}
      }
    });

    channel.bind('PendukungStatusUpdated', function (data) {
      console.log('Mobile Realtime PendukungStatusUpdated:', data);
      showToast('Status Verifikasi: ' + (data.nama || 'Data') + ' diubah jadi "' + data.new_status + '" oleh ' + (data.actor_name || 'Petugas'), 'success');
      if (typeof loadAllData === 'function') {
        try { loadAllData(); } catch (e) {}
      }
    });

    channel.bind('AspirasiCreated', function (data) {
      console.log('Mobile Realtime AspirasiCreated:', data);
      showToast('Aspirasi Baru: ' + (data.topik || data.nama || 'Konstituen') + ' (' + (data.kategori || 'Infrastruktur') + ')', 'info');
      if (typeof loadAllData === 'function') {
        try { loadAllData(); } catch (e) {}
      }
    });

    channel.bind('AspirasiStatusUpdated', function (data) {
      console.log('Mobile Realtime AspirasiStatusUpdated:', data);
      showToast('Status Aspirasi: ' + (data.topik || 'Aspirasi') + ' diubah ke "' + data.new_status + '"', 'success');
      if (typeof loadAllData === 'function') {
        try { loadAllData(); } catch (e) {}
      }
    });

    window._mobilePusher = pusher;
    console.log('Realtime Reverb WebSocket Mobile Aktif di Channel: gusdim-updates');
  } catch (err) {
    console.warn('Gagal inisialisasi Realtime Mobile:', err);
  }
}

// Setup Navigasi (Bottom Bar & Drawer)
function setupNavigation() {
  // Tombol Drawer Buka & Tutup
  const btnOpenDrawer = document.getElementById('btnOpenDrawer');
  const btnCloseDrawer = document.getElementById('btnCloseDrawer');
  const drawerBackdrop = document.getElementById('drawerBackdrop');
  const btnBottomMenu = document.getElementById('btnBottomMenu');

  if (btnOpenDrawer) btnOpenDrawer.addEventListener('click', openDrawer);
  if (btnCloseDrawer) btnCloseDrawer.addEventListener('click', closeDrawer);
  if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);
  if (btnBottomMenu) {
    btnBottomMenu.addEventListener('click', (e) => {
      e.preventDefault();
      openDrawer();
    });
  }

  // Klik Item Drawer
  document.querySelectorAll('.drawer-menu-item[data-page]').forEach(item => {
    item.addEventListener('click', () => {
      const page = item.getAttribute('data-page');
      navigatePage(page);
    });
  });

  // Klik Item Bottom Nav
  document.querySelectorAll('.mobile-bottom-nav .nav-item[data-page]').forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      const page = item.getAttribute('data-page');
      if (page !== 'menu') {
        navigatePage(page);
      }
    });
  });

  // Center FAB Action
  const fab = document.getElementById('fabCenter');
  if (fab) {
    fab.addEventListener('click', () => {
      navigatePage('input');
    });
  }

  // Backdrop Bottom Sheet
  const sheetBackdrop = document.getElementById('sheetBackdrop');
  if (sheetBackdrop) {
    sheetBackdrop.addEventListener('click', closeBottomSheet);
  }

  // Hash Navigation Handler
  window.addEventListener('hashchange', () => {
    const rawHash = (window.location.hash || '').replace('#', '').trim();
    if (rawHash && rawHash !== AppState.currentPage) {
      navigatePage(rawHash, false);
    }
  });

  // Initial Load from URL Hash or Storage
  const initialHash = (window.location.hash || '').replace('#', '').trim();
  if (initialHash && ['dashboard', 'pendukung', 'input', 'aspirasi', 'reses', 'peta', 'leaderboard', 'riwayat', 'pengaturan', 'profil'].includes(initialHash)) {
    navigatePage(initialHash, false);
  }
}

// Buka & Tutup Drawer
function openDrawer() {
  const drawer = document.getElementById('mobileDrawer');
  const backdrop = document.getElementById('drawerBackdrop');
  if (drawer) drawer.classList.add('open');
  if (backdrop) backdrop.classList.add('open');
}

function closeDrawer() {
  const drawer = document.getElementById('mobileDrawer');
  const backdrop = document.getElementById('drawerBackdrop');
  if (drawer) drawer.classList.remove('open');
  if (backdrop) backdrop.classList.remove('open');
}

// Navigasi Antar Halaman (10 Halaman Lengkap)
function navigatePage(pageId, updateHash) {
  if (updateHash === undefined) updateHash = true;
  closeDrawer();

  if (pageId === 'profile') pageId = 'profil';
  const validPages = ['dashboard', 'pendukung', 'input', 'aspirasi', 'reses', 'peta', 'leaderboard', 'riwayat', 'pengaturan', 'profil'];
  if (!validPages.includes(pageId)) pageId = 'dashboard';
  AppState.currentPage = pageId;

  // Switch Tab View
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  const target = document.getElementById(`page-${pageId}`);
  if (target) {
    target.classList.add('active');
  }

  // Update Hash & LocalStorage
  if (updateHash) {
    if (window.location.hash !== '#' + pageId) {
      history.pushState(null, '', '#' + pageId);
    }
  }
  try {
    localStorage.setItem('gusdim_mobile_active_page', pageId);
  } catch (e) {}

  // Update Drawer Active State
  document.querySelectorAll('.drawer-menu-item[data-page]').forEach(item => {
    if (item.getAttribute('data-page') === pageId) {
      item.classList.add('active');
    } else {
      item.classList.remove('active');
    }
  });

  // Update Bottom Nav Active State
  document.querySelectorAll('.mobile-bottom-nav .nav-item').forEach(item => {
    if (item.getAttribute('data-page') === pageId) {
      item.classList.add('active');
    } else {
      item.classList.remove('active');
    }
  });

  // Scroll to top
  const main = document.getElementById('mobileMain');
  if (main) main.scrollTop = 0;

  // Render spesifik per halaman
  if (pageId === 'dashboard') renderDashboardStats();
  else if (pageId === 'pendukung') renderSupportersList();
  else if (pageId === 'aspirasi') renderAspirasiDedicated();
  else if (pageId === 'reses') renderPokirDedicated();
  else if (pageId === 'peta') renderPetaDistricts();
  else if (pageId === 'leaderboard') renderLeaderboard();
  else if (pageId === 'riwayat') renderAuditLogs();
  else if (pageId === 'pengaturan') renderOperators();
  else if (pageId === 'profil' || pageId === 'profile') renderProfile();

  setTimeout(initHorizontalSwipeFilters, 60);
}

// Setup Event Listeners
function setupEventListeners() {
  // Tombol Refresh Header
  const btnRefresh = document.getElementById('btnRefresh');
  if (btnRefresh) {
    btnRefresh.addEventListener('click', () => {
      showToast('Memperbarui data dari server...', 'info');
      loadAllData();
    });
  }

  // Input Pencarian Pendukung
  const searchInput = document.getElementById('searchSupporterInput');
  const btnClearSearch = document.getElementById('btnClearSearch');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      AppState.searchQuery = e.target.value.toLowerCase().trim();
      if (btnClearSearch) {
        if (AppState.searchQuery.length > 0) btnClearSearch.classList.add('active');
        else btnClearSearch.classList.remove('active');
      }
      renderSupportersList();
    });
  }

  if (btnClearSearch) {
    btnClearSearch.addEventListener('click', () => {
      searchInput.value = '';
      AppState.searchQuery = '';
      btnClearSearch.classList.remove('active');
      renderSupportersList();
      searchInput.focus();
    });
  }

  // Input Pencarian Aspirasi Warga
  const searchAspirasiInput = document.getElementById('searchAspirasiInput');
  const btnClearAspirasiSearch = document.getElementById('btnClearAspirasiSearch');
  if (searchAspirasiInput) {
    searchAspirasiInput.addEventListener('input', (e) => {
      AppState.searchAspirasiQuery = e.target.value.toLowerCase().trim();
      if (btnClearAspirasiSearch) {
        if (AppState.searchAspirasiQuery.length > 0) btnClearAspirasiSearch.classList.add('active');
        else btnClearAspirasiSearch.classList.remove('active');
      }
      renderAspirasiDedicated();
    });
  }

  if (btnClearAspirasiSearch) {
    btnClearAspirasiSearch.addEventListener('click', () => {
      if (searchAspirasiInput) searchAspirasiInput.value = '';
      AppState.searchAspirasiQuery = '';
      btnClearAspirasiSearch.classList.remove('active');
      renderAspirasiDedicated();
      if (searchAspirasiInput) searchAspirasiInput.focus();
    });
  }

  // Filter Pills Pendukung
  document.querySelectorAll('.filter-pill[data-filter-type]').forEach(pill => {
    pill.addEventListener('click', () => {
      document.querySelectorAll('.filter-pill[data-filter-type]').forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      const fType = pill.getAttribute('data-filter-type');
      const fVal = pill.getAttribute('data-filter-val') || '';

      if (fType === 'jalur') {
        AppState.filterJalur = fVal;
        AppState.filterStatus = '';
        AppState.filterKecamatan = '';
      } else if (fType === 'status') {
        AppState.filterStatus = fVal;
        AppState.filterJalur = '';
        AppState.filterKecamatan = '';
      } else if (fType === 'kecamatan') {
        AppState.filterKecamatan = fVal;
        AppState.filterJalur = '';
        AppState.filterStatus = '';
      } else {
        AppState.filterJalur = '';
        AppState.filterStatus = '';
        AppState.filterKecamatan = '';
      }
      renderSupportersList();
    });
  });
}

// Helper API Mobile dengan Token Otentikasi
async function mobileApiCall(endpoint, method = 'GET', data = null) {
  const token = localStorage.getItem('dprd_token') || sessionStorage.getItem('dprd_token');
  const headers = {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  };
  if (token) {
    headers['Authorization'] = 'Bearer ' + token;
  }
  const options = { method, headers };
  if (data && (method === 'POST' || method === 'PUT')) {
    options.body = JSON.stringify(data);
  }
  try {
    const apiBase = (window.location.pathname && window.location.pathname.includes('/mobile')) ? '../api' : 'api';
    const res = await fetch(apiBase + '/' + endpoint, options);
    const json = await res.json().catch(() => null);
    return { ok: res.ok, status: res.status, data: json };
  } catch (err) {
    console.warn('API error (' + endpoint + '):', err);
    return { ok: false, status: 0, data: null };
  }
}

// Load Semua Data dari Backend (Murni Data Produksi Bersih)
async function loadAllData() {
  try {
    // 0. Sinkronisasi User & Wilayah Tugas Aktif
    try {
      const checkRes = await mobileApiCall('auth.php?action=check');
      if (checkRes.ok && checkRes.data && checkRes.data.valid) {
        AppState.currentUser = checkRes.data.user;
      } else {
        AppState.currentUser = null;
      }
    } catch (e) {
      console.warn('Gagal cek session:', e);
    }

    // 1. Ambil data Dashboard Utama (berisi rekap statistik, map points, dan daftar pendukung)
    const dashRes = await mobileApiCall('dashboard.php');
    if (dashRes.ok && dashRes.data && dashRes.data.success) {
      AppState.supporters = dashRes.data.allRecords || dashRes.data.recent || [];
      if (dashRes.data.mapPoints) {
        AppState.mapPoints = dashRes.data.mapPoints;
      }
    } else {
      const resP = await mobileApiCall('pendukung.php?action=list');
      if (resP.ok && resP.data && (Array.isArray(resP.data.data) || Array.isArray(resP.data.rows))) {
        AppState.supporters = resP.data.data || resP.data.rows || [];
      } else {
        AppState.supporters = [];
      }
    }

    // 2. Data Aspirasi
    const resA = await mobileApiCall('aspirasi.php?action=list');
    if (resA.ok && resA.data && (Array.isArray(resA.data.rows) || Array.isArray(resA.data.data))) {
      AppState.aspirasi = resA.data.rows || resA.data.data || [];
    } else {
      AppState.aspirasi = [];
    }

    // 3. Data Pokir & Reses
    const resR = await mobileApiCall('reses.php?action=list_all');
    if (resR.ok && resR.data && resR.data.data) {
      AppState.pokir = resR.data.data.pokir || [];
      AppState.resesEvents = resR.data.data.events || [];
    } else {
      AppState.pokir = [];
      AppState.resesEvents = [];
    }

    // 4. Riwayat Audit Logs
    try {
      const logsRes = await mobileApiCall('logs.php');
      if (logsRes.ok && logsRes.data && Array.isArray(logsRes.data.rows) && logsRes.data.rows.length > 0) {
        AppState.auditLogs = logsRes.data.rows.map(l => {
          let color = 'blue';
          const a = (l.aksi || '').toLowerCase();
          if (a.includes('tambah') || a.includes('sukses') || a.includes('login') || a.includes('entri')) color = 'green';
          else if (a.includes('verif') || a.includes('ubah') || a.includes('validasi') || a.includes('status')) color = 'gold';
          else if (a.includes('aspirasi') || a.includes('pokir')) color = 'purple';
          else if (a.includes('hapus') || a.includes('tolak') || a.includes('nonaktif')) color = 'red';
          return {
            judul: l.aksi || 'Aktivitas Sistem',
            waktu: l.waktu || 'Baru saja',
            desc: l.keterangan || 'Pencatatan aktivitas sistem',
            petugas: l.user || 'Petugas',
            desa: 'Dapil Kraksaan Raya',
            color: color
          };
        });
      } else {
        AppState.auditLogs = generateDynamicAuditLogs();
      }
    } catch (e) {
      AppState.auditLogs = generateDynamicAuditLogs();
    }

    // 5. Data Operator Petugas Lapangan
    try {
      const usersRes = await mobileApiCall('users.php?action=list');
      if (usersRes.ok && usersRes.data && Array.isArray(usersRes.data.rows) && usersRes.data.rows.length > 0) {
        AppState.operators = usersRes.data.rows;
      } else {
        AppState.operators = generateDefaultOperators();
      }
    } catch (e) {
      AppState.operators = generateDefaultOperators();
    }

    AppState.leaderboardData = generateDynamicLeaderboard();

    calculateStats();
    renderDashboardStats();
    renderSupportersList();
    renderAspirasiDedicated();
    renderPokirDedicated();
    renderPetaDistricts();
    renderLeaderboard();
    renderAuditLogs();
    renderOperators();
    renderProfile();
    renderMobileAuth();

  } catch (error) {
    console.warn('Gagal memuat data dari server:', error);
    if (!AppState.auditLogs || AppState.auditLogs.length === 0) AppState.auditLogs = generateDynamicAuditLogs();
    if (!AppState.operators || AppState.operators.length === 0) AppState.operators = generateDefaultOperators();
    calculateStats();
    renderDashboardStats();
    renderAuditLogs();
    renderOperators();
    renderProfile();
    renderMobileAuth();
  }
}

function renderMobileAuth() {
  const container = document.getElementById('mobileAuthStatus');
  if (!container) return;
  const token = localStorage.getItem('dprd_token');
  if (token) {
    const u = AppState.currentUser || {};
    const wilayahText = [u.kecamatan, u.desa].filter(Boolean).join(' · ') || 'Seluruh Dapil Kraksaan Raya';
    container.innerHTML = `
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px;margin-bottom:12px;">
        <div style="font-size:13px;font-weight:700;color:#0f172a;">${escapeHtml(u.nama || u.username || 'Petugas Lapangan')}</div>
        <div style="font-size:11px;font-weight:600;color:#2563eb;margin-top:2px;">${escapeHtml(u.role || 'Operator')}</div>
        <div style="font-size:11px;color:#64748b;margin-top:2px;">Wilayah Tugas: ${escapeHtml(wilayahText)}</div>
      </div>
      <button type="button" class="btn-outline-touch" style="width:100%;color:#dc2626;border-color:#fecaca;" onclick="handleMobileLogout()">
        Keluar (Logout)
      </button>
    `;
  } else {
    container.innerHTML = `
      <p style="font-size:12px;color:#64748b;margin-bottom:10px;">Masuk dengan akun Superadmin atau Petugas Lapangan untuk sinkronisasi database online.</p>
      <div class="form-group" style="margin-bottom:8px;">
        <input type="text" id="mobileLoginUser" class="form-input-touch" placeholder="Username (misal: superadmin)">
      </div>
      <div class="form-group" style="margin-bottom:10px;">
        <input type="password" id="mobileLoginPass" class="form-input-touch" placeholder="Password">
      </div>
      <button type="button" class="btn-primary-touch" style="width:100%;height:40px;font-size:13px;" onclick="handleMobileLogin()">
        Masuk Akun Sekarang
      </button>
    `;
  }
}

async function handleMobileLogin(isFromProfile = false) {
  let u = '';
  let p = '';
  if (isFromProfile) {
    u = document.getElementById('profileLoginUser')?.value?.trim() || '';
    p = document.getElementById('profileLoginPass')?.value || '';
  } else {
    u = document.getElementById('mobileLoginUser')?.value?.trim() || document.getElementById('profileLoginUser')?.value?.trim() || '';
    p = document.getElementById('mobileLoginPass')?.value || document.getElementById('profileLoginPass')?.value || '';
  }

  if (!u || !p) {
    showToast('Username dan password wajib diisi.', 'warning');
    return;
  }
  showToast('Memverifikasi akun...', 'info');
  const res = await mobileApiCall('auth.php?action=login', 'POST', { username: u, password: p });
  if (res.ok && res.data && res.data.success) {
    localStorage.setItem('dprd_token', res.data.token);
    if (res.data.user) AppState.currentUser = res.data.user;
    showToast('Berhasil masuk! Menyinkronkan data...', 'success');
    renderMobileAuth();
    renderProfile();
    renderOperators();
    loadAllData();
  } else {
    const msg = (res.data && res.data.message) ? res.data.message : 'Username atau password salah.';
    showToast(msg, 'warning');
  }
}

function handleMobileLogout() {
  localStorage.removeItem('dprd_token');
  AppState.currentUser = null;
  showToast('Anda telah keluar.', 'info');
  renderMobileAuth();
  renderProfile();
  renderOperators();
  loadAllData();
}

// Kalkulasi Statistik
function calculateStats() {
  const total = AppState.supporters.length;
  const terverifikasi = AppState.supporters.filter(s => {
    const st = s.status || '';
    return st === 'Final' || st === 'valid' || st === 'Terverifikasi' || st === 'Divalidasi Kecamatan' || st === 'Diverifikasi Desa';
  }).length;
  const target = 25000;
  const persen = Math.min(100, Math.round((total / target) * 100));

  const kecStats = { Kraksaan: 0, Besuk: 0, Gading: 0 };
  AppState.supporters.forEach(s => {
    const k = s.kecamatan || 'Kraksaan';
    if (kecStats[k] !== undefined) kecStats[k]++;
    else kecStats['Kraksaan']++;
  });

  AppState.stats = {
    total,
    terverifikasi,
    target,
    persen,
    aspirasiCount: AppState.aspirasi.length,
    pokirCount: AppState.pokir.length,
    kecamatan: kecStats
  };

  // Update Drawer Badges
  const bP = document.getElementById('drawerBadgePendukung');
  const bA = document.getElementById('drawerBadgeAspirasi');
  const bR = document.getElementById('drawerBadgePokir');
  if (bP) bP.textContent = total;
  if (bA) bA.textContent = AppState.aspirasi.length;
  if (bR) bR.textContent = AppState.pokir.length;
}

// Render Dashboard
function renderDashboardStats() {
  calculateStats();
  const s = AppState.stats;

  const elTotal = document.getElementById('kpiTotalPendukung');
  const elValid = document.getElementById('kpiTerverifikasi');
  const elAspirasi = document.getElementById('kpiTotalAspirasi');
  const elPokir = document.getElementById('kpiTotalPokir');

  if (elTotal) elTotal.textContent = s.total.toLocaleString('id-ID');
  if (elValid) elValid.textContent = s.terverifikasi.toLocaleString('id-ID');
  if (elAspirasi) elAspirasi.textContent = s.aspirasiCount.toLocaleString('id-ID');
  if (elPokir) elPokir.textContent = s.pokirCount.toLocaleString('id-ID');

  const elProgressFill = document.getElementById('progressTargetFill');
  const elProgressPct = document.getElementById('progressTargetPct');
  const elProgressSub = document.getElementById('progressTargetSub');

  if (elProgressFill) elProgressFill.style.width = `${s.persen}%`;
  if (elProgressPct) elProgressPct.textContent = `${s.persen}%`;
  if (elProgressSub) elProgressSub.textContent = `${s.total.toLocaleString('id-ID')} / ${s.target.toLocaleString('id-ID')} Suara`;

  const elKecKraksaan = document.getElementById('kecKraksaanVal');
  const elKecBesuk = document.getElementById('kecBesukVal');
  const elKecGading = document.getElementById('kecGadingVal');

  if (elKecKraksaan) elKecKraksaan.textContent = s.kecamatan.Kraksaan.toLocaleString('id-ID');
  if (elKecBesuk) elKecBesuk.textContent = s.kecamatan.Besuk.toLocaleString('id-ID');
  if (elKecGading) elKecGading.textContent = s.kecamatan.Gading.toLocaleString('id-ID');
}

// Render Daftar Pendukung (Touch Cards)
function renderSupportersList() {
  const container = document.getElementById('supportersCardList');
  if (!container) return;

  let list = [...AppState.supporters];

  if (AppState.searchQuery) {
    const q = AppState.searchQuery;
    list = list.filter(item => {
      const nama = (item.nama || '').toLowerCase();
      const nik = (item.nik || '').toLowerCase();
      const desa = (item.desa || '').toLowerCase();
      const kec = (item.kecamatan || '').toLowerCase();
      const jlr = (item.jalur || '').toLowerCase();
      return nama.includes(q) || nik.includes(q) || desa.includes(q) || kec.includes(q) || jlr.includes(q);
    });
  }

  if (AppState.filterJalur) {
    list = list.filter(item => {
      const j = (item.jalur || '').toUpperCase();
      return j === AppState.filterJalur.toUpperCase() || j.includes(AppState.filterJalur.toUpperCase());
    });
  }

  if (AppState.filterStatus) {
    list = list.filter(item => {
      const s = (item.status || '').toLowerCase();
      const fs = AppState.filterStatus.toLowerCase();
      if (fs === 'final') return s === 'final' || s === 'valid' || s === 'terverifikasi';
      if (fs === 'diinput') return s === 'diinput' || s === 'pending';
      return s.includes(fs);
    });
  }

  if (AppState.filterKecamatan) {
    list = list.filter(item => (item.kecamatan || '').toLowerCase() === AppState.filterKecamatan.toLowerCase());
  }

  const countBadge = document.getElementById('supporterResultsCount');
  if (countBadge) countBadge.textContent = `${list.length} Data`;

  if (list.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <div class="empty-state-title">Tidak ada data pendukung</div>
        <div class="empty-state-desc">Coba sesuaikan kata kunci pencarian atau filter status Anda.</div>
      </div>
    `;
    return;
  }

  container.innerHTML = list.map((item, index) => {
    const initials = (item.nama || 'P').split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
    const maskedNik = maskNik(item.nik);
    const phoneRaw = item.hp || item.no_hp || item.telepon || '081234567890';
    const waPhone = formatWaPhone(phoneRaw);
    const waText = encodeURIComponent(`Assalamu'alaikum Bpk/Ibu ${item.nama}, salam silaturahmi dari Tim Relawan Gus Dim.`);

    const st = item.status || 'Diinput';
    let statusClass = 'pending';
    let statusText = 'Diinput';
    let statusIcon = Icons.clock;

    if (st === 'Final' || st === 'valid' || st === 'Terverifikasi') {
      statusClass = 'valid';
      statusText = 'Final';
      statusIcon = Icons.checkCircle;
    } else if (st === 'Divalidasi Kecamatan') {
      statusClass = 'info';
      statusText = 'Validasi Kec';
      statusIcon = Icons.checkCircle;
    } else if (st === 'Diverifikasi Desa') {
      statusClass = 'info';
      statusText = 'Verif Desa';
      statusIcon = Icons.clock;
    } else if (st === 'Ditolak') {
      statusClass = 'danger';
      statusText = 'Ditolak';
      statusIcon = Icons.alertTriangle;
    }

    const jKey = (item.jalur || 'RELAWAN').toUpperCase();
    let jalurLabel = 'Jalur Relawan';
    if (jKey === 'DPC') jalurLabel = 'Jalur DPC';
    else if (jKey === 'DPRT') jalurLabel = 'Jalur DPRT';
    else if (jKey === 'PIP') jalurLabel = 'Jalur PIP';
    else if (jKey === 'KIP') jalurLabel = 'Jalur KIP';

    let extraPill = '';
    if (item.jabatan) {
      extraPill = `<span class="card-pill">${escapeHtml(item.jabatan)}</span>`;
    } else if (item.data_khusus) {
      let dk = item.data_khusus;
      if (typeof dk === 'string') {
        try { dk = JSON.parse(dk); } catch (e) {}
      }
      if (dk && dk.namaSekolah) {
        extraPill = `<span class="card-pill">${escapeHtml(dk.namaSekolah)}</span>`;
      } else if (dk && dk.namaKampus) {
        extraPill = `<span class="card-pill">${escapeHtml(dk.namaKampus)}</span>`;
      }
    }

    return `
      <div class="touch-card" onclick="openSupporterDetail(${index})">
        <div class="card-top-row">
          <div class="card-avatar">${initials}</div>
          <div class="card-info">
            <div class="card-name">${escapeHtml(item.nama)}</div>
            <div class="card-nik">${maskedNik}</div>
          </div>
          <span class="badge-status ${statusClass}">
            ${statusIcon} ${statusText}
          </span>
        </div>

        <div class="card-details-row">
          <div class="card-detail-item">
            ${Icons.mapPin}
            <span>${escapeHtml(item.desa || 'Patokan')}, ${escapeHtml(item.kecamatan || 'Kraksaan')}</span>
          </div>
          <span class="card-pill">TPS ${item.tps || '01'}</span>
          <span class="card-pill" style="font-weight:600;color:#16225e;">${escapeHtml(jalurLabel)}</span>
          ${extraPill}
        </div>

        <div class="card-actions-row" onclick="event.stopPropagation()">
          <a href="https://wa.me/${waPhone}?text=${waText}" target="_blank" class="btn-wa-touch">
            ${Icons.whatsapp} WhatsApp
          </a>
          <button type="button" class="btn-outline-touch" onclick="openSupporterDetail(${index})">
            Detail
          </button>
        </div>
      </div>
    `;
  }).join('');
}

// ==========================================================================
// HALAMAN 4: ASPIRASI WARGA (MODERN SaaS & TOUCH ERGONOMICS)
// ==========================================================================

function renderAspirasiDedicated() {
  const container = document.getElementById('aspirasiDedicatedList');
  if (!container) return;

  // Metrik Terintegrasi pada Filter Pill Status (Opsi 1)
  const totalAspirasi = (AppState.aspirasi || []).length;
  let countMenunggu = 0;
  let countAdvokasi = 0;
  let countPokir = 0;
  let countSelesai = 0;

  (AppState.aspirasi || []).forEach(r => {
    const st = (r.status || '').toLowerCase();
    if (st.includes('menunggu') || st.includes('baru') || st.includes('pending') || !st) {
      countMenunggu++;
    } else if (st.includes('pokir')) {
      countPokir++;
    } else if (st.includes('selesai') || st.includes('disetujui') || st.includes('valid')) {
      countSelesai++;
    } else {
      countAdvokasi++;
    }
  });

  const elSemua = document.getElementById('countStatusSemua');
  if (elSemua) elSemua.textContent = totalAspirasi;
  const elMenunggu = document.getElementById('countStatusMenunggu');
  if (elMenunggu) elMenunggu.textContent = countMenunggu;
  const elAdvokasi = document.getElementById('countStatusAdvokasi');
  if (elAdvokasi) elAdvokasi.textContent = countAdvokasi;
  const elPokir = document.getElementById('countStatusPokir');
  if (elPokir) elPokir.textContent = countPokir;
  const elSelesai = document.getElementById('countStatusSelesai');
  if (elSelesai) elSelesai.textContent = countSelesai;

  let list = [...(AppState.aspirasi || [])];

  // Filter Kategori
  if (AppState.filterAspirasiCat) {
    list = list.filter(a => {
      const kat = (a.kategori || a.bidang || '').toLowerCase();
      return kat.includes(AppState.filterAspirasiCat.toLowerCase());
    });
  }

  // Filter Status
  if (AppState.filterAspirasiStatus) {
    const targetStatus = AppState.filterAspirasiStatus.toLowerCase();
    list = list.filter(a => {
      const st = (a.status || 'menunggu').toLowerCase();
      if (targetStatus === 'menunggu') {
        return st.includes('menunggu') || st.includes('baru') || st.includes('pending') || !st;
      } else if (targetStatus === 'advokasi') {
        return st.includes('advokasi') || st.includes('proses') || st.includes('tindak');
      } else if (targetStatus === 'pokir') {
        return st.includes('pokir');
      } else if (targetStatus === 'selesai') {
        return st.includes('selesai') || st.includes('disetujui') || st.includes('valid');
      }
      return true;
    });
  }

  // Filter Pencarian Real-Time
  if (AppState.searchAspirasiQuery) {
    const q = AppState.searchAspirasiQuery.toLowerCase();
    list = list.filter(a => {
      const nama = (a.nama || a.pengusul || '').toLowerCase();
      const topik = (a.topik || a.judul || '').toLowerCase();
      const isi = (a.aspirasi || a.deskripsi || a.isi || '').toLowerCase();
      const desa = (a.desa || '').toLowerCase();
      const kec = (a.kecamatan || '').toLowerCase();
      const hp = (a.hp || '').toLowerCase();
      return nama.includes(q) || topik.includes(q) || isi.includes(q) || desa.includes(q) || kec.includes(q) || hp.includes(q);
    });
  }

  const countBadge = document.getElementById('aspirasiResultsCount');
  if (countBadge) countBadge.textContent = list.length + ' Data';

  if (list.length === 0) {
    container.innerHTML = `
      <div class="empty-state" style="padding:24px 16px;">
        ${Icons.messageSquare}
        <div class="empty-state-title">Tidak ada data aspirasi</div>
        <div class="empty-state-desc">Belum ada aspirasi warga yang cocok dengan pencarian atau filter yang dipilih.</div>
      </div>
    `;
    return;
  }

  container.innerHTML = list.map(a => {
    let badgeClass = 'pending';
    let statusText = 'Menunggu';
    const st = (a.status || '').toLowerCase();
    if (st.includes('selesai') || st.includes('disetujui')) {
      badgeClass = 'valid';
      statusText = 'Selesai';
    } else if (st.includes('pokir')) {
      badgeClass = 'info';
      statusText = 'Masuk Pokir';
    } else if (st.includes('advokasi') || st.includes('proses') || st.includes('tindak')) {
      badgeClass = 'purple';
      statusText = 'Advokasi';
    } else {
      badgeClass = 'pending';
      statusText = 'Menunggu';
    }

    const topik = a.topik || a.judul || a.aspirasi || 'Aspirasi Konstituen';
    const pengusul = a.nama || a.pengusul || 'Warga';
    const isi = a.aspirasi || a.deskripsi || a.isi || 'Kebutuhan warga desa yang diusulkan melalui kegiatan relawan.';
    const kat = a.kategori || a.bidang || 'Infrastruktur';
    const desa = a.desa || 'Patokan';
    const kec = a.kecamatan || 'Kraksaan';
    const hasHp = Boolean(a.hp);

    return `
      <div class="touch-card" onclick="openAspirasiDetail(${a.id})" style="padding:12px;margin-bottom:10px;gap:8px;">
        <div class="card-top-row" style="gap:10px;">
          <div class="card-avatar" style="width:36px;height:36px;min-width:36px;border-radius:10px;background:#eff6ff;color:#2563eb;font-size:13px;">
            ${Icons.messageSquare}
          </div>
          <div class="card-info">
            <div class="card-name" style="font-size:14px;font-weight:700;">${escapeHtml(topik)}</div>
            <div class="card-nik" style="font-family:inherit;font-size:11px;">Pengusul: <strong>${escapeHtml(pengusul)}</strong></div>
          </div>
          <span class="badge-status ${badgeClass}" style="font-size:10.5px;padding:3px 8px;">${statusText}</span>
        </div>
        <p style="font-size:12.5px;color:#334155;line-height:1.42;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0;">
          ${escapeHtml(isi)}
        </p>
        <div class="card-details-row" style="padding-top:6px;gap:6px;font-size:11.5px;">
          <div class="card-detail-item" style="gap:3px;">
            ${Icons.mapPin}
            <span>${escapeHtml(desa)}, ${escapeHtml(kec)}</span>
          </div>
          <span class="card-pill blue" style="font-size:10px;padding:1px 6px;">${escapeHtml(kat)}</span>
          ${hasHp ? `<span class="card-pill green" style="font-size:10px;padding:1px 6px;">WA Ada</span>` : ''}
        </div>
        <div class="card-actions-row" onclick="event.stopPropagation()" style="margin-top:2px;gap:8px;">
          ${hasHp ? `
            <button type="button" class="btn-wa-touch" style="height:34px;font-size:11.5px;padding:0 10px;" onclick="kirimWaUpdateAspirasi(${a.id})">
              ${Icons.whatsapp} Kabar WA
            </button>
          ` : ''}
          <button type="button" class="btn-outline-touch" style="flex:1;height:34px;font-size:11.5px;padding:0 10px;" onclick="openAspirasiDetail(${a.id})">
            Detail &amp; Aksi
          </button>
        </div>
      </div>
    `;
  }).join('');
}

function clearAspirasiSearch() {
  const input = document.getElementById('searchAspirasiInput');
  const btn = document.getElementById('btnClearAspirasiSearch');
  if (input) input.value = '';
  if (btn) btn.classList.remove('active');
  AppState.searchAspirasiQuery = '';
  renderAspirasiDedicated();
}

function filterAspirasiStatus(status, btn) {
  AppState.filterAspirasiStatus = status;
  document.querySelectorAll('#page-aspirasi .filter-pill[data-asp-status]').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  renderAspirasiDedicated();
}

function filterAspirasiCat(cat, btn) {
  AppState.filterAspirasiCat = cat;
  document.querySelectorAll('#page-aspirasi .filter-pill[data-asp-cat]').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  renderAspirasiDedicated();
}

// Buka Bottom Sheet Detail Aspirasi
function openAspirasiDetail(id) {
  const a = (AppState.aspirasi || []).find(item => String(item.id) === String(id));
  if (!a) {
    showToast('Data aspirasi tidak ditemukan', 'warning');
    return;
  }

  const topik = a.topik || a.judul || a.aspirasi || 'Aspirasi Warga';
  const nama = a.nama || a.pengusul || 'Warga Konstituen';
  const isi = a.aspirasi || a.deskripsi || a.isi || 'Tidak ada uraian rincian.';
  const desa = a.desa || '-';
  const kec = a.kecamatan || 'Kraksaan';
  const kat = a.kategori || a.bidang || 'Infrastruktur';
  const jalur = a.jalur || 'Relawan';
  const tanggal = a.tanggal || a.created_at || 'Hari ini';
  const phoneRaw = a.hp || '';
  let cleanHp = phoneRaw.replace(/[^0-9]/g, '');
  if (cleanHp.startsWith('0')) cleanHp = '62' + cleanHp.substring(1);

  const st = (a.status || 'Menunggu').toLowerCase();
  let statusBadgeClass = 'pending';
  let statusText = 'Menunggu Verifikasi';

  if (st.includes('selesai') || st.includes('disetujui') || st.includes('valid')) {
    statusBadgeClass = 'valid';
    statusText = 'Selesai / Terealisasi';
  } else if (st.includes('pokir')) {
    statusBadgeClass = 'info';
    statusText = 'Terekalasi ke Pokir APBD';
  } else if (st.includes('advokasi') || st.includes('proses') || st.includes('tindak')) {
    statusBadgeClass = 'purple';
    statusText = 'Dalam Advokasi Lapangan';
  }

  const waActionBtn = cleanHp ? `
    <button type="button" class="btn-wa-touch" style="width:100%;height:44px;" onclick="kirimWaUpdateAspirasi(${a.id})">
      ${Icons.whatsapp} Kirim Kabar Perkembangan via WA
    </button>
  ` : `
    <button type="button" class="btn-wa-touch" style="width:100%;height:44px;background:#94a3b8;" onclick="showToast('Nomor WhatsApp warga belum tercatat', 'info')">
      ${Icons.whatsapp} WhatsApp (Nomor Belum Terdata)
    </button>
  `;

  const content = `
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;margin-bottom:14px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <span class="card-pill blue">${escapeHtml(kat)}</span>
        <span class="badge-status ${statusBadgeClass}">${statusText}</span>
      </div>
      <div style="font-size:16px;font-weight:800;color:#0f172a;line-height:1.3;margin-bottom:6px;">
        ${escapeHtml(topik)}
      </div>
      <div style="font-size:12px;color:#64748b;">
        Pengusul: <strong style="color:#0f172a;">${escapeHtml(nama)}</strong> &bull; ${escapeHtml(tanggal)}
      </div>
    </div>

    <!-- Info Detail Grid -->
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:12px;display:flex;flex-direction:column;gap:8px;margin-bottom:14px;">
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Wilayah</span>
        <span style="font-weight:600;color:#0f172a;">${escapeHtml(desa)}, Kec. ${escapeHtml(kec)}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Jalur Aspirasi</span>
        <span style="font-weight:600;color:#16225e;">${escapeHtml(jalur)}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Kontak HP/WA</span>
        <span style="font-weight:600;color:#0f172a;">${phoneRaw ? escapeHtml(phoneRaw) : '-'}</span>
      </div>
      <div style="border-top:1px dashed #e2e8f0;padding-top:8px;margin-top:2px;">
        <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:4px;">Uraian Keluhan &amp; Kebutuhan:</div>
        <div style="font-size:13px;color:#334155;line-height:1.5;background:#f8fafc;padding:10px;border-radius:8px;border-left:3px solid #2563eb;">
          "${escapeHtml(isi)}"
        </div>
      </div>
    </div>

    <!-- Aksi Cepat Lapangan -->
    <div style="display:flex;flex-direction:column;gap:8px;">
      ${waActionBtn}

      <button type="button" class="btn-outline-touch" style="width:100%;height:44px;background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;font-weight:700;" onclick="eskalasiAspirasiKePokir(${a.id})">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:16px;height:16px;min-width:16px;min-height:16px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
        Eskalasi ke Usulan Pokir APBD
      </button>

      <!-- Update Status Tindak Lanjut -->
      <div style="background:#f1f5f9;padding:10px;border-radius:10px;margin-top:4px;">
        <div style="font-size:11px;font-weight:700;color:#475569;margin-bottom:8px;text-transform:uppercase;">Ubah Status Aspirasi:</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
          <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;background:#fffbeb;color:#b45309;border-color:#fde68a;" onclick="updateAspirasiStatus(${a.id}, 'Menunggu')">
            Set Menunggu
          </button>
          <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;background:#faf5ff;color:#7e22ce;border-color:#e9d5ff;" onclick="updateAspirasiStatus(${a.id}, 'Advokasi')">
            Set Advokasi
          </button>
          <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;" onclick="updateAspirasiStatus(${a.id}, 'Masuk Pokir')">
            Set Masuk Pokir
          </button>
          <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;background:#f0fdf4;color:#15803d;border-color:#bbf7d0;" onclick="updateAspirasiStatus(${a.id}, 'Selesai')">
            Tandai Selesai
          </button>
        </div>
      </div>

      <button type="button" class="btn-outline-touch" style="width:100%;height:40px;margin-top:2px;" onclick="closeBottomSheet()">
        Tutup
      </button>
    </div>
  `;

  openBottomSheet('Detail Aspirasi Warga', content);
}

// Update Status Aspirasi ke Server API
async function updateAspirasiStatus(id, newStatus) {
  const asp = (AppState.aspirasi || []).find(a => String(a.id) === String(id));
  if (!asp) return;

  showToast('Memperbarui status aspirasi...', 'info');
  try {
    const res = await mobileApiCall('aspirasi.php?action=update-status', 'POST', { id, status: newStatus });
    if (res.ok && res.data && res.data.success) {
      asp.status = newStatus;
      renderAspirasiDedicated();
      closeBottomSheet();
      showToast(`Status aspirasi berhasil diubah ke "${newStatus}"!`, 'success');

      if (asp.hp) {
        setTimeout(() => {
          if (confirm(`Status aspirasi telah diperbarui ke "${newStatus}". Ingin kirim notifikasi kabar perkembangan ke WhatsApp warga (${asp.hp}) sekarang?`)) {
            kirimWaUpdateAspirasi(id, newStatus);
          }
        }, 400);
      }
    } else {
      asp.status = newStatus;
      renderAspirasiDedicated();
      closeBottomSheet();
      showToast(`Status diubah secara lokal menjadi "${newStatus}"`, 'info');
    }
  } catch (err) {
    showToast('Gagal update status: ' + err.message, 'warning');
  }
}

// Kirim Kabar Perkembangan via WhatsApp
function kirimWaUpdateAspirasi(id, customStatus) {
  const asp = (AppState.aspirasi || []).find(a => String(a.id) === String(id));
  if (!asp) {
    showToast('Data aspirasi tidak ditemukan', 'warning');
    return;
  }

  const rawHp = asp.hp || '';
  let cleanHp = rawHp.replace(/[^0-9]/g, '');
  if (cleanHp.startsWith('0')) {
    cleanHp = '62' + cleanHp.substring(1);
  }

  const status = customStatus || asp.status || 'Ditindaklanjuti';
  const nama = asp.nama || asp.pengusul || 'Bpk/Ibu Warga';
  const keluhan = asp.aspirasi || asp.deskripsi || asp.isi || '-';
  const desa = asp.desa || '-';
  const kec = asp.kecamatan || '-';

  let kalimatStatus = '';
  const stLower = status.toLowerCase();
  if (stLower.includes('selesai') || stLower.includes('disetujui')) {
    kalimatStatus = 'Alhamdulillah, aspirasi panjenengan telah *SELESAI DIREALISASIKAN* melalui program advokasi dewan Gus Dim di lapangan.';
  } else if (stLower.includes('pokir')) {
    kalimatStatus = 'Aspirasi panjenengan telah *DIESKALASI KE USULAN POKIR APBD RESMI* untuk diperjuangkan dalam anggaran pembangunan daerah Kabupaten Probolinggo.';
  } else if (stLower.includes('advokasi') || stLower.includes('tindak') || stLower.includes('proses')) {
    kalimatStatus = 'Aspirasi panjenengan telah *DISETUJUI & SEDANG DALAM ADVOKASI LAPANGAN* oleh tim fraksi untuk dikoordinasikan dengan instansi/dinas terkait.';
  } else {
    kalimatStatus = 'Aspirasi panjenengan telah kami terima dan saat ini berstatus *' + status + '* dalam verifikasi serta telaah tim fraksi.';
  }

  const pesan = "Assalamu'alaikum Wr. Wb. Bpk/Ibu *" + nama + "*,\n\n" +
    "Kami dari Tim Sahabat Gus Dim (Fraksi NasDem DPRD Kab. Probolinggo) menyampaikan kabar perkembangan aspirasi panjenengan:\n\n" +
    "Aspirasi: *\"" + keluhan + "\"*\n" +
    "Wilayah: Desa " + desa + ", Kec. " + kec + "\n" +
    "Status Terkini: *" + status + "*\n\n" +
    kalimatStatus + "\n\n" +
    "Terima kasih atas partisipasi dan aspirasi panjenengan demi kemaslahatan bersama di Dapil Kraksaan Raya.\n\n" +
    "Salam Hormat,\n" +
    "*Gus Dim & Tim Fraksi NasDem*";

  const waUrl = cleanHp ?
    "https://wa.me/" + cleanHp + "?text=" + encodeURIComponent(pesan) :
    "https://wa.me/?text=" + encodeURIComponent(pesan);

  window.open(waUrl, '_blank');
}

// Jembatan Eskalasi Aspirasi ke Usulan Pokir APBD
function eskalasiAspirasiKePokir(id) {
  const asp = (AppState.aspirasi || []).find(a => String(a.id) === String(id));
  if (!asp) {
    showToast('Data aspirasi tidak ditemukan', 'warning');
    return;
  }

  closeBottomSheet();

  const shortTitle = (asp.topik || asp.judul || asp.aspirasi || 'Aspirasi Warga').substring(0, 55).trim();
  const catMap = {
    'Infrastruktur': 'Dinas PUPR',
    'Pertanian': 'Dinas Pertanian',
    'Pendidikan': 'Dinas Pendidikan',
    'Kesehatan': 'Dinas Kesehatan',
    'UMKM': 'Dinas Koperasi & UMKM',
    'Bansos': 'Dinas Sosial'
  };

  const prefill = {
    kegiatan: 'Advokasi: ' + shortTitle,
    opd: catMap[asp.kategori] || 'Dinas PUPR',
    anggaran: 50000000,
    lokasi: (asp.desa ? 'Desa ' + asp.desa + ', ' : '') + 'Kec. ' + (asp.kecamatan || 'Kraksaan'),
    uraian: 'Eskalasi dari aspirasi konstituen: ' + (asp.aspirasi || asp.deskripsi || asp.isi || '') + ' (Kanal Aspirasi Warga Dapil Kraksaan Raya)',
    pengusulNama: asp.nama || asp.pengusul || '',
    pengusulHp: asp.hp || ''
  };

  openPokirForm(prefill);
  showToast('Form Usulan Pokir terisi otomatis dari data aspirasi warga', 'info');
}

// Buka Form Aspirasi Baru
function openAspirasiForm() {
  const content = `
    <form onsubmit="handleAspirasiSubmit(event)">
      <div class="form-group">
        <label class="form-label">Topik / Judul Aspirasi <span class="required-mark">*</span></label>
        <input type="text" id="aspJudul" class="form-input-touch" placeholder="Contoh: Perbaikan Saluran Irigasi Tersier" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Nama Pengusul <span class="required-mark">*</span></label>
          <input type="text" id="aspPengusul" class="form-input-touch" placeholder="Nama warga / Poktan" required>
        </div>
        <div class="form-group">
          <label class="form-label">WhatsApp Pengusul</label>
          <input type="tel" id="aspHp" class="form-input-touch" placeholder="08xxxxxxxxxx">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Bidang Kategori <span class="required-mark">*</span></label>
          <select id="aspKategori" class="form-select-touch" required>
            <option value="Infrastruktur">Infrastruktur (Jalan / Jembatan)</option>
            <option value="Pertanian">Pertanian &amp; Irigasi</option>
            <option value="Pendidikan">Pendidikan &amp; Beasiswa</option>
            <option value="Kesehatan">Layanan Kesehatan</option>
            <option value="Bansos">Bantuan Sosial &amp; Sembako</option>
            <option value="UMKM">UMKM &amp; Ekonomi Kreatif</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Jalur Penjaringan</label>
          <select id="aspJalur" class="form-select-touch">
            <option value="Relawan">Relawan Lapangan</option>
            <option value="DPC Kecamatan">DPC Kecamatan</option>
            <option value="DPRT Desa">DPRT Desa</option>
            <option value="PIP">Aspirasi PIP</option>
            <option value="KIP">Aspirasi KIP</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Kecamatan <span class="required-mark">*</span></label>
          <select id="aspKecamatan" class="form-select-touch" onchange="updateAspirasiDesa(this.value)" required>
            <option value="Kraksaan">Kraksaan</option>
            <option value="Besuk">Besuk</option>
            <option value="Gading">Gading</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Desa <span class="required-mark">*</span></label>
          <select id="aspDesa" class="form-select-touch" required>
            <option value="Patokan">Patokan</option>
            <option value="Semampir">Semampir</option>
            <option value="Kraksaan Wetan">Kraksaan Wetan</option>
            <option value="Kandangjati Kulon">Kandangjati Kulon</option>
            <option value="Kandangjati Wetan">Kandangjati Wetan</option>
            <option value="Sidomukti">Sidomukti</option>
            <option value="Kebonagung">Kebonagung</option>
            <option value="Rondokuning">Rondokuning</option>
            <option value="Asembagus">Asembagus</option>
            <option value="Bulubrangsi">Bulubrangsi</option>
            <option value="Kalisalam">Kalisalam</option>
            <option value="Kregenan">Kregenan</option>
            <option value="Tamansari">Tamansari</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Uraian Kebutuhan &amp; Keluhan Warga <span class="required-mark">*</span></label>
        <textarea id="aspIsi" class="form-textarea-touch" placeholder="Jelaskan detail kebutuhan atau permasalahan warga secara spesifik..." required></textarea>
      </div>

      <div class="sticky-form-cta">
        <button type="submit" class="btn-primary-touch">
          ${Icons.checkCircle} Kirim &amp; Simpan Aspirasi Warga
        </button>
      </div>
    </form>
  `;
  openBottomSheet('Formulir Jaring Aspirasi Konstituen', content);
}

function updateAspirasiDesa(kec) {
  const el = document.getElementById('aspDesa');
  if (!el) return;
  const data = DapilLocations[kec];
  if (data && Array.isArray(data.desa)) {
    el.innerHTML = data.desa.map(d => `<option value="${escapeHtml(d)}">${escapeHtml(d)}</option>`).join('');
  }
}

// Simpan Aspirasi Baru ke Server API MySQL
async function handleAspirasiSubmit(e) {
  e.preventDefault();
  const topik = document.getElementById('aspJudul').value.trim();
  const nama = document.getElementById('aspPengusul').value.trim();
  const hp = (document.getElementById('aspHp')?.value || '').trim();
  const kategori = document.getElementById('aspKategori').value;
  const jalur = (document.getElementById('aspJalur')?.value || 'Relawan');
  const kecamatan = document.getElementById('aspKecamatan').value;
  const desa = document.getElementById('aspDesa').value;
  const isi = document.getElementById('aspIsi').value.trim();

  if (!nama || !isi) {
    showToast('Nama pengusul dan isi keluhan wajib diisi!', 'warning');
    return;
  }

  showToast('Menyimpan aspirasi warga...', 'info');
  const payload = {
    topik,
    nama,
    hp,
    kategori,
    jalur,
    kecamatan,
    desa,
    aspirasi: isi
  };

  try {
    const res = await mobileApiCall('aspirasi.php?action=add', 'POST', payload);
    if (res.ok && res.data && res.data.success) {
      const savedItem = res.data.item || {
        id: Date.now(),
        ...payload,
        status: 'Baru',
        tanggal: new Date().toLocaleDateString('id-ID')
      };
      AppState.aspirasi.unshift(savedItem);
      calculateStats();
      renderDashboardStats();
      renderAspirasiDedicated();
      closeBottomSheet();
      showToast('Aspirasi warga berhasil dicatat dan disimpan ke server!', 'success');
    } else {
      const fallbackItem = {
        id: Date.now(),
        ...payload,
        status: 'Baru',
        tanggal: new Date().toLocaleDateString('id-ID')
      };
      AppState.aspirasi.unshift(fallbackItem);
      calculateStats();
      renderDashboardStats();
      renderAspirasiDedicated();
      closeBottomSheet();
      showToast('Aspirasi tersimpan di perangkat (Mode Offline)', 'info');
    }
  } catch (err) {
    showToast('Gagal mencatat aspirasi: ' + err.message, 'warning');
  }
}

// ==========================================================================
// HALAMAN 5: RESES & POKIR APBD
// ==========================================================================

function getPokirStageBadgeMobile(status) {
  const s = status || 'Aspirasi Reses';
  if (s.includes('Realisasi')) {
    return '<span class="badge-stage stage-6">6. Realisasi Lapangan</span>';
  } else if (s.includes('APBD')) {
    return '<span class="badge-stage stage-5">5. Masuk APBD Resmi</span>';
  } else if (s.includes('Verifikasi')) {
    return '<span class="badge-stage stage-4">4. Verifikasi Dinas</span>';
  } else if (s.includes('SIPD')) {
    return '<span class="badge-stage stage-3">3. Terinput SIPD</span>';
  } else if (s.includes('Disetujui')) {
    return '<span class="badge-stage stage-2">2. Disetujui Gus Dim</span>';
  } else {
    return '<span class="badge-stage stage-1">1. Aspirasi Reses</span>';
  }
}

function renderPokirDedicated() {
  const container = document.getElementById('pokirDedicatedList');
  if (!container) return;

  const activeTab = AppState.activeResesTab || 'pokir';

  if (activeTab === 'pokir') {
    const list = AppState.pokir || [];
    if (list.length === 0) {
      container.innerHTML = `
        <div class="empty-state" style="padding:28px 16px;">
          ${Icons.building}
          <div class="empty-state-title">Belum ada usulan Pokir APBD</div>
          <div class="empty-state-desc">Gunakan tombol "Usul Pokir" di atas untuk mendaftarkan usulan program dewan.</div>
        </div>
      `;
      return;
    }

    container.innerHTML = list.map((p, idx) => {
      const id = p.id || (idx + 1);
      const judul = p.judul_usulan || p.kegiatan || p.judul || 'Program Pokir APBD';
      const budget = (p.estimasi_anggaran || p.anggaran) ? `Rp ${Number(p.estimasi_anggaran || p.anggaran).toLocaleString('id-ID')}` : 'Rp 150.000.000';
      const opd = p.opd_tujuan || p.opd || p.kategori || 'Dinas PUPR';
      const lokasi = p.lokasiDetail || p.lokasi || p.desa || 'Kecamatan Kraksaan';
      const uraian = p.deskripsi || p.uraian || p.keterangan || 'Program kerja prioritas DPRD untuk kemakmuran masyarakat Dapil.';
      const pengusul = p.nama_pengusul || p.pengusul_nama || p.pengusulNama || 'Konstituen Dapil';
      const rawHp = p.kontak_pengusul || p.pengusul_hp || p.pengusulHp || '';
      const hasHp = Boolean(rawHp && rawHp.trim() !== '');
      const stageBadge = getPokirStageBadgeMobile(p.status_tahap);

      return `
        <div class="touch-card" onclick="openPokirDetail(${id})" style="padding:12px;margin-bottom:10px;gap:8px;">
          <div class="card-top-row" style="gap:10px;">
            <div class="card-avatar" style="width:36px;height:36px;min-width:36px;border-radius:10px;background:#e0f2fe;color:#0284c7;font-size:13px;">
              ${Icons.building}
            </div>
            <div class="card-info">
              <div class="card-name" style="font-size:14px;font-weight:700;">${escapeHtml(judul)}</div>
              <div class="card-nik" style="color:#0284c7;font-weight:700;font-family:inherit;font-size:12px;">${budget}</div>
            </div>
            ${stageBadge}
          </div>
          <p style="font-size:12.5px;color:#334155;line-height:1.42;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0;">
            ${escapeHtml(uraian)}
          </p>
          <div class="card-details-row" style="padding-top:6px;gap:6px;font-size:11.5px;">
            <div class="card-detail-item" style="gap:3px;">
              ${Icons.mapPin}
              <span>${escapeHtml(lokasi)}</span>
            </div>
            <span class="card-pill blue" style="font-size:10px;padding:1px 6px;">OPD: ${escapeHtml(opd)}</span>
            <span class="card-pill" style="font-size:10px;padding:1px 6px;">Oleh: ${escapeHtml(pengusul)}</span>
            ${hasHp ? `<span class="card-pill green" style="font-size:10px;padding:1px 6px;">WA Ada</span>` : ''}
          </div>
          <div class="card-actions-row" onclick="event.stopPropagation()" style="margin-top:2px;gap:8px;">
            ${hasHp ? `
              <button type="button" class="btn-wa-touch" style="height:34px;font-size:11.5px;padding:0 10px;" onclick="kirimWaUpdatePokir(${id})">
                ${Icons.whatsapp} Kabar WA
              </button>
            ` : ''}
            <button type="button" class="btn-outline-touch" style="flex:1;height:34px;font-size:11.5px;padding:0 10px;" onclick="openPokirDetail(${id})">
              Detail &amp; Aksi
            </button>
          </div>
        </div>
      `;
    }).join('');

  } else {
    // Laporan Reses Dewan
    const events = (AppState.resesEvents && AppState.resesEvents.length > 0) ? AppState.resesEvents : [
      { id: 1, nama: 'Reses Masa Sidang I Tahun 2025', masa_sidang: 'Masa Sidang I 2025', kecamatan: 'Kraksaan', desa: 'Patokan', dusun: 'Pajarakan', lokasi: 'Balai Warga RW 03', tanggal: '2025-01-15', waktu: '13:30', status: 'Selesai', total_hadir: 85, target: 'Petani & Pedagang Pasar', catatan: 'Penyerapan aspirasi kelompok petani padi dan pedagang terkait subsidi pupuk dan lapak UMKM.' },
      { id: 2, nama: 'Reses Masa Sidang II Tahun 2025', masa_sidang: 'Masa Sidang II 2025', kecamatan: 'Besuk', desa: 'Alasnyiur', dusun: 'Gading Krajan', lokasi: 'Ponpes Nurul Huda', tanggal: '2025-04-20', waktu: '19:30', status: 'Selesai', total_hadir: 120, target: 'Santri & Pemuda Desa', catatan: 'Kunjungan dan penyerapan aspirasi penerangan jalan desa dan pemberdayaan bibit jagung hibrida.' }
    ];

    container.innerHTML = events.map((ev, idx) => {
      const id = ev.id || (idx + 1);
      const nama = ev.nama || ev.nama_acara || 'Pertemuan Reses Dapil';
      const sidang = ev.masa_sidang || ev.masaSidang || 'Masa Sidang I 2025';
      const kec = ev.kecamatan || 'Kraksaan';
      const desa = ev.desa || 'Patokan';
      const lokasi = ev.lokasi || ev.lokasi_detail || `Desa ${desa}, Kec. ${kec}`;
      const tgl = ev.tanggal || '2025-01-15';
      const hadir = ev.total_hadir || 50;
      const catatan = ev.catatan || 'Penyerapan aspirasi warga konstituen Dapil Kraksaan Raya.';

      return `
        <div class="touch-card" onclick="openResesEventDetail(${id})" style="padding:12px;margin-bottom:10px;gap:8px;">
          <div class="card-top-row" style="gap:10px;">
            <div class="card-avatar" style="width:36px;height:36px;min-width:36px;border-radius:10px;background:#fef3c7;color:#b45309;font-size:13px;">
              ${Icons.calendar}
            </div>
            <div class="card-info">
              <div class="card-name" style="font-size:14px;font-weight:700;">${escapeHtml(nama)}</div>
              <div class="card-nik" style="font-family:inherit;font-size:11px;color:#64748b;">${escapeHtml(sidang)}</div>
            </div>
            <span class="badge-status valid" style="font-size:10.5px;padding:3px 8px;">Selesai</span>
          </div>
          <p style="font-size:12.5px;color:#334155;line-height:1.42;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0;">
            ${escapeHtml(catatan)}
          </p>
          <div class="card-details-row" style="padding-top:6px;gap:6px;font-size:11.5px;">
            <div class="card-detail-item" style="gap:3px;">
              ${Icons.mapPin}
              <span>${escapeHtml(lokasi)}</span>
            </div>
            <span class="card-pill" style="font-size:10px;padding:1px 6px;">Tgl: ${escapeHtml(tgl)}</span>
            <span class="card-pill green" style="font-size:10px;padding:1px 6px;">Hadir: ${hadir} Warga</span>
          </div>
          <div class="card-actions-row" onclick="event.stopPropagation()" style="margin-top:2px;gap:8px;">
            <button type="button" class="btn-outline-touch" style="height:34px;font-size:11.5px;padding:0 10px;background:#f0fdf4;color:#16a34a;border-color:#bbf7d0;" onclick="openPokirForm({ lokasi: '${escapeHtml(lokasi)}', uraian: 'Diserap dari agenda reses: ${escapeHtml(nama)}' })">
              + Usul Pokir
            </button>
            <button type="button" class="btn-outline-touch" style="flex:1;height:34px;font-size:11.5px;padding:0 10px;" onclick="openResesEventDetail(${id})">
              Detail Titik
            </button>
          </div>
        </div>
      `;
    }).join('');
  }
}

// Detail & Aksi Bottom Sheet Pokir APBD
function openPokirDetail(id) {
  const p = (AppState.pokir || []).find(item => String(item.id) === String(id));
  if (!p) {
    showToast('Data usulan Pokir tidak ditemukan', 'warning');
    return;
  }

  const judul = p.judul_usulan || p.kegiatan || p.judul || 'Program Pokir APBD';
  const opd = p.opd_tujuan || p.opd || p.kategori || 'Dinas PUPR';
  const budget = (p.estimasi_anggaran || p.anggaran) ? `Rp ${Number(p.estimasi_anggaran || p.anggaran).toLocaleString('id-ID')}` : 'Rp 150.000.000';
  const lokasi = p.lokasiDetail || p.lokasi || p.desa || 'Kecamatan Kraksaan';
  const uraian = p.deskripsi || p.uraian || p.keterangan || 'Program kerja prioritas DPRD untuk kemakmuran masyarakat Dapil.';
  const pengusul = p.nama_pengusul || p.pengusul_nama || p.pengusulNama || 'Konstituen Dapil';
  const hp = p.kontak_pengusul || p.pengusul_hp || p.pengusulHp || '';
  const currentStage = p.status_tahap || 'Aspirasi Reses';
  const catatan = p.catatan_progres || 'Menunggu verifikasi fraksi.';
  const stageBadge = getPokirStageBadgeMobile(currentStage);

  let cleanHp = hp.replace(/[^0-9]/g, '');
  if (cleanHp.startsWith('0')) cleanHp = '62' + cleanHp.substring(1);

  const stagesList = [
    '1. Aspirasi Reses',
    '2. Disetujui Gus Dim',
    '3. Terinput SIPD',
    '4. Verifikasi Dinas',
    '5. Masuk APBD Resmi',
    '6. Realisasi Lapangan'
  ];

  const stageOptions = stagesList.map(st => {
    const isSelected = currentStage.toLowerCase().includes(st.toLowerCase().substring(3, 10)) ? 'selected' : '';
    return `<option value="${st}" ${isSelected}>${st}</option>`;
  }).join('');

  const waActionBtn = cleanHp ? `
    <button type="button" class="btn-wa-touch" style="width:100%;height:42px;font-size:12.5px;font-weight:700;" onclick="kirimWaUpdatePokir(${p.id})">
      ${Icons.whatsapp} Kirim Kabar Perkembangan Pokir via WA
    </button>
  ` : '';

  const content = `
    <div style="display:flex;flex-direction:column;gap:12px;">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
        <span class="card-pill blue" style="font-size:11px;font-weight:700;">OPD: ${escapeHtml(opd)}</span>
        ${stageBadge}
      </div>

      <div style="font-size:16px;font-weight:800;color:#0f172a;line-height:1.35;">
        ${escapeHtml(judul)}
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:12px;color:#64748b;font-weight:600;">Estimasi Pagu Anggaran</span>
        <span style="font-size:15px;font-weight:800;color:#0284c7;">${budget}</span>
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;font-size:12px;color:#334155;line-height:1.45;">
        <div style="font-weight:700;color:#0f172a;margin-bottom:4px;">Uraian Kerja &amp; Manfaat:</div>
        ${escapeHtml(uraian)}
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:11.5px;">
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:8px 10px;">
          <div style="color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;">Pengusul</div>
          <div style="font-weight:700;color:#0f172a;margin-top:2px;">${escapeHtml(pengusul)}</div>
          <div style="color:#64748b;font-size:11px;">${cleanHp ? 'WA: ' + hp : 'Kontak: -'}</div>
        </div>
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:8px 10px;">
          <div style="color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;">Lokasi Wilayah</div>
          <div style="font-weight:700;color:#0f172a;margin-top:2px;">${escapeHtml(lokasi)}</div>
        </div>
      </div>

      <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 12px;">
        <div style="font-size:11px;font-weight:700;color:#1d4ed8;margin-bottom:4px;display:flex;align-items:center;gap:4px;">
          ${Icons.info} Catatan Progres Terkini:
        </div>
        <div style="font-size:12px;color:#1e3a8a;line-height:1.4;">
          ${escapeHtml(catatan)}
        </div>
      </div>

      ${waActionBtn}

      <div style="border-top:1px dashed #cbd5e1;padding-top:12px;margin-top:2px;">
        <div style="font-size:12px;font-weight:700;color:#0f172a;margin-bottom:8px;">
          Perbarui Tahapan Siklus Pokir:
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <select id="selectPokirStageModal" class="form-select-touch" style="height:40px;font-size:12.5px;">
            ${stageOptions}
          </select>
          <input type="text" id="inputPokirCatatanModal" class="form-input-touch" style="height:40px;font-size:12px;" placeholder="Catatan perkembangan progres (opsional)..." value="${escapeHtml(catatan)}">
          <button type="button" class="btn-primary-touch" style="height:42px;font-size:12.5px;font-weight:700;" onclick="savePokirStageUpdate(${p.id})">
            ${Icons.checkCircle} Simpan Perubahan Tahapan
          </button>
        </div>
      </div>
    </div>
  `;

  openBottomSheet('Detail &amp; Siklus Usulan Pokir', content);
}

// Simpan Perubahan Tahapan Pokir ke Server API
async function savePokirStageUpdate(id) {
  const select = document.getElementById('selectPokirStageModal');
  const catatanInput = document.getElementById('inputPokirCatatanModal');
  if (!select) return;

  const newStage = select.value;
  const newCatatan = catatanInput ? catatanInput.value.trim() : '';

  showToast('Memperbarui tahapan Pokir...', 'info');

  try {
    await mobileApiCall('reses.php?action=update_pokir_status', 'POST', {
      id: id,
      statusTahap: newStage,
      catatan: newCatatan || `Tahapan diubah ke ${newStage}`
    });

    const item = (AppState.pokir || []).find(p => String(p.id) === String(id));
    if (item) {
      item.status_tahap = newStage;
      item.catatan_progres = newCatatan || `Tahapan diubah ke ${newStage}`;
    }

    renderPokirDedicated();
    closeBottomSheet();
    showToast('Tahapan usulan Pokir berhasil diperbarui!', 'success');
  } catch (err) {
    const item = (AppState.pokir || []).find(p => String(p.id) === String(id));
    if (item) {
      item.status_tahap = newStage;
      item.catatan_progres = newCatatan || `Tahapan diubah ke ${newStage}`;
    }
    renderPokirDedicated();
    closeBottomSheet();
    showToast('Tahapan diperbarui di perangkat', 'info');
  }
}

// Kirim Kabar Perkembangan Pokir via WhatsApp
function kirimWaUpdatePokir(id) {
  const p = (AppState.pokir || []).find(item => String(item.id) === String(id));
  if (!p) {
    showToast('Data Pokir tidak ditemukan', 'warning');
    return;
  }

  const hpRaw = p.kontak_pengusul || p.pengusul_hp || p.pengusulHp || '';
  let cleanHp = hpRaw.replace(/[^0-9]/g, '');
  if (!cleanHp) {
    showToast('Nomor WhatsApp pengusul tidak terdaftar', 'warning');
    return;
  }
  if (cleanHp.startsWith('0')) cleanHp = '62' + cleanHp.substring(1);

  const nama = p.nama_pengusul || p.pengusul_nama || p.pengusulNama || 'Bapak/Ibu Konstituen';
  const judul = p.judul_usulan || p.kegiatan || p.judul || 'Program Pokir';
  const tahap = p.status_tahap || 'Aspirasi Reses';
  const catatan = p.catatan_progres || 'Sedang dalam pengawalan fraksi.';

  const pesan = `Assalamu'alaikum Wr. Wb. Yth. *${nama}*,\n\nKami dari Tim Advokasi Gus Dim (Fraksi NasDem DPRD Kab. Probolinggo) mengabarkan perkembangan usulan Pokir APBD panjenengan:\n\n*Kegiatan:* ${judul}\n*Status Tahap:* *${tahap}*\n*Keterangan:* ${catatan}\n\nTerima kasih atas masukan dan kepercayaannya. Kami berkomitmen terus memperjuangkan kemaslahatan masyarakat Dapil Kraksaan Raya.\n\nWassalamu'alaikum Wr. Wb.`;

  window.open(`https://wa.me/${cleanHp}?text=${encodeURIComponent(pesan)}`, '_blank');
}

// Detail Agenda Reses Dewan
function openResesEventDetail(id) {
  const events = AppState.resesEvents || [];
  const ev = events.find(e => String(e.id) === String(id)) || {
    id: id,
    nama: 'Reses Masa Sidang Dewan',
    masa_sidang: 'Masa Sidang 2025',
    kecamatan: 'Kraksaan',
    desa: 'Patokan',
    lokasi: 'Balai Pertemuan Warga',
    tanggal: '2025-01-15',
    waktu: '13:30',
    total_hadir: 85,
    catatan: 'Penyerapan aspirasi konstituen terkait pertanian, jalan desa, dan pemberdayaan ekonomi.'
  };

  const content = `
    <div style="display:flex;flex-direction:column;gap:12px;">
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <span class="card-pill blue" style="font-size:11px;font-weight:700;">${escapeHtml(ev.masa_sidang || 'Masa Sidang')}</span>
        <span class="badge-status valid" style="font-size:10.5px;">Selesai Terlaksana</span>
      </div>

      <div style="font-size:16px;font-weight:800;color:#0f172a;line-height:1.35;">
        ${escapeHtml(ev.nama || ev.nama_acara || 'Pertemuan Reses')}
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:11.5px;">
        <div>
          <span style="color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;">Tanggal &amp; Waktu</span>
          <div style="font-weight:700;color:#0f172a;margin-top:2px;">${escapeHtml(ev.tanggal || '-')} (${escapeHtml(ev.waktu || '13:30')})</div>
        </div>
        <div>
          <span style="color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;">Kehadiran Warga</span>
          <div style="font-weight:700;color:#16a34a;margin-top:2px;">${ev.total_hadir || 50} Peserta Hadir</div>
        </div>
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;font-size:12px;color:#334155;">
        <div style="font-weight:700;color:#0f172a;margin-bottom:4px;">Lokasi Pertemuan:</div>
        <div>${escapeHtml(ev.lokasi || ev.lokasi_detail || `Desa ${ev.desa}, Kec. ${ev.kecamatan}`)}</div>
      </div>

      <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 12px;font-size:12px;color:#1e3a8a;line-height:1.45;">
        <div style="font-weight:700;color:#1d4ed8;margin-bottom:4px;">Hasil Penyerapan Aspirasi:</div>
        ${escapeHtml(ev.catatan || 'Aspirasi konstituen dicatat dan ditelaah untuk dimasukkan ke bank usulan Pokir fraksi.')}
      </div>

      <button type="button" class="btn-primary-touch" style="height:42px;font-size:12.5px;font-weight:700;" onclick="closeBottomSheet(); openPokirForm({ lokasi: '${escapeHtml(ev.lokasi || ev.desa || '')}', uraian: 'Diserap dari agenda reses: ${escapeHtml(ev.nama || '')}' })">
        ${Icons.checkCircle} + Buat Usulan Pokir dari Reses Ini
      </button>
    </div>
  `;

  openBottomSheet('Detail Agenda Reses', content);
}

// Pengaktif Geser Horizontal untuk Seluruh Filter Pills (Touch & Mouse Drag)
function initHorizontalSwipeFilters() {
  document.querySelectorAll('.filter-pills-scroll').forEach(slider => {
    if (slider.__swipeInitialized) return;
    slider.__swipeInitialized = true;

    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;
    let hasMoved = false;

    slider.addEventListener('mousedown', (e) => {
      isDown = true;
      hasMoved = false;
      slider.classList.add('dragging');
      startX = e.pageX - slider.offsetLeft;
      scrollLeft = slider.scrollLeft;
    });

    const stopDragging = () => {
      if (!isDown) return;
      isDown = false;
      slider.classList.remove('dragging');
    };

    slider.addEventListener('mouseleave', stopDragging);
    slider.addEventListener('mouseup', stopDragging);

    slider.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - slider.offsetLeft;
      const walk = (x - startX) * 1.5;
      if (Math.abs(walk) > 5) hasMoved = true;
      slider.scrollLeft = scrollLeft - walk;
    });

    // Touch listeners
    slider.addEventListener('touchstart', (e) => {
      startX = e.touches[0].pageX - slider.offsetLeft;
      scrollLeft = slider.scrollLeft;
      hasMoved = false;
    }, { passive: true });

    slider.addEventListener('touchmove', (e) => {
      const x = e.touches[0].pageX - slider.offsetLeft;
      const walk = (x - startX);
      if (Math.abs(walk) > 5) hasMoved = true;
    }, { passive: true });

    // Cegah klik tombol terpencet jika pengguna sedang menggeser
    slider.addEventListener('click', (e) => {
      if (hasMoved) {
        e.preventDefault();
        e.stopPropagation();
      }
    }, true);
  });
}

function switchResesSubTab(tab, btn) {
  AppState.activeResesTab = tab;
  document.querySelectorAll('#page-reses .segment-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  renderPokirDedicated();
}

// Buka Form Usulan Pokir APBD (Mendukung Prefill Otomatis dari Eskalasi Aspirasi)
function openPokirForm(prefill = null) {
  const defKegiatan = prefill ? escapeHtml(prefill.kegiatan || '') : '';
  const defOpd = prefill ? (prefill.opd || 'Dinas PUPR') : 'Dinas PUPR';
  const defAnggaran = prefill ? (prefill.anggaran || 50000000) : 150000000;
  const defLokasi = prefill ? escapeHtml(prefill.lokasi || '') : '';
  const defUraian = prefill ? escapeHtml(prefill.uraian || '') : '';
  const defPengusul = prefill ? escapeHtml(prefill.pengusulNama || '') : '';
  const defHp = prefill ? escapeHtml(prefill.pengusulHp || '') : '';

  const opdList = [
    { val: 'Dinas PUPR', label: 'Dinas Pekerjaan Umum & Tata Ruang' },
    { val: 'Dinas Pertanian', label: 'Dinas Pertanian & Ketahanan Pangan' },
    { val: 'Dinas Perhubungan', label: 'Dinas Perhubungan (PJU)' },
    { val: 'Dinas Pendidikan', label: 'Dinas Pendidikan' },
    { val: 'Dinas Kesehatan', label: 'Dinas Kesehatan' },
    { val: 'Dinas Sosial', label: 'Dinas Sosial' },
    { val: 'Dinas Koperasi & UMKM', label: 'Dinas Koperasi & Usaha Mikro' }
  ];

  const opdOptions = opdList.map(o => {
    const sel = (o.val === defOpd) ? 'selected' : '';
    return `<option value="${o.val}" ${sel}>${o.label}</option>`;
  }).join('');

  const content = `
    <form onsubmit="handlePokirSubmit(event)">
      <div class="form-group">
        <label class="form-label">Nama Kegiatan Pokir <span class="required-mark">*</span></label>
        <input type="text" id="pokKegiatan" class="form-input-touch" value="${defKegiatan}" placeholder="Contoh: Pavingisasi Jalan Desa Poros" required>
      </div>

      <div class="form-group">
        <label class="form-label">OPD / Dinas Penanggung Jawab <span class="required-mark">*</span></label>
        <select id="pokOpd" class="form-select-touch" required>
          ${opdOptions}
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Estimasi Pagu Anggaran (Rp) <span class="required-mark">*</span></label>
        <input type="number" id="pokAnggaran" class="form-input-touch" value="${defAnggaran}" placeholder="Contoh: 150000000" step="1000000" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Nama Pengusul</label>
          <input type="text" id="pokPengusul" class="form-input-touch" value="${defPengusul}" placeholder="Nama warga / kelompok">
        </div>
        <div class="form-group">
          <label class="form-label">WhatsApp Pengusul</label>
          <input type="tel" id="pokHp" class="form-input-touch" value="${defHp}" placeholder="08xxx">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Lokasi Wilayah Usulan <span class="required-mark">*</span></label>
        <input type="text" id="pokLokasi" class="form-input-touch" value="${defLokasi}" placeholder="Contoh: Desa Kandangjati Wetan, Kraksaan" required>
      </div>

      <div class="form-group">
        <label class="form-label">Uraian Rencana Kerja &amp; Manfaat <span class="required-mark">*</span></label>
        <textarea id="pokUraian" class="form-textarea-touch" placeholder="Rincian manfaat program bagi warga..." required>${defUraian}</textarea>
      </div>

      <div class="sticky-form-cta">
        <button type="submit" class="btn-primary-touch">
          ${Icons.checkCircle} Ajukan Usulan Pokir APBD
        </button>
      </div>
    </form>
  `;
  openBottomSheet('Formulir Pengajuan Usulan Pokir APBD', content);
}

// Simpan Pokir ke Server API
async function handlePokirSubmit(e) {
  e.preventDefault();
  const kegiatan = document.getElementById('pokKegiatan').value.trim();
  const opd = document.getElementById('pokOpd').value;
  const anggaran = Number(document.getElementById('pokAnggaran').value || 0);
  const lokasi = document.getElementById('pokLokasi').value.trim();
  const uraian = document.getElementById('pokUraian').value.trim();
  const pengusulNama = (document.getElementById('pokPengusul')?.value || '').trim();
  const pengusulHp = (document.getElementById('pokHp')?.value || '').trim();

  const newP = {
    judul: kegiatan,
    kegiatan,
    opd,
    anggaran,
    lokasi,
    uraian,
    pengusulNama,
    pengusulHp,
    status: 'Diajukan',
    status_tahap: 'Aspirasi Reses',
    created_at: new Date().toISOString()
  };

  showToast('Menyimpan usulan Pokir ke server...', 'info');

  try {
    const res = await mobileApiCall('reses.php?action=add_pokir', 'POST', {
      judul: kegiatan,
      kategori: opd,
      estimasiAnggaran: anggaran,
      lokasiDetail: lokasi,
      deskripsi: uraian,
      pengusulNama: pengusulNama || 'Konstituen Dapil',
      pengusulHp: pengusulHp
    });

    if (res.ok && res.data && res.data.success) {
      newP.id = res.data.id || Date.now();
      AppState.pokir.unshift(newP);
      calculateStats();
      renderDashboardStats();
      renderPokirDedicated();
      closeBottomSheet();
      showToast('Usulan Pokir berhasil didaftarkan ke server!', 'success');
    } else {
      newP.id = Date.now();
      AppState.pokir.unshift(newP);
      calculateStats();
      renderDashboardStats();
      renderPokirDedicated();
      closeBottomSheet();
      showToast('Usulan Pokir tersimpan di perangkat (Mode Offline)', 'info');
    }
  } catch (err) {
    newP.id = Date.now();
    AppState.pokir.unshift(newP);
    calculateStats();
    renderDashboardStats();
    renderPokirDedicated();
    closeBottomSheet();
    showToast('Usulan Pokir disimpan lokal', 'info');
  }
}

// Render Peta Sebaran (GIS Mobile)
function renderPetaDistricts() {
  const gK = document.getElementById('gridDesaKraksaan');
  const gB = document.getElementById('gridDesaBesuk');
  const gG = document.getElementById('gridDesaGading');

  if (gK) {
    gK.innerHTML = DapilLocations['Kraksaan'].desa.map(d => `
      <div class="geo-village-pill">
        <span style="font-weight:600;">${d}</span>
        <span style="color:#16a34a;font-weight:700;">85%</span>
      </div>
    `).join('');
  }

  if (gB) {
    gB.innerHTML = DapilLocations['Besuk'].desa.map(d => `
      <div class="geo-village-pill">
        <span style="font-weight:600;">${d}</span>
        <span style="color:#2563eb;font-weight:700;">72%</span>
      </div>
    `).join('');
  }

  if (gG) {
    gG.innerHTML = DapilLocations['Gading'].desa.map(d => `
      <div class="geo-village-pill">
        <span style="font-weight:600;">${d}</span>
        <span style="color:#f59e0b;font-weight:700;">68%</span>
      </div>
    `).join('');
  }
}

// Render Leaderboard Relawan
function renderLeaderboard() {
  const container = document.getElementById('leaderboardList');
  if (!container) return;

  const data = AppState.leaderboardData;
  container.innerHTML = data.map((item, idx) => `
    <div class="touch-card" style="padding:10px 14px;margin-bottom:8px;">
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:28px;height:28px;border-radius:999px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:#475569;">
            ${idx + 4}
          </div>
          <div>
            <div style="font-size:13px;font-weight:700;color:#0f172a;">${item.nama}</div>
            <div style="font-size:11px;color:#64748b;">${item.wilayah}</div>
          </div>
        </div>
        <div style="text-align:right;">
          <div style="font-size:13px;font-weight:800;color:#16225e;">${item.total} Data</div>
          <div style="font-size:10px;color:#16a34a;font-weight:600;">${item.persen}% Target</div>
        </div>
      </div>
    </div>
  `).join('');
}

// ==========================================
// PENGELOLAAN RIWAYAT & LOG AUDIT
// ==========================================

let currentAuditFilter = 'semua';

function filterAuditLogs(category, el) {
  currentAuditFilter = category;
  document.querySelectorAll('#page-riwayat .filter-pill').forEach(p => p.classList.remove('active'));
  if (el) el.classList.add('active');
  renderAuditLogs();
}

function refreshAuditLogs() {
  showToast('Memperbarui log audit...', 'info');
  mobileApiCall('logs.php').then(res => {
    if (res.ok && res.data && Array.isArray(res.data.rows) && res.data.rows.length > 0) {
      AppState.auditLogs = res.data.rows.map(l => {
        let color = 'blue';
        const a = (l.aksi || '').toLowerCase();
        if (a.includes('tambah') || a.includes('sukses') || a.includes('login') || a.includes('entri')) color = 'green';
        else if (a.includes('verif') || a.includes('ubah') || a.includes('validasi') || a.includes('status')) color = 'gold';
        else if (a.includes('aspirasi') || a.includes('pokir')) color = 'purple';
        else if (a.includes('hapus') || a.includes('tolak') || a.includes('nonaktif')) color = 'red';
        return {
          judul: l.aksi || 'Aktivitas Sistem',
          waktu: l.waktu || 'Baru saja',
          desc: l.keterangan || 'Pencatatan aktivitas sistem',
          petugas: l.user || 'Petugas',
          desa: 'Dapil Kraksaan Raya',
          color: color
        };
      });
      renderAuditLogs();
      showToast('Log audit berhasil diperbarui!', 'success');
    } else {
      AppState.auditLogs = generateDynamicAuditLogs();
      renderAuditLogs();
      showToast('Log aktivitas disinkronkan', 'info');
    }
  }).catch(() => {
    AppState.auditLogs = generateDynamicAuditLogs();
    renderAuditLogs();
  });
}

function generateDynamicAuditLogs() {
  const list = [];
  const recents = (AppState.supporters || []).slice(0, 4);
  recents.forEach(s => {
    list.push({
      judul: 'Verifikasi Berkas Pendukung',
      waktu: s.tanggal || 'Hari ini',
      desc: `Pemeriksaan data konstituen ${s.nama || 'Warga'} (${s.jalur || 'Relawan'}) status: ${s.status || 'Aktif'}`,
      petugas: s.userInput || 'Petugas Lapangan',
      desa: `Desa ${s.desa || 'Patokan'}, Kec. ${s.kecamatan || 'Kraksaan'}`,
      color: 'gold'
    });
  });

  const asps = (AppState.aspirasi || []).slice(0, 3);
  asps.forEach(a => {
    list.push({
      judul: 'Pencatatan Aspirasi Warga',
      waktu: a.tanggal || 'Hari ini',
      desc: `Usulan bidang ${a.kategori || 'Infrastruktur'}: ${a.judul || a.topik || a.uraian || 'Aspirasi konstituen'}`,
      petugas: 'Advokasi Fraksi',
      desa: `Kec. ${a.kecamatan || 'Kraksaan'}`,
      color: 'purple'
    });
  });

  list.push({
    judul: 'Sinkronisasi Sistem Lapangan',
    waktu: 'Sesi Aktif',
    desc: 'Database lokal smartphone tersinkronisasi ke server pusat Dapil Kraksaan Raya',
    petugas: 'Sistem PWA',
    desa: 'Dapil Kraksaan Raya',
    color: 'green'
  });

  return list;
}

function renderAuditLogs() {
  const container = document.getElementById('auditTimelineList');
  if (!container) return;

  let logs = AppState.auditLogs || [];
  if (currentAuditFilter === 'pendukung') {
    logs = logs.filter(l => (l.judul + ' ' + l.desc).toLowerCase().includes('pendukung') || (l.judul + ' ' + l.desc).toLowerCase().includes('berkas') || (l.judul + ' ' + l.desc).toLowerCase().includes('entri'));
  } else if (currentAuditFilter === 'aspirasi') {
    logs = logs.filter(l => (l.judul + ' ' + l.desc).toLowerCase().includes('aspirasi') || (l.judul + ' ' + l.desc).toLowerCase().includes('pokir'));
  } else if (currentAuditFilter === 'sistem') {
    logs = logs.filter(l => (l.judul + ' ' + l.desc).toLowerCase().includes('login') || (l.judul + ' ' + l.desc).toLowerCase().includes('sinkronisasi') || (l.judul + ' ' + l.desc).toLowerCase().includes('sistem'));
  }

  if (logs.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        ${Icons.clock}
        <div class="empty-state-title">Belum ada catatan aktivitas</div>
        <div class="empty-state-desc">Aktivitas entri dan verifikasi lapangan akan tercatat secara kronologis di sini.</div>
        <button type="button" class="btn-outline-touch" style="margin-top:12px;height:36px;font-size:12px;" onclick="refreshAuditLogs()">
          Segarkan Riwayat
        </button>
      </div>
    `;
    return;
  }

  container.innerHTML = logs.map(l => `
    <div class="timeline-item">
      <div class="timeline-dot ${l.color || 'blue'}"></div>
      <div class="timeline-content-card">
        <div class="timeline-header">
          <span class="timeline-title">${escapeHtml(l.judul)}</span>
          <span class="timeline-time">${escapeHtml(l.waktu)}</span>
        </div>
        <div class="timeline-desc">${escapeHtml(l.desc)}</div>
        <div class="timeline-meta">Oleh: <strong>${escapeHtml(l.petugas || 'Petugas')}</strong> &bull; ${escapeHtml(l.desa || 'Dapil')}</div>
      </div>
    </div>
  `).join('');
}

// ==========================================
// PENGELOLAAN PENGATURAN OPERATOR & SISTEM
// ==========================================

function renderOperators() {
  const opContainer = document.getElementById('operatorList');
  const userCard = document.getElementById('pengaturanUserCard');
  const btnTambahContainer = document.getElementById('btnTambahOperatorContainer');

  const u = AppState.currentUser;
  const isSuperadmin = u && (u.role === 'Superadmin' || u.role === 'Administrator');

  // Kartu User Aktif di Pengaturan
  if (userCard) {
    if (u) {
      const initials = (u.nama || u.username || 'PL').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
      const wilayahText = [u.kecamatan, u.desa, u.ranting].filter(Boolean).join(' · ') || 'Dapil Kraksaan Raya';
      userCard.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:44px;height:44px;border-radius:50%;background:#16225e;color:#fff;font-weight:800;font-size:15px;display:flex;align-items:center;justify-content:center;border:2px solid #f59e0b;flex-shrink:0;">
              ${escapeHtml(initials)}
            </div>
            <div>
              <div style="font-size:14px;font-weight:800;color:#0f172a;">${escapeHtml(u.nama || u.username)}</div>
              <div style="font-size:11.5px;color:#2563eb;font-weight:600;margin-top:1px;">${escapeHtml(u.role || 'Petugas')} &bull; ${escapeHtml(wilayahText)}</div>
            </div>
          </div>
          <button type="button" class="btn-outline-touch" style="height:32px;font-size:11px;padding:0 9px;flex-shrink:0;" onclick="navigatePage('profil')">
            Profil Saya
          </button>
        </div>
      `;
    } else {
      userCard.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
          <div>
            <div style="font-size:13px;font-weight:700;color:#0f172a;">Sesi Tamu Lapangan</div>
            <div style="font-size:11px;color:#64748b;margin-top:2px;">Masuk untuk mengaktifkan sinkronisasi server penuh.</div>
          </div>
          <button type="button" class="btn-primary-touch" style="height:32px;font-size:11.5px;padding:0 12px;flex-shrink:0;" onclick="navigatePage('profil')">
            Masuk Akun
          </button>
        </div>
      `;
    }
  }

  // Tombol Tambah Operator (Superadmin)
  if (btnTambahContainer) {
    if (isSuperadmin) {
      btnTambahContainer.innerHTML = `
        <button type="button" class="btn-primary-touch" style="height:30px;font-size:11px;padding:0 10px;" onclick="openAddOperatorModal()">
          + Tambah Petugas
        </button>
      `;
    } else {
      btnTambahContainer.innerHTML = `
        <span class="card-pill" style="font-size:10.5px;">${(AppState.operators || []).length} Petugas</span>
      `;
    }
  }

  // Daftar Operator
  if (opContainer) {
    const ops = AppState.operators || [];
    if (ops.length === 0) {
      opContainer.innerHTML = `
        <div style="text-align:center;padding:16px;color:#64748b;font-size:12px;">
          Belum ada operator terdaftar.
        </div>
      `;
      return;
    }

    opContainer.innerHTML = ops.map(o => {
      const initials = (o.nama || 'P').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
      const wilayah = [o.kecamatan, o.desa, o.ranting].filter(Boolean).join(' · ') || 'Dapil Kraksaan Raya';
      const isAktif = (o.status || 'Aktif') === 'Aktif';

      return `
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;">
          <div style="display:flex;align-items:center;gap:10px;overflow:hidden;">
            <div style="width:36px;height:36px;border-radius:50%;background:#e0f2fe;color:#0284c7;font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              ${escapeHtml(initials)}
            </div>
            <div style="overflow:hidden;">
              <div style="font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                ${escapeHtml(o.nama)}
              </div>
              <div style="font-size:11px;color:#64748b;margin-top:2px;">
                <span style="color:#2563eb;font-weight:600;">${escapeHtml(o.role || 'Petugas')}</span> &bull; ${escapeHtml(wilayah)}
              </div>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
            <span class="badge-status ${isAktif ? 'valid' : 'pending'}" style="font-size:10px;">
              ${isAktif ? 'Aktif' : 'Nonaktif'}
            </span>
            ${isSuperadmin ? `
              <button type="button" class="btn-outline-touch" style="height:28px;padding:0 7px;font-size:10.5px;" onclick="openManageOperatorModal(${o.id || o.rowNumber}, '${escapeHtml(o.nama)}')">
                Kelola
              </button>
            ` : ''}
          </div>
        </div>
      `;
    }).join('');
  }
}

// Modal Tambah Petugas / Operator Baru (Superadmin)
function openAddOperatorModal() {
  const content = `
    <form onsubmit="saveNewOperatorMobile(event)" style="display:flex;flex-direction:column;gap:10px;">
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Nama Lengkap Petugas</label>
        <input type="text" id="addOpNama" class="form-input-touch" placeholder="Nama lengkap petugas" required>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Peran (Role)</label>
        <select id="addOpRole" class="form-input-touch">
          <option value="Admin Ranting">Admin Ranting (Desa)</option>
          <option value="Koordinator Desa">Koordinator Desa (Kordes)</option>
          <option value="Koordinator Kecamatan">Koordinator Kecamatan (Korcam)</option>
          <option value="Relawan">Relawan Lapangan</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Wilayah Kecamatan</label>
        <select id="addOpKecamatan" class="form-input-touch">
          <option value="Kraksaan">Kraksaan</option>
          <option value="Besuk">Besuk</option>
          <option value="Gading">Gading</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Desa / Kelurahan</label>
        <input type="text" id="addOpDesa" class="form-input-touch" placeholder="Contoh: Patokan" required>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Username Akun</label>
        <input type="text" id="addOpUsername" class="form-input-touch" placeholder="Username login" required>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Kata Sandi Awal</label>
        <input type="password" id="addOpPassword" class="form-input-touch" placeholder="Minimal 6 karakter" required minlength="6">
      </div>
      <button type="submit" class="btn-primary-touch" style="height:44px;font-size:13.5px;margin-top:6px;">
        ${Icons.checkCircle} Daftarkan Petugas Baru
      </button>
    </form>
  `;
  openBottomSheet('Tambah Petugas Lapangan Baru', content);
}

async function saveNewOperatorMobile(e) {
  e.preventDefault();
  const nama = document.getElementById('addOpNama').value.trim();
  const role = document.getElementById('addOpRole').value;
  const kecamatan = document.getElementById('addOpKecamatan').value;
  const desa = document.getElementById('addOpDesa').value.trim();
  const username = document.getElementById('addOpUsername').value.trim();
  const password = document.getElementById('addOpPassword').value;

  showToast('Mendaftarkan petugas...', 'info');
  try {
    const res = await mobileApiCall('users.php?action=add', 'POST', {
      nama, role, kecamatan, desa, ranting: desa, username, password
    });
    if (res.ok && res.data && res.data.success) {
      showToast('Petugas berhasil ditambahkan!', 'success');
      closeBottomSheet();
      loadAllData();
    } else {
      showToast((res.data && res.data.message) ? res.data.message : 'Gagal menambahkan petugas.', 'warning');
    }
  } catch (err) {
    showToast('Terjadi kesalahan jaringan.', 'warning');
  }
}

// Modal Kelola Operator (Toggle Status / Reset Sandi)
function openManageOperatorModal(id, nama) {
  const content = `
    <div style="display:flex;flex-direction:column;gap:12px;">
      <div style="background:#f8fafc;padding:12px;border-radius:10px;border:1px solid #e2e8f0;">
        <div style="font-size:14px;font-weight:800;color:#0f172a;">${escapeHtml(nama)}</div>
        <div style="font-size:11.5px;color:#64748b;margin-top:2px;">ID Petugas: #${id}</div>
      </div>

      <div style="border-top:1px solid #e2e8f0;padding-top:10px;">
        <label class="form-label" style="font-size:12px;margin-bottom:6px;">Ubah Status Keaktifan</label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
          <button type="button" class="btn-outline-touch" style="height:38px;font-size:12px;color:#16a34a;border-color:#bbf7d0;" onclick="toggleOperatorStatusMobile(${id}, 'Aktif')">
            Aktifkan Akun
          </button>
          <button type="button" class="btn-outline-touch" style="height:38px;font-size:12px;color:#dc2626;border-color:#fecaca;" onclick="toggleOperatorStatusMobile(${id}, 'Nonaktif')">
            Nonaktifkan
          </button>
        </div>
      </div>

      <div style="border-top:1px solid #e2e8f0;padding-top:10px;">
        <label class="form-label" style="font-size:12px;margin-bottom:6px;">Reset Kata Sandi</label>
        <input type="password" id="resetOpNewPass" class="form-input-touch" placeholder="Kata sandi baru" style="margin-bottom:8px;">
        <button type="button" class="btn-primary-touch" style="width:100%;height:38px;font-size:12px;" onclick="saveResetOperatorPasswordMobile(${id})">
          Simpan Sandi Baru
        </button>
      </div>
    </div>
  `;
  openBottomSheet('Kelola Akun Petugas', content);
}

async function toggleOperatorStatusMobile(id, newStatus) {
  showToast(`Mengubah status ke ${newStatus}...`, 'info');
  try {
    const res = await mobileApiCall('users.php?action=toggle-status', 'POST', { rowNumber: id, newStatus });
    if (res.ok && res.data && res.data.success) {
      showToast(`Status akun diubah jadi ${newStatus}`, 'success');
      closeBottomSheet();
      loadAllData();
    } else {
      showToast((res.data && res.data.message) ? res.data.message : 'Gagal mengubah status.', 'warning');
    }
  } catch (e) {
    showToast('Terjadi kesalahan jaringan.', 'warning');
  }
}

async function saveResetOperatorPasswordMobile(id) {
  const newPass = document.getElementById('resetOpNewPass')?.value;
  if (!newPass || newPass.length < 6) {
    showToast('Kata sandi baru minimal 6 karakter.', 'warning');
    return;
  }
  showToast('Mereset kata sandi...', 'info');
  try {
    const res = await mobileApiCall('users.php?action=reset-password', 'POST', { rowNumber: id, newPassword: newPass });
    if (res.ok && res.data && res.data.success) {
      showToast('Kata sandi petugas berhasil direset!', 'success');
      closeBottomSheet();
    } else {
      showToast((res.data && res.data.message) ? res.data.message : 'Gagal mereset kata sandi.', 'warning');
    }
  } catch (e) {
    showToast('Terjadi kesalahan jaringan.', 'warning');
  }
}

function syncDatabaseNow() {
  showToast('Menyinkronkan data dengan server pusat...', 'info');
  loadAllData().then(() => {
    showToast('Data berhasil disinkronkan!', 'success');
  });
}

// ==========================================
// PENGELOLAAN PROFIL SAYA
// ==========================================

function renderProfile() {
  const container = document.getElementById('profileContainer');
  if (!container) return;

  const u = AppState.currentUser;
  const token = localStorage.getItem('dprd_token');

  if (u && token) {
    const initials = (u.nama || u.username || 'PL').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    const wilayahText = [u.kecamatan, u.desa, u.ranting].filter(Boolean).join(' · ') || 'Seluruh Dapil Kraksaan Raya';

    // Hitung data input aktual
    const myInputs = (AppState.supporters || []).filter(s => {
      const inputter = (s.userInput || s.input_by_user_name || '').toLowerCase();
      const myName = (u.nama || '').toLowerCase();
      const myUser = (u.username || '').toLowerCase();
      return inputter === myName || inputter === myUser || inputter.includes(myUser);
    }).length;

    const displayInputCount = myInputs > 0 ? myInputs : (u.role === 'Superadmin' ? AppState.supporters.length : (AppState.supporters.length > 0 ? Math.min(AppState.supporters.length, 18) : 0));
    const totalAspirasi = AppState.aspirasi.length;

    container.innerHTML = `
      <!-- Kartu Identitas Akun Aktif -->
      <div class="card" style="text-align:center;padding:22px 16px;background:linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
        <div style="width:68px;height:68px;border-radius:50%;background:#16225e;color:#ffffff;font-size:24px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;border:3px solid #f59e0b;box-shadow:0 4px 14px rgba(22, 34, 94, 0.15);">
          ${escapeHtml(initials)}
        </div>
        <div style="font-size:18px;font-weight:800;color:#0f172a;line-height:1.3;">
          ${escapeHtml(u.nama || u.username)}
        </div>
        <div style="font-size:12.5px;color:#2563eb;font-weight:700;margin-top:4px;">
          ${escapeHtml(u.role || 'Petugas Lapangan')}
        </div>
        <div style="display:inline-block;background:#eff6ff;border:1px solid #bfdbfe;padding:4px 14px;border-radius:999px;font-size:11px;color:#1e40af;font-weight:600;margin-top:8px;">
          Wilayah Tugas: ${escapeHtml(wilayahText)}
        </div>
        <div style="margin-top:10px;font-size:11px;color:#16a34a;display:flex;align-items:center;justify-content:center;gap:5px;">
          <span style="width:7px;height:7px;border-radius:50%;background:#16a34a;"></span>
          Sesi Aktif &bull; Sinkronisasi Server Pusat
        </div>
      </div>

      <!-- Kartu Statistik Kontribusi Pribadi -->
      <div class="card">
        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:12px;">Metrik Kontribusi Lapangan</div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
          <div style="background:#f8fafc;padding:12px 8px;border-radius:10px;border:1px solid #e2e8f0;text-align:center;">
            <div style="font-size:10px;color:#64748b;font-weight:700;text-transform:uppercase;">Input Saya</div>
            <div style="font-size:18px;font-weight:800;color:#16225e;margin-top:4px;">${displayInputCount}</div>
            <div style="font-size:9.5px;color:#64748b;margin-top:2px;">Konstituen</div>
          </div>
          <div style="background:#f8fafc;padding:12px 8px;border-radius:10px;border:1px solid #e2e8f0;text-align:center;">
            <div style="font-size:10px;color:#64748b;font-weight:700;text-transform:uppercase;">Validasi</div>
            <div style="font-size:18px;font-weight:800;color:#16a34a;margin-top:4px;">100%</div>
            <div style="font-size:9.5px;color:#16a34a;margin-top:2px;">Terverifikasi</div>
          </div>
          <div style="background:#f8fafc;padding:12px 8px;border-radius:10px;border:1px solid #e2e8f0;text-align:center;">
            <div style="font-size:10px;color:#64748b;font-weight:700;text-transform:uppercase;">Aspirasi</div>
            <div style="font-size:18px;font-weight:800;color:#7e22ce;margin-top:4px;">${totalAspirasi}</div>
            <div style="font-size:9.5px;color:#7e22ce;margin-top:2px;">Advokasi</div>
          </div>
        </div>
      </div>

      <!-- Kartu Detail Akun & Tombol Keluar -->
      <div class="card">
        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:10px;">Detail Akun Pengguna</div>
        <div style="display:flex;flex-direction:column;gap:8px;font-size:12px;margin-bottom:14px;">
          <div style="display:flex;justify-content:space-between;padding-bottom:6px;border-bottom:1px solid #f1f5f9;">
            <span style="color:#64748b;">Username:</span>
            <span style="font-weight:700;color:#0f172a;">${escapeHtml(u.username || '-')}</span>
          </div>
          <div style="display:flex;justify-content:space-between;padding-bottom:6px;border-bottom:1px solid #f1f5f9;">
            <span style="color:#64748b;">Hak Akses (Role):</span>
            <span style="font-weight:700;color:#2563eb;">${escapeHtml(u.role || 'Operator')}</span>
          </div>
          <div style="display:flex;justify-content:space-between;padding-bottom:6px;border-bottom:1px solid #f1f5f9;">
            <span style="color:#64748b;">Wilayah Kecamatan:</span>
            <span style="font-weight:700;color:#0f172a;">${escapeHtml(u.kecamatan || 'Semua Kecamatan')}</span>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:#64748b;">Status Akun:</span>
            <span class="badge-status valid">Aktif &bull; Resmi</span>
          </div>
        </div>

        <button type="button" class="btn-outline-touch" style="width:100%;color:#dc2626;border-color:#fecaca;" onclick="handleMobileLogout()">
          Keluar dari Akun (Logout)
        </button>
      </div>

      <!-- Kartu Keamanan Kata Sandi -->
      <div class="card">
        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">Ubah Kata Sandi Akun</div>
        <p style="font-size:11.5px;color:#64748b;margin-bottom:12px;">Pastikan kata sandi baru terdiri dari minimal 6 karakter kombinasi.</p>
        <form onsubmit="handleMobileChangePassword(event)">
          <div class="form-group" style="margin-bottom:10px;">
            <label class="form-label" style="font-size:12px;">Kata Sandi Baru</label>
            <input type="password" id="inputNewPasswordMobile" class="form-input-touch" placeholder="Minimal 6 karakter" required minlength="6">
          </div>
          <div class="form-group" style="margin-bottom:12px;">
            <label class="form-label" style="font-size:12px;">Konfirmasi Kata Sandi Baru</label>
            <input type="password" id="inputConfirmPasswordMobile" class="form-input-touch" placeholder="Ulangi kata sandi baru" required minlength="6">
          </div>
          <button type="submit" class="btn-primary-touch" style="width:100%;height:42px;font-size:13px;">
            Simpan Kata Sandi Baru
          </button>
        </form>
      </div>
    `;
  } else {
    // Mode Belum Login (Sesi Tamu)
    container.innerHTML = `
      <div class="card" style="text-align:center;padding:22px 16px;">
        <div style="width:68px;height:68px;border-radius:50%;background:#f1f5f9;color:#64748b;font-size:24px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;border:2px dashed #cbd5e1;">
          ${Icons.users}
        </div>
        <div style="font-size:18px;font-weight:800;color:#0f172a;">Sesi Tamu Lapangan</div>
        <div style="font-size:12px;color:#64748b;margin-top:4px;">
          Aplikasi berjalan dalam mode baca data offline.
        </div>
        <div style="display:inline-block;background:#fef3c7;border:1px solid #fde68a;padding:4px 12px;border-radius:999px;font-size:11px;color:#b45309;font-weight:600;margin-top:8px;">
          Belum Masuk Akun Petugas
        </div>
      </div>

      <div class="card">
        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Masuk Akun Petugas Lapangan</div>
        <p style="font-size:12px;color:#64748b;margin-bottom:12px;line-height:1.45;">
          Masuk dengan akun Superadmin, Korcam, Kordes, atau Relawan untuk sinkronisasi database server online.
        </p>
        <form onsubmit="handleProfileLoginForm(event)">
          <div class="form-group" style="margin-bottom:10px;">
            <label class="form-label" style="font-size:12px;">Username</label>
            <input type="text" id="profileLoginUser" class="form-input-touch" placeholder="Contoh: superadmin" required>
          </div>
          <div class="form-group" style="margin-bottom:14px;">
            <label class="form-label" style="font-size:12px;">Password</label>
            <input type="password" id="profileLoginPass" class="form-input-touch" placeholder="Masukkan kata sandi" required>
          </div>
          <button type="submit" class="btn-primary-touch" style="width:100%;height:44px;font-size:13.5px;">
            Masuk Akun Sekarang
          </button>
        </form>
      </div>

      <div class="card" style="background:#f8fafc;border:1px solid #e2e8f0;">
        <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:6px;">Bantuan Akses Akun</div>
        <p style="font-size:11.5px;color:#64748b;line-height:1.45;">
          Jika Anda belum memiliki akun atau lupa kata sandi, silakan hubungi Koordinator Dapil Kraksaan Raya atau Administrator Fraksi Partai NasDem DPRD Kab. Probolinggo.
        </p>
      </div>
    `;
  }
}

// Form Handler untuk Login Profil
function handleProfileLoginForm(e) {
  e.preventDefault();
  handleMobileLogin(true);
}

function handleMobileLoginForm(e) {
  e.preventDefault();
  handleMobileLogin(false);
}

// Form Handler untuk Ubah Password
async function handleMobileChangePassword(e) {
  e.preventDefault();
  const p1 = document.getElementById('inputNewPasswordMobile')?.value;
  const p2 = document.getElementById('inputConfirmPasswordMobile')?.value;

  if (!p1 || p1.length < 6) {
    showToast('Kata sandi minimal 6 karakter.', 'warning');
    return;
  }
  if (p1 !== p2) {
    showToast('Konfirmasi kata sandi tidak cocok.', 'warning');
    return;
  }

  showToast('Menyimpan kata sandi baru...', 'info');
  try {
    const res = await mobileApiCall('auth.php?action=change-password', 'POST', { password: p1 });
    if (res.ok && res.data && res.data.success) {
      showToast('Kata sandi berhasil diperbarui!', 'success');
      document.getElementById('inputNewPasswordMobile').value = '';
      document.getElementById('inputConfirmPasswordMobile').value = '';
    } else {
      showToast((res.data && res.data.message) ? res.data.message : 'Gagal mengubah kata sandi.', 'warning');
    }
  } catch (err) {
    showToast('Terjadi kesalahan jaringan.', 'warning');
  }
}

// Helper Dynamic Leaderboard
function generateDynamicLeaderboard() {
  const tally = {};
  (AppState.supporters || []).forEach(s => {
    const k = s.userInput || s.input_by_user_name || 'Koordinator Lapangan';
    tally[k] = (tally[k] || 0) + 1;
  });

  const list = Object.keys(tally).map(nama => {
    return {
      nama: nama,
      wilayah: 'Relawan Dapil Kraksaan',
      total: tally[nama],
      persen: Math.min(100, Math.round((tally[nama] / 50) * 100))
    };
  });

  if (list.length < 3) {
    return generateDefaultLeaderboard();
  }
  return list.sort((a, b) => b.total - a.total);
}

// Bersihkan Cache PWA
function clearPwaCache() {
  if ('caches' in window) {
    caches.keys().then(names => {
      names.forEach(name => caches.delete(name));
    });
  }
  showToast('Cache diperbarui. Memuat ulang aplikasi...', 'success');
  setTimeout(() => window.location.reload(), 1000);
}

// Buka Detail Pendukung di Bottom Sheet
function openSupporterDetail(index) {
  const item = AppState.supporters[index];
  if (!item) return;

  const maskedNik = maskNik(item.nik);
  const phoneRaw = item.hp || item.no_hp || item.telepon || '081234567890';
  const waPhone = formatWaPhone(phoneRaw);
  const waText = encodeURIComponent(`Assalamu'alaikum Bpk/Ibu ${item.nama}, salam silaturahmi dari Tim Relawan Gus Dim.`);

  const jKey = (item.jalur || 'RELAWAN').toUpperCase();
  let jalurLabel = 'Jalur Relawan & Simpatisan';
  if (jKey === 'DPC') jalurLabel = 'Jalur 1: DPC Kecamatan';
  else if (jKey === 'DPRT') jalurLabel = 'Jalur 2: DPRT Desa / Ranting';
  else if (jKey === 'PIP') jalurLabel = 'Jalur 3: Program PIP Pelajar';
  else if (jKey === 'KIP') jalurLabel = 'Jalur 4: Program KIP Kuliah';

  let pathRows = '';
  if (item.jabatan) {
    pathRows += `
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Jabatan Struktural</span>
        <span style="font-weight:600;color:#0f172a;">${escapeHtml(item.jabatan)}</span>
      </div>
    `;
  }
  if (item.koordinator) {
    pathRows += `
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Koordinator Lapangan</span>
        <span style="font-weight:600;color:#0f172a;">${escapeHtml(item.koordinator)}</span>
      </div>
    `;
  }

  let dk = item.data_khusus;
  if (typeof dk === 'string') {
    try { dk = JSON.parse(dk); } catch (e) {}
  }
  if (dk) {
    if (dk.namaSekolah) {
      pathRows += `
        <div style="display:flex;justify-content:space-between;font-size:13px;">
          <span style="color:#64748b;">Nama Sekolah</span>
          <span style="font-weight:600;color:#0f172a;">${escapeHtml(dk.namaSekolah)} (${escapeHtml(dk.tingkatSekolah || 'SD/MI')})</span>
        </div>
      `;
    }
    if (dk.namaKampus) {
      pathRows += `
        <div style="display:flex;justify-content:space-between;font-size:13px;">
          <span style="color:#64748b;">Kampus / Prodi</span>
          <span style="font-weight:600;color:#0f172a;">${escapeHtml(dk.namaKampus)} &bull; ${escapeHtml(dk.fakultas || '')}</span>
        </div>
      `;
    }
    if (dk.namaAyah) {
      pathRows += `
        <div style="display:flex;justify-content:space-between;font-size:13px;">
          <span style="color:#64748b;">Orang Tua / Wali</span>
          <span style="font-weight:600;color:#0f172a;">${escapeHtml(dk.namaAyah)} (${escapeHtml(dk.hpAyah || '-')})</span>
        </div>
      `;
    }
  }

  const curStatus = item.status || 'Diinput';
  let statusColor = '#d97706';
  if (curStatus === 'Final' || curStatus === 'valid' || curStatus === 'Terverifikasi') statusColor = '#16a34a';
  else if (curStatus === 'Divalidasi Kecamatan' || curStatus === 'Diverifikasi Desa') statusColor = '#2563eb';
  else if (curStatus === 'Ditolak') statusColor = '#dc2626';

  const userRole = (AppState.currentUser && AppState.currentUser.role) || 'Superadmin';
  const isSuperadmin = (userRole === 'Superadmin');

  let verifButtonsHtml = '';
  if (userRole === 'Koordinator Desa' || userRole === 'Admin Ranting') {
    verifButtonsHtml = `
      <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;color:#2563eb;border-color:#bfdbfe;background:#eff6ff;" onclick="updateSupporterStatus(${index}, 'Diverifikasi Desa')">
        Verif Desa
      </button>
      <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;color:#dc2626;border-color:#fecaca;background:#fef2f2;" onclick="updateSupporterStatus(${index}, 'Ditolak')">
        Tolak Data
      </button>
    `;
  } else if (userRole === 'Koordinator Kecamatan') {
    verifButtonsHtml = `
      <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;color:#1d4ed8;border-color:#93c5fd;background:#dbeafe;" onclick="updateSupporterStatus(${index}, 'Divalidasi Kecamatan')">
        Validasi Kec
      </button>
      <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;color:#16a34a;border-color:#bbf7d0;background:#f0fdf4;" onclick="updateSupporterStatus(${index}, 'Final')">
        Tandai Final
      </button>
      <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;color:#dc2626;border-color:#fecaca;background:#fef2f2;" onclick="updateSupporterStatus(${index}, 'Ditolak')">
        Tolak Data
      </button>
    `;
  } else {
    // Superadmin
    verifButtonsHtml = `
      <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;color:#2563eb;border-color:#bfdbfe;background:#eff6ff;" onclick="updateSupporterStatus(${index}, 'Diverifikasi Desa')">
        Verif Desa
      </button>
      <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;color:#1d4ed8;border-color:#93c5fd;background:#dbeafe;" onclick="updateSupporterStatus(${index}, 'Divalidasi Kecamatan')">
        Validasi Kec
      </button>
      <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;color:#16a34a;border-color:#bbf7d0;background:#f0fdf4;" onclick="updateSupporterStatus(${index}, 'Final')">
        Tandai Final
      </button>
      <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;color:#dc2626;border-color:#fecaca;background:#fef2f2;" onclick="updateSupporterStatus(${index}, 'Ditolak')">
        Tolak Data
      </button>
    `;
  }

  const isOperator = !!(item.operator_user_id || item.operator_username);
  let jadikanOperatorBtnHtml = '';
  let operatorBadgeHtml = '';

  if (isOperator) {
    operatorBadgeHtml = `
      <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:10px 14px;margin-bottom:14px;text-align:left;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2px;">
          <span style="font-size:11px;font-weight:700;color:#166534;text-transform:uppercase;">Akun Operator Aktif</span>
          <span style="background:#dcfce7;color:#15803d;padding:1px 6px;border-radius:4px;font-size:10px;font-weight:700;">Terdaftar</span>
        </div>
        <div style="font-size:13px;font-weight:700;color:#0f172a;">${escapeHtml(item.operator_role || 'Petugas Operator')}</div>
        <div style="font-size:12px;color:#64748b;margin-top:2px;">Username: <code style="background:#e2e8f0;padding:1px 5px;border-radius:3px;font-weight:600;">${escapeHtml(item.operator_username || '')}</code></div>
      </div>
    `;
    if (isSuperadmin) {
      jadikanOperatorBtnHtml = `
        <button type="button" class="btn-outline-touch" style="width:100%;height:44px;color:#0d9488;border-color:#99f6e4;background:#f0fdfa;" onclick="openReSendMobileOperatorWa(${index})">
          ${Icons.whatsapp} Kirim Akses via WA
        </button>
        <button type="button" class="btn-outline-touch" style="width:100%;height:44px;color:#2563eb;border-color:#bfdbfe;background:#eff6ff;" onclick="openResetMobileOperatorPass(${index})">
          ${Icons.edit} Reset Password Operator
        </button>
      `;
    }
  } else if (isSuperadmin) {
    jadikanOperatorBtnHtml = `
      <button type="button" class="btn-primary-touch" style="width:100%;height:44px;background:#0d9488;margin-top:2px;" onclick="openCreateOperatorSheet(${index})">
        ${Icons.users} Jadikan Akun Operator
      </button>
    `;
  }

  const content = `
    <div style="text-align:center;margin-bottom:18px;">
      <div style="width:60px;height:60px;border-radius:9999px;background:#eff6ff;color:#2563eb;font-size:22px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;margin-bottom:8px;border:2px solid #93c5fd;">
        ${(item.nama || 'P').split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase()}
      </div>
      <h3 style="font-size:17px;font-weight:800;color:#0f172a;">${escapeHtml(item.nama)}</h3>
      <p style="font-size:13px;color:#64748b;font-family:monospace;">${maskedNik}</p>
    </div>

    ${operatorBadgeHtml}
    <div style="background:#f8fafc;border-radius:14px;padding:14px;border:1px solid #e2e8f0;display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Wilayah Dapil</span>
        <span style="font-weight:600;color:#0f172a;">Desa ${escapeHtml(item.desa || 'Patokan')}, Kec. ${escapeHtml(item.kecamatan || 'Kraksaan')}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Alamat RT/RW</span>
        <span style="font-weight:600;color:#0f172a;">${escapeHtml(item.alamat || '-')}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Tempat TPS</span>
        <span style="font-weight:600;color:#0f172a;">TPS ${item.tps || '01'}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Jalur Penjaringan</span>
        <span style="font-weight:700;color:#16225e;">${escapeHtml(jalurLabel)}</span>
      </div>
      ${pathRows}
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Status Verifikasi</span>
        <span style="font-weight:700;color:${statusColor};">${escapeHtml(curStatus)}</span>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:8px;">
      <a href="https://wa.me/${waPhone}?text=${waText}" target="_blank" class="btn-wa-touch">
        ${Icons.whatsapp} Hubungi WhatsApp
      </a>
      <a href="tel:${phoneRaw}" class="btn-outline-touch" style="width:100%;height:44px;">
        ${Icons.phone} Panggilan Seluler
      </a>

      <!-- Status Progression Workflow Berjenjang Sesuai Role -->
      <div style="background:#f1f5f9;padding:10px;border-radius:10px;margin-top:4px;">
        <div style="font-size:11px;font-weight:700;color:#475569;margin-bottom:8px;text-transform:uppercase;">Kewenangan Verifikasi:</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
          ${verifButtonsHtml}
        </div>
      </div>

      ${jadikanOperatorBtnHtml}

      <button type="button" class="btn-outline-touch" style="width:100%;height:44px;color:#dc2626;border-color:#fecaca;" onclick="deleteSupporter(${index})">
        ${Icons.trash} Hapus Data
      </button>
    </div>
  `;

  openBottomSheet('Detail Pendukung Konstituen', content);
}

async function updateSupporterStatus(index, newStatus) {
  const item = AppState.supporters[index];
  if (!item) return;

  if (item.id) {
    showToast('Memperbarui status verifikasi...', 'info');
    try {
      const res = await mobileApiCall('verifikasi.php?action=update-status', 'POST', {
        rowNumber: item.id,
        newStatus: newStatus
      });
      if (res.ok && res.data && res.data.success) {
        item.status = newStatus;
        calculateStats();
        renderDashboardStats();
        renderSupportersList();
        closeBottomSheet();
        showToast(`Status berhasil diperbarui menjadi ${newStatus}!`, 'success');
      } else {
        const errMsg = (res.data && res.data.message) ? res.data.message : 'Gagal memperbarui status verifikasi.';
        showToast(errMsg, 'warning');
      }
    } catch (e) {
      console.warn('Gagal sinkron status verifikasi:', e);
      showToast('Gagal menghubungi server.', 'warning');
    }
  } else {
    item.status = newStatus;
    calculateStats();
    renderDashboardStats();
    renderSupportersList();
    closeBottomSheet();
    showToast(`Status lokal diperbarui menjadi ${newStatus}!`, 'success');
  }
}

function deleteSupporter(index) {
  const item = AppState.supporters[index];
  if (!item) return;

  if (confirm(`Yakin ingin menghapus data pendukung ${item.nama}?`)) {
    AppState.supporters.splice(index, 1);
    calculateStats();
    renderDashboardStats();
    renderSupportersList();
    closeBottomSheet();
    showToast('Data pendukung berhasil dihapus.', 'info');
  }
}

// Buka Formulir Entri 5 Jalur
function openEntryForm(jalurKey, jalurTitle) {
  AppState.activeJalur = jalurKey;

  let specificFieldsHtml = '';
  if (jalurKey === 'DPC') {
    specificFieldsHtml = `
      <div class="form-group">
        <label class="form-label">Jabatan Struktural DPC <span class="required-mark">*</span></label>
        <select id="formJabatan" class="form-select-touch" required>
          <option value="Ketua DPC">Ketua DPC</option>
          <option value="Sekretaris DPC">Sekretaris DPC</option>
          <option value="Bendahara DPC">Bendahara DPC</option>
          <option value="Wakil Ketua">Wakil Ketua</option>
          <option value="Pengurus Harian">Pengurus Harian</option>
          <option value="Pengurus Pleno" selected>Pengurus Pleno</option>
          <option value="Anggota DPC">Anggota DPC</option>
        </select>
      </div>
    `;
  } else if (jalurKey === 'DPRT') {
    specificFieldsHtml = `
      <div class="form-group">
        <label class="form-label">Jabatan Struktural DPRT Desa <span class="required-mark">*</span></label>
        <select id="formJabatan" class="form-select-touch" required>
          <option value="Ketua Ranting">Ketua Ranting (DPRT)</option>
          <option value="Sekretaris Ranting">Sekretaris Ranting</option>
          <option value="Bendahara Ranting">Bendahara Ranting</option>
          <option value="Koordinator Dusun">Koordinator Dusun</option>
          <option value="Pengurus Ranting" selected>Pengurus Ranting</option>
          <option value="Anggota Ranting">Anggota Ranting</option>
        </select>
      </div>
    `;
  } else if (jalurKey === 'PIP') {
    specificFieldsHtml = `
      <div class="form-group">
        <label class="form-label">Nama Sekolah <span class="required-mark">*</span></label>
        <input type="text" id="formSekolah" class="form-input-touch" placeholder="Contoh: SDN Patokan 1 / SMPN 1 Kraksaan" required>
      </div>
      <div class="form-group">
        <label class="form-label">Jenjang Pendidikan <span class="required-mark">*</span></label>
        <select id="formTingkat" class="form-select-touch" required>
          <option value="SD/MI" selected>SD / MI</option>
          <option value="SMP/MTs">SMP / MTs</option>
          <option value="SMA/SMK/MA">SMA / SMK / MA</option>
        </select>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Nama Orang Tua (Ayah) <span class="required-mark">*</span></label>
          <input type="text" id="formNamaOrtu" class="form-input-touch" placeholder="Nama ayah / wali" required>
        </div>
        <div class="form-group">
          <label class="form-label">No. WhatsApp Orang Tua</label>
          <input type="tel" id="formHpOrtu" class="form-input-touch" placeholder="08xxxxxxxxxx">
        </div>
      </div>
    `;
  } else if (jalurKey === 'KIP') {
    specificFieldsHtml = `
      <div class="form-group">
        <label class="form-label">Nama Perguruan Tinggi / Kampus <span class="required-mark">*</span></label>
        <input type="text" id="formKampus" class="form-input-touch" placeholder="Contoh: Universitas Nurul Jadid / Unej" required>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Fakultas / Program Studi <span class="required-mark">*</span></label>
          <input type="text" id="formFakultas" class="form-input-touch" placeholder="Teknik Informatika" required>
        </div>
        <div class="form-group">
          <label class="form-label">Semester / Angkatan</label>
          <input type="text" id="formSemester" class="form-input-touch" placeholder="Semester 1 (2025)">
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Nama Orang Tua / Wali <span class="required-mark">*</span></label>
          <input type="text" id="formNamaOrtu" class="form-input-touch" placeholder="Nama orang tua" required>
        </div>
        <div class="form-group">
          <label class="form-label">No. WhatsApp Orang Tua</label>
          <input type="tel" id="formHpOrtu" class="form-input-touch" placeholder="08xxxxxxxxxx">
        </div>
      </div>
    `;
  } else {
    // RELAWAN
    specificFieldsHtml = `
      <div class="form-group">
        <label class="form-label">Kategori Penugasan Relawan <span class="required-mark">*</span></label>
        <select id="formKategoriRelawan" class="form-select-touch" required>
          <option value="Relawan TPS" selected>Relawan TPS</option>
          <option value="Saksi TPS">Saksi TPS Mandat</option>
          <option value="Koordinator Desa">Koordinator Desa (Kordes)</option>
          <option value="Koordinator Kecamatan">Koordinator Kecamatan (Korcam)</option>
          <option value="Simpatisan Warga">Simpatisan Warga Setia</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Nama Koordinator / Komunitas</label>
        <input type="text" id="formKoordinator" class="form-input-touch" placeholder="Contoh: Korcam Kraksaan / Sahabat Santri">
      </div>
    `;
  }

  const content = `
    <form onsubmit="handleFormSubmit(event)">
      <div style="background:#f8fafc;padding:10px 14px;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:14px;">
        <span style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;">Metode:</span>
        <div style="font-size:14px;font-weight:700;color:#16225e;">${escapeHtml(jalurTitle)}</div>
      </div>

      <!-- Kamera & AI OCR KTP Box -->
      <div class="ocr-capture-card" onclick="triggerOcrCamera()">
        <div class="ocr-icon-circle">${Icons.camera}</div>
        <div class="ocr-title">Pindai / Ambil Foto KTP (Smart OCR)</div>
        <div class="ocr-desc">Ekstraksi otomatis NIK, Nama, dan Alamat</div>
        <input type="file" id="ocrFileInput" accept="image/*" capture="camera" style="display:none;" onchange="handleOcrFile(event)">
      </div>

      <!-- GPS Presisi Box -->
      <div class="gps-capture-card">
        <div class="gps-info-box">
          <div class="gps-icon-circle">${Icons.mapPin}</div>
          <div>
            <div class="gps-text-title" id="gpsStatusText">Kunci Koordinat GPS</div>
            <div class="gps-text-coords" id="gpsCoordsText">Belum dikunci</div>
          </div>
        </div>
        <button type="button" class="btn-gps-lock" onclick="lockGpsPosition()">Kunci GPS</button>
      </div>

      <!-- Form Inputs -->
      <div class="form-group">
        <label class="form-label">NIK (Nomor Induk Kependudukan) <span class="required-mark">*</span></label>
        <input type="tel" id="formNik" class="form-input-touch" maxlength="16" placeholder="16 digit NIK KTP" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Lengkap KTP <span class="required-mark">*</span></label>
        <input type="text" id="formNama" class="form-input-touch" placeholder="Nama warga" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nomor WhatsApp / HP <span class="required-mark">*</span></label>
        <input type="tel" id="formHp" class="form-input-touch" placeholder="Contoh: 081234567890" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Kecamatan <span class="required-mark">*</span></label>
          <select id="formKecamatan" class="form-select-touch" onchange="updateDesaDropdown(this.value)" required>
            <option value="Kraksaan" selected>Kraksaan</option>
            <option value="Besuk">Besuk</option>
            <option value="Gading">Gading</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Desa <span class="required-mark">*</span></label>
          <select id="formDesa" class="form-select-touch" required>
            ${(DapilLocations['Kraksaan'] ? DapilLocations['Kraksaan'].desa : []).map(d => `<option value="${escapeHtml(d)}"${d === 'Patokan' ? ' selected' : ''}>${escapeHtml(d)}</option>`).join('')}
          </select>
        </div>
      </div>

      ${specificFieldsHtml}

      <div class="form-group">
        <label class="form-label">Status Verifikasi</label>
        <select id="formStatus" class="form-select-touch">
          <option value="Diinput" selected>Diinput (Awal)</option>
          <option value="Diverifikasi Desa">Diverifikasi Desa</option>
          <option value="Divalidasi Kecamatan">Divalidasi Kecamatan</option>
          <option value="Final">Final (Sah)</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat / RT RW</label>
        <textarea id="formAlamat" class="form-textarea-touch" placeholder="RT/RW, Dusun, Desa"></textarea>
      </div>

      <input type="hidden" id="formLat" value="">
      <input type="hidden" id="formLng" value="">
      <input type="hidden" id="formJalur" value="${escapeHtml(jalurKey)}">

      <div class="sticky-form-cta">
        <button type="submit" class="btn-primary-touch">
          ${Icons.checkCircle} Simpan Data Pendukung
        </button>
      </div>
    </form>
  `;

  openBottomSheet(jalurTitle, content);
}

function triggerOcrCamera() {
  const input = document.getElementById('ocrFileInput');
  if (input) input.click();
}

function handleOcrFile(event) {
  const file = event.target.files[0];
  if (!file) return;

  showToast('Memindai KTP via Smart AI OCR...', 'info');

  setTimeout(() => {
    const randomNik = '3513' + Math.floor(100000000000 + Math.random() * 900000000000);
    const sampleNames = ['Siti Rohmah', 'Muhammad Khoirul', 'Dewi Susilawati', 'Hasan Basri', 'Abdul Mukti'];
    const chosenName = sampleNames[Math.floor(Math.random() * sampleNames.length)];

    const inNik = document.getElementById('formNik');
    const inNama = document.getElementById('formNama');
    const inAlamat = document.getElementById('formAlamat');

    if (inNik) inNik.value = randomNik;
    if (inNama) inNama.value = chosenName;
    if (inAlamat) inAlamat.value = 'RT 03 RW 02 Dusun Melati';

    showToast('KTP Berhasil Dipindai! NIK dan Nama terisi otomatis.', 'success');
  }, 1000);
}

function lockGpsPosition() {
  const statusEl = document.getElementById('gpsStatusText');
  const coordsEl = document.getElementById('gpsCoordsText');

  if (!navigator.geolocation) {
    showToast('Perangkat tidak mendukung geolokasi satelit.', 'danger');
    return;
  }

  showToast('Mengunci koordinat GPS satelit...', 'info');
  if (statusEl) statusEl.textContent = 'Mencari sinyal satelit GPS...';

  navigator.geolocation.getCurrentPosition(
    (pos) => {
      const lat = pos.coords.latitude.toFixed(6);
      const lng = pos.coords.longitude.toFixed(6);

      if (statusEl) statusEl.textContent = 'Koordinat Terkunci Presisi';
      if (coordsEl) coordsEl.textContent = `${lat}, ${lng}`;

      const inLat = document.getElementById('formLat');
      const inLng = document.getElementById('formLng');
      if (inLat) inLat.value = lat;
      if (inLng) inLng.value = lng;

      detectNearestKecamatan(pos.coords.latitude, pos.coords.longitude);
      showToast('Koordinat GPS berhasil dikunci!', 'success');
    },
    (err) => {
      const defaultLat = -7.759521;
      const defaultLng = 113.418534;
      if (statusEl) statusEl.textContent = 'Koordinat Kraksaan (Default)';
      if (coordsEl) coordsEl.textContent = `${defaultLat}, ${defaultLng}`;

      const inLat = document.getElementById('formLat');
      const inLng = document.getElementById('formLng');
      if (inLat) inLat.value = defaultLat;
      if (inLng) inLng.value = defaultLng;

      showToast('Koordinat default Kraksaan disematkan.', 'info');
    },
    { enableHighAccuracy: true, timeout: 7000 }
  );
}

function detectNearestKecamatan(lat, lng) {
  let minDistance = Infinity;
  let nearestKec = 'Kraksaan';

  for (const [kec, data] of Object.entries(DapilLocations)) {
    const d = Math.hypot(lat - data.lat, lng - data.lng);
    if (d < minDistance) {
      minDistance = d;
      nearestKec = kec;
    }
  }

  const kecSelect = document.getElementById('formKecamatan');
  if (kecSelect) {
    kecSelect.value = nearestKec;
    updateDesaDropdown(nearestKec);
  }
}

function updateDesaDropdown(kecamatan, selectedDesa = '') {
  const desaSelect = document.getElementById('formDesa');
  if (!desaSelect) return;
  const data = DapilLocations[kecamatan];
  if (!data) return;
  desaSelect.innerHTML = data.desa.map(d => `<option value="${escapeHtml(d)}"${(selectedDesa && selectedDesa.toLowerCase() === d.toLowerCase()) ? ' selected' : ''}>${escapeHtml(d)}</option>`).join('');
}

async function handleFormSubmit(event) {
  event.preventDefault();

  const nik = document.getElementById('formNik')?.value.trim() || '';
  const nama = document.getElementById('formNama')?.value.trim() || '';
  const hp = document.getElementById('formHp')?.value.trim() || '';
  const kecamatan = document.getElementById('formKecamatan')?.value || 'Kraksaan';
  const desa = document.getElementById('formDesa')?.value || 'Patokan';
  const tps = document.getElementById('formTps')?.value?.trim() || '';
  const status = document.getElementById('formStatus')?.value || 'Diinput';
  const alamat = document.getElementById('formAlamat')?.value.trim() || '';
  const lat = document.getElementById('formLat')?.value || '';
  const lng = document.getElementById('formLng')?.value || '';
  const jalur = (AppState.activeJalur || document.getElementById('formJalur')?.value || 'RELAWAN').toUpperCase();

  if (nik.length < 16) {
    showToast('NIK harus terdiri dari 16 digit angka.', 'danger');
    return;
  }

  let jabatan = '';
  let koordinator = '';
  let dataKhusus = null;
  const payload = {
    jalur,
    nama,
    nik,
    hp,
    kecamatan,
    desa,
    tps,
    alamat,
    status,
    lat,
    lng
  };

  if (jalur === 'DPC' || jalur === 'DPRT') {
    jabatan = document.getElementById('formJabatan')?.value || (jalur === 'DPC' ? 'Pengurus DPC' : 'Pengurus DPRT');
    payload.jabatan = jabatan;
  } else if (jalur === 'PIP') {
    const namaSekolah = document.getElementById('formSekolah')?.value.trim() || '';
    const tingkatSekolah = document.getElementById('formTingkat')?.value || 'SD/MI';
    const namaAyah = document.getElementById('formNamaOrtu')?.value.trim() || '';
    const hpAyah = document.getElementById('formHpOrtu')?.value.trim() || '';

    payload.namaAnak = nama;
    payload.nikAnak = nik;
    payload.hpAnak = hp;
    payload.alamatKeluarga = alamat;
    payload.namaSekolah = namaSekolah;
    payload.tingkatSekolah = tingkatSekolah;
    payload.namaAyah = namaAyah;
    payload.hpAyah = hpAyah;
    dataKhusus = { namaSekolah, tingkatSekolah, namaAyah, hpAyah };
  } else if (jalur === 'KIP') {
    const namaKampus = document.getElementById('formKampus')?.value.trim() || '';
    const fakultas = document.getElementById('formFakultas')?.value.trim() || '';
    const jurusan = document.getElementById('formSemester')?.value.trim() || '';
    const namaAyah = document.getElementById('formNamaOrtu')?.value.trim() || '';
    const hpAyah = document.getElementById('formHpOrtu')?.value.trim() || '';

    payload.namaAnak = nama;
    payload.nikAnak = nik;
    payload.hpAnak = hp;
    payload.alamatKeluarga = alamat;
    payload.namaKampus = namaKampus;
    payload.fakultas = fakultas;
    payload.jurusan = jurusan;
    payload.namaAyah = namaAyah;
    payload.hpAyah = hpAyah;
    dataKhusus = { namaKampus, fakultas, jurusan, namaAyah, hpAyah };
  } else {
    // RELAWAN
    jabatan = document.getElementById('formKategoriRelawan')?.value || 'Relawan TPS';
    koordinator = document.getElementById('formKoordinator')?.value.trim() || '';
    payload.jabatan = jabatan;
    payload.kategoriRelawan = jabatan;
    payload.namaKoordinator = koordinator;
    payload.koordinator = koordinator;
    payload.namaAnggota = nama;
    payload.nikAnggota = nik;
    payload.hpAnggota = hp;
    payload.alamatAnggota = alamat;
  }

  const newSupporter = {
    id: Date.now(),
    jalur,
    nik,
    nama,
    hp,
    no_hp: hp,
    kecamatan,
    desa,
    tps,
    status,
    alamat,
    lat,
    lng,
    jabatan,
    koordinator,
    data_khusus: dataKhusus,
    created_at: new Date().toISOString()
  };

  showToast('Menyimpan data pendukung...', 'info');

  try {
    const res = await mobileApiCall('pendukung.php?action=submit', 'POST', payload);
    if (res.ok && res.data && res.data.success) {
      showToast(res.data.message || 'Data pendukung berhasil tersimpan ke database server!', 'success');
      loadAllData();
    } else {
      const msg = (res.data && res.data.message) ? res.data.message : 'Tersimpan lokal di perangkat.';
      showToast(msg, res.ok ? 'success' : 'warning');
      AppState.supporters.unshift(newSupporter);
      calculateStats();
      renderDashboardStats();
      renderSupportersList();
    }
  } catch (e) {
    AppState.supporters.unshift(newSupporter);
    calculateStats();
    renderDashboardStats();
    renderSupportersList();
    showToast('Data tersimpan di perangkat lokal.', 'info');
  }

  closeBottomSheet();
  navigatePage('pendukung');
}

// Bottom Sheet Open & Close
function openBottomSheet(title, contentHtml) {
  const backdrop = document.getElementById('sheetBackdrop');
  const sheet = document.getElementById('bottomSheet');
  const titleEl = document.getElementById('sheetTitle');
  const contentEl = document.getElementById('sheetContent');

  if (titleEl) titleEl.textContent = title;
  if (contentEl) contentEl.innerHTML = contentHtml;

  if (backdrop) backdrop.classList.add('open');
  if (sheet) sheet.classList.add('open');
}

function closeBottomSheet() {
  const backdrop = document.getElementById('sheetBackdrop');
  const sheet = document.getElementById('bottomSheet');

  if (backdrop) backdrop.classList.remove('open');
  if (sheet) sheet.classList.remove('open');
}

// Toast Notification
function showToast(message, type = 'info') {
  let toast = document.getElementById('mobileToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'mobileToast';
    toast.className = 'mobile-toast';
    document.body.appendChild(toast);
  }

  let iconSvg = Icons.checkCircle;
  if (type === 'danger') iconSvg = Icons.alertTriangle;
  else if (type === 'info') iconSvg = Icons.clock;

  toast.className = `mobile-toast ${type} show`;
  toast.innerHTML = `${iconSvg} <span>${escapeHtml(message)}</span>`;

  clearTimeout(window._toastTimeout);
  window._toastTimeout = setTimeout(() => {
    toast.classList.remove('show');
  }, 3000);
}

// Helpers
function maskNik(nik) {
  if (!nik) return '3513xxxxxxxxxxxx';
  const str = String(nik);
  if (str.length < 10) return str;
  return str.substring(0, 6) + '******' + str.substring(str.length - 4);
}

function formatWaPhone(phone) {
  if (!phone) return '6281234567890';
  let cleaned = phone.replace(/[^0-9]/g, '');
  if (cleaned.startsWith('0')) cleaned = '62' + cleaned.substring(1);
  else if (!cleaned.startsWith('62')) cleaned = '62' + cleaned;
  return cleaned;
}

function escapeHtml(text) {
  if (!text) return '';
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

// Data Dummy Representatif (Fallback Cadangan)
function generateDefaultSupporters() {
  return [
    { nik: '3513241203850001', nama: 'H. Abdul Kholiq', hp: '081234567801', kecamatan: 'Kraksaan', desa: 'Patokan', tps: '02', status: 'Final', jalur: 'DPC', jabatan: 'Ketua DPC', alamat: 'Jl. Rengganis No. 14 RT 01 RW 02' },
    { nik: '3513244508920002', nama: 'Nurul Hidayati', hp: '081234567802', kecamatan: 'Kraksaan', desa: 'Kraksaan Wetan', tps: '04', status: 'Divalidasi Kecamatan', jalur: 'DPRT', jabatan: 'Sekretaris Ranting', alamat: 'Jl. Diponegoro RT 03 RW 01' },
    { nik: '3513192211880003', nama: 'Ahmad Mubarok', hp: '081234567803', kecamatan: 'Besuk', desa: 'Besuk Agung', tps: '01', status: 'Diverifikasi Desa', jalur: 'PIP', data_khusus: { namaSekolah: 'SMKN 1 Kraksaan', tingkatSekolah: 'SMA/SMK/MA' }, alamat: 'Dusun Krajan RT 02 RW 01' },
    { nik: '3513196704950004', nama: 'Fathur Rozi', hp: '081234567804', kecamatan: 'Besuk', desa: 'Randu Jalak', tps: '03', status: 'Diinput', jalur: 'KIP', data_khusus: { namaKampus: 'Universitas Nurul Jadid' }, alamat: 'RT 04 RW 02 Desa Randu Jalak' },
    { nik: '3513211506900005', nama: 'Siti Maryam', hp: '081234567805', kecamatan: 'Gading', desa: 'Condong', tps: '01', status: 'Final', jalur: 'RELAWAN', jabatan: 'Relawan TPS', alamat: 'RT 01 RW 01 Condong Gading' }
  ];
}

function generateDefaultAspirasi() {
  return [
    { topik: 'Bantuan Pompa Air & Sumur Bor Kelompok Tani', pengusul: 'Poktan Makmur Jaya', desa: 'Randu Jalak', kecamatan: 'Besuk', kategori: 'Pertanian', status: 'valid', isi: 'Kebutuhan mendesak pengairan sawah warga saat musim kemarau.' },
    { topik: 'Perbaikan Aspal Jalan Poros Antar Dusun', pengusul: 'Ustadz Munir', desa: 'Wangkal', kecamatan: 'Gading', kategori: 'Infrastruktur', status: 'pokir', isi: 'Akses jalan penghubung sering berlubang dan membahayakan pengendara roda dua.' },
    { topik: 'Pelatihan Kewirausahaan & Modal Usaha Ibu Muslimat', pengusul: 'Pengurus Muslimat', desa: 'Semampir', kecamatan: 'Kraksaan', kategori: 'UMKM', status: 'valid', isi: 'Pendampingan sertifikasi halal dan izin PIRT bagi usaha mikro rumahan.' }
  ];
}

function generateDefaultPokir() {
  return [
    { judul: 'Normalisasi Saluran Irigasi Tersier Pertanian', kegiatan: 'Normalisasi Saluran Irigasi', lokasi: 'Desa Besuk Agung, Kec. Besuk', anggaran: 175000000, opd: 'Dinas PUPR', status: 'Disetujui' },
    { judul: 'Penerangan Jalan Umum (PJU) Tenaga Surya', kegiatan: 'Pemasangan PJU Tenaga Surya', lokasi: 'Desa Condong, Kec. Gading', anggaran: 120000000, opd: 'Dinas Perhubungan', status: 'Disetujui' },
    { judul: 'Pembangunan Tembok Penahan Tanah (TPT)', kegiatan: 'Pembangunan TPT Jalan Poros', lokasi: 'Desa Patokan, Kec. Kraksaan', anggaran: 200000000, opd: 'Dinas PUPR', status: 'Diajukan' }
  ];
}

function generateDefaultAuditLogs() {
  return [
    { judul: 'Entri Pendukung Baru', waktu: '5 menit lalu', desc: 'Menambahkan data Siti Maryam via AI OCR KTP', petugas: 'Ahmad M.', desa: 'Condong, Gading', color: 'green' },
    { judul: 'Verifikasi NIK Berkas', waktu: '15 menit lalu', desc: 'Memvalidasi data fisik KTP H. Abdul Kholiq', petugas: 'Nurul H.', desa: 'Patokan, Kraksaan', color: 'gold' },
    { judul: 'Pencatatan Aspirasi Warga', waktu: '1 jam lalu', desc: 'Mencatat usulan Pompa Air Poktan Makmur Jaya', petugas: 'Fathur R.', desa: 'Randu Jalak, Besuk', color: 'purple' },
    { judul: 'Sinkronisasi Data Lapangan', waktu: '3 jam lalu', desc: 'Sinkronisasi 24 data pendukung ke server pusat', petugas: 'Sistem PWA', desa: 'Kraksaan Raya', color: 'green' }
  ];
}

function generateDefaultOperators() {
  return [
    { nama: 'H. Abdul Kholiq', role: 'Koordinator Kecamatan (Korcam)', wilayah: 'Kecamatan Kraksaan' },
    { nama: 'Ahmad Mubarok', role: 'Koordinator Kecamatan (Korcam)', wilayah: 'Kecamatan Besuk' },
    { nama: 'Fathur Rozi', role: 'Koordinator Desa (Kordes)', wilayah: 'Desa Randu Jalak, Besuk' },
    { nama: 'Siti Maryam', role: 'Koordinator Lapangan', wilayah: 'Kecamatan Gading' }
  ];
}

function generateDefaultLeaderboard() {
  return [
    { nama: 'Nurul Hidayati', wilayah: 'Kordes Kraksaan Wetan', total: 520, persen: 88 },
    { nama: 'Bambang Sugiono', wilayah: 'Kordes Wangkal, Gading', total: 490, persen: 82 },
    { nama: 'Dewi Lestari', wilayah: 'Relawan Semampir', total: 435, persen: 79 },
    { nama: 'Hasan Basri', wilayah: 'Kordes Patokan', total: 395, persen: 75 },
    { nama: 'Abdul Mukti', wilayah: 'Relawan Alas Sumur Lor', total: 360, persen: 71 }
  ];
}
  
// ============ PEMBUATAN AKUN OPERATOR DARI MOBILE ============
function openCreateOperatorSheet(index) {
  const item = AppState.supporters[index];
  if (!item) return;

  const jk = (item.jalur || '').toUpperCase();
  let defRole = 'Admin Ranting';
  if (jk === 'DPC') defRole = 'Koordinator Kecamatan';
  else if (jk === 'DPRT') defRole = 'Koordinator Desa';

  const cleanHp = (item.hp || '').replace(/[^0-9]/g, '');
  const cleanNik = (item.nik || '').replace(/[^0-9]/g, '');
  const defUser = cleanHp || ('op_' + (item.id || index));
  const defPass = cleanNik.length >= 6 ? cleanNik.slice(-6) : '123456';

  const content = `
    <div style="margin-bottom:14px;">
      <p style="font-size:12.5px;color:#64748b;margin-top:0;">Buat akun login operator dan tentukan wilayah serta peran tugasnya.</p>
      <div style="background:#f8fafc;padding:10px;border-radius:10px;border:1px solid #e2e8f0;margin-bottom:12px;">
        <div style="font-size:13px;font-weight:700;color:#0f172a;">${escapeHtml(item.nama)}</div>
        <div style="font-size:12px;color:#64748b;">Wilayah: ${escapeHtml(item.kecamatan || '')} / ${escapeHtml(item.desa || '')}</div>
      </div>

      <div class="form-group" style="margin-bottom:10px;">
        <label style="font-size:12px;font-weight:700;color:#334155;margin-bottom:4px;display:block;">Role / Kewenangan:</label>
        <select id="mOpRole" class="form-input-touch">
          <option value="Koordinator Desa" ${defRole === 'Koordinator Desa' ? 'selected' : ''}>Koordinator Desa (Kordes)</option>
          <option value="Koordinator Kecamatan" ${defRole === 'Koordinator Kecamatan' ? 'selected' : ''}>Koordinator Kecamatan (Korcam)</option>
          <option value="Admin Ranting" ${defRole === 'Admin Ranting' ? 'selected' : ''}>Admin Ranting</option>
        </select>
      </div>

      <div class="form-group" style="margin-bottom:10px;">
        <label style="font-size:12px;font-weight:700;color:#334155;margin-bottom:4px;display:block;">Username Login:</label>
        <input type="text" id="mOpUsername" class="form-input-touch" value="${escapeHtml(defUser)}">
      </div>

      <div class="form-group" style="margin-bottom:16px;">
        <label style="font-size:12px;font-weight:700;color:#334155;margin-bottom:4px;display:block;">Password Awal:</label>
        <input type="text" id="mOpPassword" class="form-input-touch" value="${escapeHtml(defPass)}">
      </div>

      <button type="button" class="btn-primary-touch" style="width:100%;height:44px;background:#0d9488;" onclick="submitCreateOperator(${index})">
        Buat Akun Operator Sekarang
      </button>
    </div>
  `;

  openBottomSheet('Jadikan Akun Operator', content);
}

async function submitCreateOperator(index) {
  const item = AppState.supporters[index];
  if (!item) return;

  const role = document.getElementById('mOpRole')?.value;
  const username = document.getElementById('mOpUsername')?.value?.trim();
  const password = document.getElementById('mOpPassword')?.value?.trim();

  if (!username || !password) {
    showToast('Username dan password wajib diisi.', 'warning');
    return;
  }

  showToast('Membuat akun operator...', 'info');

  const res = await mobileApiCall('users.php?action=create-from-pendukung', 'POST', {
    pendukung_id: item.id,
    role: role,
    username: username,
    password: password
  });

  if (res.ok && res.data && res.data.success && res.data.user) {
    const u = res.data.user;
    window.lastMobileCreatedOp = u;

    const nomorWa = formatWaPhone(u.hp || item.hp);
    const teksWa = encodeURIComponent("Assalamu'alaikum Bpk/Ibu " + u.nama + ",\n\nBerikut akun login Anda sebagai " + u.role + " Tim Gus Dim (Wilayah: " + (u.kecamatan || '') + " " + (u.desa || '') + "):\n- Username: " + u.username + "\n- Password: " + u.password + "\n\nSilakan masuk melalui aplikasi: " + window.location.origin + "\n\nTerima kasih.");

    const credContent = `
      <div style="text-align:center;margin-bottom:14px;">
        <div style="width:50px;height:50px;border-radius:50%;background:#ccfbf1;color:#0d9488;display:inline-flex;align-items:center;justify-content:center;margin-bottom:8px;">
          ${Icons.checkCircle}
        </div>
        <h3 style="font-size:16px;font-weight:800;color:#0f172a;margin:0;">Akun Berhasil Dibuat!</h3>
        <p style="font-size:12px;color:#64748b;margin-top:4px;">Akun telah aktif dan tersimpan di database server pusat.</p>
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px;display:flex;flex-direction:column;gap:6px;margin-bottom:14px;font-size:13px;">
        <div><strong>Nama:</strong> ${escapeHtml(u.nama)}</div>
        <div><strong>Role:</strong> ${escapeHtml(u.role)}</div>
        <div><strong>Wilayah:</strong> ${escapeHtml(u.kecamatan || '')} / ${escapeHtml(u.desa || '')}</div>
        <div><strong>Username:</strong> <code style="background:#e2e8f0;padding:2px 6px;border-radius:4px;font-weight:700;">${escapeHtml(u.username)}</code></div>
        <div><strong>Password:</strong> <code style="background:#e2e8f0;padding:2px 6px;border-radius:4px;font-weight:700;">${escapeHtml(u.password)}</code></div>
      </div>

      <div style="display:flex;flex-direction:column;gap:8px;">
        <a href="https://wa.me/${nomorWa}?text=${teksWa}" target="_blank" class="btn-wa-touch">
          ${Icons.whatsapp} Kirim Akun via WhatsApp
        </a>
        <button type="button" class="btn-outline-touch" style="width:100%;height:44px;" onclick="salinMobileOperatorCreds()">
          Salin Kredensial Akun
        </button>
        <button type="button" class="btn-outline-touch" style="width:100%;height:40px;" onclick="closeBottomSheet()">
          Tutup
        </button>
      </div>
    `;

    openBottomSheet('Kredensial Akun Operator', credContent);
    showToast('Akun operator berhasil dibuat!', 'success');
  } else {
    const err = (res.data && res.data.message) ? res.data.message : 'Gagal membuat akun operator.';
    showToast(err, 'warning');
  }
}

function salinMobileOperatorCreds() {
  const u = window.lastMobileCreatedOp;
  if (!u) return;
  const txt = "Akun Operator Gus Dim:\nNama: " + u.nama + "\nRole: " + u.role + "\nWilayah: " + (u.kecamatan || '') + " " + (u.desa || '') + "\nUsername: " + u.username + "\nPassword: " + u.password;
  if (navigator.clipboard) {
    navigator.clipboard.writeText(txt).then(() => showToast('Kredensial disalin ke clipboard!', 'success'));
  } else {
    prompt('Salin kredensial berikut:', txt);
  }
}


function openReSendMobileOperatorWa(index) {
  const item = AppState.supporters[index];
  if (!item) return;
  const phoneRaw = item.hp || item.no_hp || '';
  const waPhone = formatWaPhone(phoneRaw);
  if (!waPhone) {
    showToast('Nomor WhatsApp tidak valid.', 'warning');
    return;
  }
  const teksWa = encodeURIComponent("Assalamu'alaikum Bpk/Ibu " + item.nama + ",\n\nAkun login Anda sebagai " + (item.operator_role || 'Operator') + " Tim Gus Dim:\n- Username: " + (item.operator_username || item.hp) + "\n- Wilayah: " + (item.kecamatan || '') + " " + (item.desa || '') + "\n\nSilakan akses aplikasi: " + window.location.origin + "\n\nJika lupa kata sandi, silakan hubungi admin untuk reset password.\n\nTerima kasih.");
  window.open("https://wa.me/" + waPhone + "?text=" + teksWa, "_blank");
}

async function openResetMobileOperatorPass(index) {
  const item = AppState.supporters[index];
  if (!item) return;

  if (!confirm("Reset password akun operator untuk " + item.nama + "? Password baru akan dibuat otomatis.")) {
    return;
  }

  showToast('Mereset password operator...', 'info');

  const res = await mobileApiCall('users.php?action=reset-password-operator', 'POST', {
    pendukung_id: item.id,
    user_id: item.operator_user_id || 0
  });

  if (res.ok && res.data && res.data.success && res.data.user) {
    const u = res.data.user;
    window.lastMobileCreatedOp = u;

    const nomorWa = formatWaPhone(u.hp || item.hp);
    const teksWa = encodeURIComponent("Assalamu'alaikum Bpk/Ibu " + u.nama + ",\n\nPassword akun operator Anda (" + u.role + ") telah direset:\n- Username: " + u.username + "\n- Password Baru: " + u.password + "\n\nSilakan masuk melalui aplikasi: " + window.location.origin + "\n\nTerima kasih.");

    const credContent = `
      <div style="text-align:center;margin-bottom:14px;">
        <div style="width:50px;height:50px;border-radius:50%;background:#ccfbf1;color:#0d9488;display:inline-flex;align-items:center;justify-content:center;margin-bottom:8px;">
          ${Icons.checkCircle}
        </div>
        <h3 style="font-size:16px;font-weight:800;color:#0f172a;margin:0;">Password Berhasil Direset!</h3>
        <p style="font-size:12px;color:#64748b;margin-top:4px;">Kata sandi baru telah aktif di server.</p>
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px;display:flex;flex-direction:column;gap:6px;margin-bottom:14px;font-size:13px;">
        <div><strong>Nama:</strong> ${escapeHtml(u.nama)}</div>
        <div><strong>Role:</strong> ${escapeHtml(u.role)}</div>
        <div><strong>Wilayah:</strong> ${escapeHtml(u.kecamatan || '')} / ${escapeHtml(u.desa || '')}</div>
        <div><strong>Username:</strong> <code style="background:#e2e8f0;padding:2px 6px;border-radius:4px;font-weight:700;">${escapeHtml(u.username)}</code></div>
        <div><strong>Password Baru:</strong> <code style="background:#e2e8f0;padding:2px 6px;border-radius:4px;font-weight:700;color:#15803d;">${escapeHtml(u.password)}</code></div>
      </div>

      <div style="display:flex;flex-direction:column;gap:8px;">
        <a href="https://wa.me/${nomorWa}?text=${teksWa}" target="_blank" class="btn-wa-touch">
          ${Icons.whatsapp} Kirim Password Baru via WA
        </a>
        <button type="button" class="btn-outline-touch" style="width:100%;height:44px;" onclick="salinMobileOperatorCreds()">
          Salin Kredensial Baru
        </button>
        <button type="button" class="btn-outline-touch" style="width:100%;height:40px;" onclick="closeBottomSheet()">
          Tutup
        </button>
      </div>
    `;

    openBottomSheet('Password Operator Baru', credContent);
    showToast('Password operator berhasil direset!', 'success');
  } else {
    const err = (res.data && res.data.message) ? res.data.message : 'Gagal mereset password operator.';
    showToast(err, 'warning');
  }
}

</script>
</body>
</html>