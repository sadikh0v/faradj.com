<!DOCTYPE html>
<html lang="az">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin — Faradj MMC</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <?php if (!empty($includeQuill)): ?>
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <?php endif; ?>
</head>
<body class="admin-body">

  <aside class="admin-sidebar">
    <div class="sidebar-logo">
      <img src="/assets/img/logo/faradj_logo.png" alt="Faradj MMC" style="height:36px; width:auto;">
      <strong>Admin Panel</strong>
    </div>
    <nav class="sidebar-nav">
      <a href="/admin" class="sidebar-link <?= (rtrim($_SERVER['REQUEST_URI'] ?? '', '/') === '/admin') ? 'active' : '' ?>">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
      <a href="/admin/events" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/events') ? 'active' : '' ?>">
        <i class="fas fa-newspaper"></i> Xəbərlər
      </a>
      <a href="/admin/contacts" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/contacts') ? 'active' : '' ?>">
        <i class="fas fa-envelope"></i> Müraciətlər
      </a>
      <a href="/admin/faqs" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/faqs') ? 'active' : '' ?>">
        <i class="fas fa-question-circle"></i> FAQ
      </a>
      <a href="/admin/testimonials" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/testimonials') ? 'active' : '' ?>">
        <i class="fas fa-quote-right"></i> Rəylər
      </a>
      <a href="/admin/b2b" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/b2b') ? 'active' : '' ?>">
        <i class="fas fa-briefcase"></i> B2B Sorğular
      </a>
      <a href="/admin/callbacks" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/callbacks') ? 'active' : '' ?>">
        <i class="fas fa-phone"></i> Zəng sorğuları
      </a>
      <a href="/admin/users" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/users') ? 'active' : '' ?>">
        <i class="fas fa-user-shield"></i> İstifadəçilər
      </a>
      <a href="/admin/brands" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/brands') ? 'active' : '' ?>">
        <i class="fas fa-tags"></i> Brendlər
      </a>
      <a href="/admin/suppliers" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/suppliers') ? 'active' : '' ?>">
        <i class="fas fa-globe"></i> Xəritə
      </a>
      <a href="/admin/clients" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/clients') ? 'active' : '' ?>">
        <i class="fas fa-handshake"></i> Müştərilər
      </a>
      <a href="/admin/settings" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/settings') ? 'active' : '' ?>">
        <i class="fas fa-sliders-h"></i> Parametrlər
      </a>
      <a href="/admin/stats" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/stats') ? 'active' : '' ?>">
        <i class="fas fa-chart-bar"></i> Statistika
      </a>
      <a href="/admin/gallery" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/gallery') ? 'active' : '' ?>">
        <i class="fas fa-images"></i> Qalereya
      </a>
      <div class="sidebar-divider"></div>
      <a href="/" target="_blank" class="sidebar-link">
        <i class="fas fa-external-link-alt"></i> Sayta bax
      </a>
      <a href="/admin/logout" class="sidebar-link logout">
        <i class="fas fa-sign-out-alt"></i> Çıxış
      </a>
    </nav>
  </aside>

  <div class="admin-mobile-header" style="display:none;">
    <span class="logo-text">
      <img src="/assets/img/logo/faradj_logo.png" style="height:24px;filter:brightness(10);" alt="Faradj">
    </span>
    <button class="burger-admin" id="adminBurger" type="button">
      <i class="fas fa-bars"></i>
    </button>
  </div>
  <div class="sidebar-overlay" id="sidebarOverlay"></div>
  <main class="admin-main">
    <header class="admin-topbar">
      <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
      </button>
      <div class="topbar-right">
        <span class="admin-user">
          <i class="fas fa-user-circle"></i> Admin
        </span>
      </div>
    </header>

    <div class="admin-content">

