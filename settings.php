<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengaturan User - Tel-U Parking</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Material Icons - Link yang benar -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
  </head>
  <body>
    <style>
      body {
        height: 100vh;
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: black;
        color: white;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        display: flex;
      }

      .background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
          135deg,
          #8b1538 0%,
          #5c0e24 30%,
          #3d0b18 70%,
          #2d0710 100%
        );
        z-index: -2;
      }

      .sidebar-container {
        display: flex;
        height: 100vh;
        width: 100px;
        background: linear-gradient(
          135deg,
          #8b1538 0%,
          #5c0e24 30%,
          #3d0b18 70%,
          #2d0710 100%
        );
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
      }

      .sidebar {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 60px;
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
        gap: 10px;
        width: fit-content;
        height: fit-content;
        justify-content: center;
      }

      @media (min-width: 768px) {
        .sidebar {
          width: fit-content;
        }
      }

      .sidebar-logo {
        margin-top: 0px;
        padding: 20px;
      }

      .sidebar-item {
        width: 66px;
        height: 66px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
      }

      @media (min-width: 768px) {
        .sidebar-item {
          width: 66px;
          height: 66px;
        }
      }

      .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.1);
      }

      .sidebar-item.active {
        background: rgba(255, 255, 255, 0.8);
      }

      .material-symbols-outlined.inactive {
        font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
        color: rgba(255, 255, 255, 0.75);
        font-size: 32px;
      }

      .material-symbols-outlined.active {
        font-variation-settings: "FILL" 1, "wght" 400, "GRAD" 0, "opsz" 24;
        color: rgba(128, 0, 0);
        font-size: 32px;
      }

      .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
        -webkit-backdrop-filter: blur(12px);
        overflow: hidden;
      }

      .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 32px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.25);
        border-radius: 0 0 10px 10px;
      }

      @media (min-width: 768px) {
        .header {
          padding: 24px 32px;
        }
      }

      @media (min-width: 768px) {
        .mobile-logo {
          display: none;
        }
      }

      .greeting {
        font-size: 24px;
        font-weight: 400;
        color: #ffeeee;
        text-shadow: 0 6px 4px rgba(0, 0, 0, 0.25);
      }

      @media (min-width: 768px) {
        .greeting {
          font-size: 24px;
        }
      }

      .username {
        font-weight: 700;
        color: #ffeeee;
      }

      .header-right {
        display: flex;
        align-items: center;
        gap: 16px;
      }

      @media (min-width: 768px) {
        .header-right {
          gap: 16px;
        }
      }

      .search-bar {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 30px;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.75);
        width: 300px;
        height: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
      }

      .search-bar .material-symbols-outlined {
        font-size: 30px;
      }

      @media (min-width: 768px) {
        .search-bar {
          width: 300px;
          padding: 12px 18px;
        }
      }

      .search-bar:focus-within {
        background: rgba(255, 255, 255, 0.2);
      }

      .search-bar input {
        background: transparent;
        border: none;
        flex-grow: 1;
        color: white;
        font-size: 18px;
        outline: none;
        font-family: inherit;
      }

      .search-bar input::placeholder {
        color: rgba(255, 255, 255, 0.6);
        font-style: italic;
      }

      input[type="search"]::-webkit-search-cancel-button {
        -webkit-appearance: none;
        appearance: none;
        height: 18px;
        width: 18px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.75);
        cursor: pointer;

        mask: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e")
          center/contain no-repeat;
        -webkit-mask: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e")
          center/contain no-repeat;
      }

      #notifications-icon {
        font-variation-settings: "FILL" 1, "wght" 400, "GRAD" 0, "opsz" 24;
        color: white;
        font-size: 36px;
        text-shadow: 0 6px 4px rgba(0, 0, 0, 0.25);
        padding: 10px;
      }

      .icon-button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
      }

      @media (min-width: 768px) {
        .icon-button {
          width: 50px;
          height: 50px;
        }
      }

      .icon-button:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: scale(1.05);
      }

      .avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      @media (min-width: 768px) {
        .avatar {
          width: 60px;
          height: 60px;
        }
      }

      .avatar:hover {
        transform: scale(1.05);
      }

      .content-area {
        flex: 1;
        padding: 20px 16px;
        min-height: 0;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
      }

      @media (min-width: 768px) {
        .content-area {
          padding: 20px;
          max-width: 100%;
        }
      }

      .page-title {
        font-size: 30px;
        font-weight: 700;
        color: #ffeeee;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 32px;
        flex-shrink: 0;
      }

      .page-title .material-symbols-outlined {
        font-size: 30px;
      }

      @media (min-width: 768px) {
        .page-title {
          font-size: 30px;
          margin-bottom: 32px;
        }
      }

      /* Settings groups */
      .settings-group {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 18px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        overflow: hidden;
        transition: all 0.3s ease;
        width: 100%;
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
      }

      @media (min-width: 768px) {
        .settings-group {
          margin-bottom: 24px;
          border-radius: 18px;
        }
      }

      .settings-group:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
      }

      .settings-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px;
        color: #ffeeee;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        text-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
      }

      @media (min-width: 768px) {
        .settings-item {
          padding: 20px 24px;
        }
      }

      .settings-item:last-child {
        border-bottom: none;
      }

      .settings-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
      }

      .settings-item:active {
        transform: scale(0.98);
      }

      .item-left {
        display: flex;
        align-items: center;
        gap: 16px;
      }

      @media (min-width: 768px) {
        .item-left {
          gap: 18px;
        }
      }

      .item-icon {
        font-size: 24px;
        flex-shrink: 0;
        color: inherit;
      }

      @media (min-width: 768px) {
        .item-icon {
          font-size: 26px;
        }
      }

      .item-label {
        font-size: 16px;
        font-weight: 500;
      }

      @media (min-width: 768px) {
        .item-label {
          font-size: 18px;
        }
      }

      .arrow-icon {
        font-size: 22px;
        color: rgba(255, 255, 255, 0.6);
        flex-shrink: 0;
        transition: all 0.2s ease;
      }

      @media (min-width: 768px) {
        .arrow-icon {
          font-size: 24px;
        }
      }

      .settings-item:hover .arrow-icon {
        color: #fff;
        transform: translateX(2px);
      }

      /* Mobile Responsive */
      @media (max-width: 767px) {
        body {
          flex-direction: column;
          padding-bottom: 90px;
        }

        .sidebar-container {
          width: 100%;
          height: 90px;
          flex-direction: row;
          order: 2;
          bottom: 0px;
          z-index: 100;
          position: fixed;
          justify-content: center;
          border-radius: 10px 10px 0 0;
        }

        .sidebar {
          flex-direction: row;
          margin-top: 0;
        }

        .sidebar-item {
          margin-right: 16px;
        }

        .sidebar-logo {
          display: none;
        }

        .main-content {
          order: 1;
          height: calc(100vh - 70px);
        }

        .header {
          flex-wrap: wrap;
          justify-content: center;
          text-align: center;
          padding: 14px 0;
        }

        .mobile-logo {
          position: absolute;
          left: 16px;
          top: 40px;
          transform: translateY(-50%);
          height: 40px;
          z-index: 10;
        }

        .mobile-logo-img {
          height: 100%;
          width: auto;
          object-fit: contain;
        }

        .header-right {
          margin-top: 12px;
          justify-content: center;
          text-align: center;
          width: 100%;
          padding: 0px 12px;
        }

        .search-bar {
          min-width: 150px;
          width: 100%;
        }
      }
    </style>

    <div class="background"></div>
    <div class="sidebar-container">
      <div class="sidebar-logo" title="Logo TelU">
        <img
          src="./assets/logo-telu-parking.png"
          alt="tel-u-parking-logo"
          onclick="goToHome()"
        />
      </div>
      <nav class="sidebar">
        <div class="sidebar-item" title="Home Page" onclick="goToHome()">
          <span class="material-symbols-outlined inactive">cottage</span>
        </div>
        <div class="sidebar-item" title="Statistics" onclick="goToStatistics()">
          <span class="material-symbols-outlined inactive"
            >bar_chart_4_bars</span
          >
        </div>
        <div class="sidebar-item active" title="Settings">
          <span class="material-symbols-outlined active">settings</span>
        </div>
      </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content" role="main" aria-label="Halaman Pengaturan User">
      <!-- Header -->
      <header class="header">
        <div class="mobile-logo">
          <img
            src="./assets/logo-telu-parking.png"
            alt="Tel-U Parking Logo"
            class="mobile-logo-img"
          />
        </div>
        <div class="greeting">
          Halo, <span class="username">
            <?php echo isset($_SESSION['nama_lengkap']) ? htmlspecialchars($_SESSION['nama_lengkap']) : 'User'; ?>
          </span>
        </div>
        <div class="header-right" aria-label="Notifikasi dan Profil">
          <form
            class="search-bar"
            role="search"
            aria-label="Pencarian Pengaturan"
            onsubmit="handleSearch(event)"
          >
            <span class="material-symbols-outlined">search</span>
            <input
              type="search"
              placeholder="Mau cari apa nih?"
              aria-label="Kotak pencarian"
              id="searchInput"
            />
          </form>
          <button
            class="icon-button"
            aria-label="Notifikasi"
            onclick="showNotifications()"
          >
            <span class="material-symbols-outlined" id="notifications-icon">
              notifications
            </span>
          </button>
          <img
            class="avatar"
            src="assets/<?php echo isset($_SESSION['foto']) ? htmlspecialchars($_SESSION['foto']) : 'default.png'; ?>"
            alt="Avatar user"
            onclick="showProfile()"
            onerror="this.onerror=null;this.src='https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/0c0cb72b-b8c3-4a55-9108-bea1f9c2fbf0.png';"
          />
        </div>
      </header>

      <!-- Content Area -->
      <div class="content-area">
        <h1 class="page-title" aria-label="Judul Pengaturan">
          <span class="material-symbols-outlined">settings</span>
          Pengaturan
        </h1>

        <!-- Bookmark Section -->
        <section class="settings-group" aria-label="Bookmark">
          <div
            class="settings-item"
            tabindex="0"
            role="button"
            aria-pressed="false"
            aria-label="Bookmark Pengaturan"
            onclick="handleSettingClick('bookmark')"
          >
            <div class="item-left">
              <span class="material-symbols-outlined item-icon">bookmark</span>
              <span class="item-label">Bookmark</span>
            </div>
            <span class="material-symbols-outlined arrow-icon"
              >chevron_right</span
            >
          </div>
        </section>

        <!-- Notification & Language Section -->
        <section
          class="settings-group"
          aria-label="Notifikasi dan Bahasa"
          role="region"
        >
          <div
            class="settings-item"
            tabindex="0"
            role="button"
            aria-pressed="false"
            aria-label="Notification Pengaturan"
            onclick="handleSettingClick('notification')"
          >
            <div class="item-left">
              <span class="material-symbols-outlined item-icon"
                >notifications</span
              >
              <span class="item-label">Notification</span>
            </div>
            <span class="material-symbols-outlined arrow-icon"
              >chevron_right</span
            >
          </div>
          <div
            class="settings-item"
            tabindex="0"
            role="button"
            aria-pressed="false"
            aria-label="Language Pengaturan"
            onclick="handleSettingClick('language')"
          >
            <div class="item-left">
              <span class="material-symbols-outlined item-icon">language</span>
              <span class="item-label">Language</span>
            </div>
            <span class="material-symbols-outlined arrow-icon"
              >chevron_right</span
            >
          </div>
        </section>

        <!-- Cache & Cookies Section -->
        <section
          class="settings-group"
          aria-label="Clear Cache dan Cookies"
          role="region"
        >
          <div
            class="settings-item"
            tabindex="0"
            role="button"
            aria-pressed="false"
            aria-label="Clear Cache Pengaturan"
            onclick="handleSettingClick('clear-cache')"
          >
            <div class="item-left">
              <span class="material-symbols-outlined item-icon">delete</span>
              <span class="item-label">Clear Cache</span>
            </div>
            <span class="material-symbols-outlined arrow-icon"
              >chevron_right</span
            >
          </div>
          <div
            class="settings-item"
            tabindex="0"
            role="button"
            aria-pressed="false"
            aria-label="Clear Cookies Pengaturan"
            onclick="handleSettingClick('clear-cookies')"
          >
            <div class="item-left">
              <span class="material-symbols-outlined item-icon"
                >delete_sweep</span
              >
              <span class="item-label">Clear Cookies</span>
            </div>
            <span class="material-symbols-outlined arrow-icon"
              >chevron_right</span
            >
          </div>
        </section>

        <!-- Information Section -->
        <section
          class="settings-group"
          aria-label="Copyright and Privacy Information"
          role="region"
        >
          <div
            class="settings-item"
            tabindex="0"
            role="button"
            aria-pressed="false"
            aria-label="Copyright Information"
            onclick="handleSettingClick('copyright')"
          >
            <div class="item-left">
              <span class="material-symbols-outlined item-icon">info</span>
              <span class="item-label">Copyright Information</span>
            </div>
            <span class="material-symbols-outlined arrow-icon"
              >chevron_right</span
            >
          </div>
          <div
            class="settings-item"
            tabindex="0"
            role="button"
            aria-pressed="false"
            aria-label="Privacy Policy"
            onclick="handleSettingClick('privacy')"
          >
            <div class="item-left">
              <span class="material-symbols-outlined item-icon">shield</span>
              <span class="item-label">Privacy Policy</span>
            </div>
            <span class="material-symbols-outlined arrow-icon"
              >chevron_right</span
            >
          </div>
          <div
            class="settings-item"
            tabindex="0"
            role="button"
            aria-pressed="false"
            aria-label="Version Information"
            onclick="handleSettingClick('version')"
          >
            <div class="item-left">
              <span class="material-symbols-outlined item-icon"
                >description</span
              >
              <span class="item-label">Version Information</span>
            </div>
            <span class="material-symbols-outlined arrow-icon"
              >chevron_right</span
            >
          </div>
        </section>
      </div>
    </main>

    <script>
      // Navigation functions
      function goToHome() {
        window.location.href = "home-page.php";
      }

      function goToStatistics() {
        window.location.href = "statistics.php";
      }

      // Search function
      function handleSearch(event) {
        event.preventDefault();
        const searchTerm = document.getElementById("searchInput").value;
        console.log("Searching for:", searchTerm);
        if (searchTerm.trim()) {
          window.location.href = `search.html?q=${encodeURIComponent(
            searchTerm
          )}`;
        }
      }

      // Notification function
      function showNotifications() {
        alert("Menampilkan notifikasi");
      }

      // Profile function
      function showProfile() {
        alert("Menampilkan profil user");
      }

      // Settings item handler
      function handleSettingClick(settingType) {
        console.log("Settings item clicked:", settingType);

        switch (settingType) {
          case "bookmark":
            alert("Navigasi ke halaman Bookmark");
            break;
          case "notification":
            alert("Navigasi ke pengaturan Notifikasi");
            break;
          case "language":
            alert("Navigasi ke pengaturan Bahasa");
            break;
          case "clear-cache":
            if (confirm("Apakah Anda yakin ingin membersihkan cache?")) {
              alert("Cache berhasil dibersihkan");
            }
            break;
          case "clear-cookies":
            if (confirm("Apakah Anda yakin ingin membersihkan cookies?")) {
              alert("Cookies berhasil dibersihkan");
            }
            break;
          case "copyright":
            alert("Menampilkan informasi Copyright");
            break;
          case "privacy":
            alert("Menampilkan Privacy Policy");
            break;
          case "version":
            alert("Menampilkan informasi Versi");
            break;
          default:
            console.log("Unknown setting type:", settingType);
        }
      }

      // Keyboard accessibility for settings items
      document.querySelectorAll(".settings-item").forEach((item) => {
        item.addEventListener("keydown", function (e) {
          if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            this.click();
          }
        });
      });

      // Update sidebar active state
      function updateSidebarActive(activeIndex) {
        const sidebarItems = document.querySelectorAll(".sidebar-item");
        const icons = document.querySelectorAll(
          ".sidebar-item .material-symbols-outlined"
        );

        sidebarItems.forEach((item, index) => {
          if (index === activeIndex) {
            item.classList.add("active");
            icons[index].classList.remove("inactive");
            icons[index].classList.add("active");
          } else {
            item.classList.remove("active");
            icons[index].classList.remove("active");
            icons[index].classList.add("inactive");
          }
        });
      }
    </script>
  </body>
</html>
