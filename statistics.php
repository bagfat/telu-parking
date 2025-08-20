<?php 
session_start();

// Handle AJAX request untuk data statistik
if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_stats') {
    header('Content-Type: application/json');
    
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db   = 'db_user';
    
    try {
        $conn = new mysqli($host, $user, $pass, $db);
        
        if ($conn->connect_error) {
            throw new Exception('Connection failed: ' . $conn->connect_error);
        }
        
        $parking = isset($_GET['name']) ? $_GET['name'] : 'Parkiran TULT';
        $date = date('Y-m-d');
        
        $stmt = $conn->prepare("
            SELECT hour, percentage, count_motor
            FROM parking_statistics
            WHERE parking_name = ? AND date = ?
            ORDER BY hour ASC
        ");
        
        if (!$stmt) {
            throw new Exception('Prepare statement failed: ' . $conn->error);
        }
        
        $stmt->bind_param("ss", $parking, $date);
        
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $h = (int)$row['hour'];
            $time = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $data[] = [
                'hour'  => $h,
                'time'  => $time,
                'value' => (int)$row['percentage'],
                'count' => (int)$row['count_motor'],
                'label' => $time
            ];
        }
        
        $stmt->close();
        $conn->close();
        
        echo json_encode($data);
        exit;
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => true,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tel-U Parking - Statistik</title>
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

      /* Statistics specific styles */
      .page-subtitle {
        font-size: 14px;
        color: #10b981;
        margin-bottom: 32px;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
      }

      .stats-grid {
        display: grid;
        gap: 24px;
        grid-template-columns: 1fr;
        min-height: 0;
      }

      @media (min-width: 768px) {
        .stats-grid {
          grid-template-columns: 1fr 1fr;
          gap: 24px;
        }
      }

      @media (min-width: 1200px) {
        .stats-grid {
          grid-template-columns: 1fr 2fr;
        }
      }

      .stats-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 18px;
        padding: 24px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
      }

      .stats-card:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
      }

      /* Circular Progress */
      .progress-container {
        display: flex;
        justify-content: center;
        margin-bottom: 24px;
      }

      .circular-progress {
        position: relative;
        width: 160px;
        height: 160px;
      }

      @media (min-width: 768px) {
        .circular-progress {
          width: 200px;
          height: 200px;
        }
      }

      .progress-ring {
        transform: rotate(-90deg);
      }

      .progress-ring-bg {
        fill: none;
        stroke: rgba(255, 255, 255, 0.1);
        stroke-width: 8;
      }

      .progress-ring-fill {
        fill: none;
        stroke-width: 8;
        stroke-linecap: round;
        transition: stroke-dasharray 1s ease-in-out;
      }

      .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
      }

      .progress-percentage {
        font-size: 32px;
        font-weight: 700;
        color: #fff;
      }

      @media (min-width: 768px) {
        .progress-percentage {
          font-size: 40px;
        }
      }

      .progress-label {
        font-size: 14px;
        color: #d3b6bdcc;
        margin-top: 4px;
      }

      /* Chart Section */
      .chart-section {
        margin-bottom: 40px;
      }

      .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
      }

      .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .chart-title .material-symbols-outlined {
        font-size: 20px;
      }

      .chart-controls {
        display: flex;
        gap: 8px;
      }

      .scroll-button {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
      }

      .scroll-button:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: scale(1.05);
      }

      .scroll-button:disabled {
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.3);
        cursor: not-allowed;
        transform: none;
      }

      /* Bar Chart */
      .chart-container {
        position: relative;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.05);
      }

      .chart-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
      }

      .chart-scroll {
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.3) rgba(255, 255, 255, 0.1);
        scroll-behavior: smooth;
      }

      .chart-scroll::-webkit-scrollbar {
        height: 6px;
      }

      .chart-scroll::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
      }

      .chart-scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
        transition: background 0.3s ease;
      }

      .chart-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
      }

      .chart-bars {
        display: flex;
        align-items: end;
        gap: 20px;
        min-width: 800px;
        height: 220px;
        padding: 20px;
        background: linear-gradient(
          to top,
          rgba(255, 255, 255, 0.02),
          transparent
        );
      }

      .bar-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        min-width: 50px;
        position: relative;
      }

      .bar-container {
        position: relative;
        height: 180px;
        display: flex;
        align-items: end;
      }

      .bar {
        width: 24px;
        border-radius: 12px 12px 4px 4px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        background: linear-gradient(
          to top,
          #dc2626,
          #ef4444,
          rgba(255, 255, 255, 0.2)
        );
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
      }

      .bar:hover {
        transform: scaleY(1.05) scaleX(1.1);
        filter: brightness(1.3);
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5);
      }

      .bar-value {
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 11px;
        font-weight: 600;
        color: #fff;
        background: rgba(0, 0, 0, 0.7);
        padding: 2px 6px;
        border-radius: 4px;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
      }

      .bar:hover .bar-value {
        opacity: 1;
      }

      .bar-label {
        font-size: 13px;
        color: #d3b6bdcc;
        font-weight: 600;
        text-align: center;
      }

      .bar-time {
        font-size: 11px;
        color: #d3b6bd99;
        margin-top: 2px;
      }

      /* Chart indicators */
      .chart-indicators {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 12px;
        color: #d3b6bd99;
      }

      .indicator {
        display: flex;
        align-items: center;
        gap: 6px;
      }

      .indicator-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
      }

      .high-occupancy {
        background: #dc2626;
      }
      .medium-occupancy {
        background: #f59e0b;
      }
      .low-occupancy {
        background: #10b981;
      }

      /* Parking Gallery */
      .parking-gallery {
        margin-bottom: 32px;
      }

      .gallery-scroll {
        overflow-x: auto;
        padding: 10px 0;
      }

      .gallery-scroll::-webkit-scrollbar {
        height: 4px;
      }

      .gallery-scroll::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
      }

      .gallery-scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
      }

      .gallery-items {
        display: flex;
        gap: 16px;
        padding: 0 4px;
      }

      .gallery-item {
        flex-shrink: 0;
        width: 120px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
      }

      @media (min-width: 768px) {
        .gallery-item {
          width: 140px;
          height: 90px;
        }
      }

      .gallery-item:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
      }

      .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        display: flex;
        align-items: end;
        padding: 8px;
      }

      .gallery-text {
        color: white;
        font-size: 11px;
        font-weight: 600;
      }

      /* Detail Button */
      .detail-button {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        padding: 12px 24px;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0 auto;
        max-width: 200px;
      }

      .detail-button:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
      }

      .loading-message {
        text-align: center;
        color: #d3b6bdcc;
        padding: 20px;
        font-style: italic;
      }

      .error-message {
        text-align: center;
        color: #ef4444;
        padding: 20px;
        background: rgba(239, 68, 68, 0.1);
        border-radius: 8px;
        margin: 20px 0;
      }

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

        .stats-grid {
          grid-template-columns: 1fr;
        }

        .chart-bars {
          min-width: 600px;
          gap: 15px;
          padding: 15px;
        }

        .bar {
          width: 20px;
        }

        .chart-controls {
          display: none;
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
        <div
          class="sidebar-item active"
          title="Statistics"
          onclick="goToStatistics()"
        >
          <span class="material-symbols-outlined active">
            bar_chart_4_bars
          </span>
        </div>
        <div class="sidebar-item" title="Settings" onclick="goToSettings()">
          <span class="material-symbols-outlined inactive">settings</span>
        </div>
      </nav>
    </div>
    <main class="main-content">
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

        <div class="header-right">
          <form class="search-bar" role="search" onsubmit="handleSearch(event)">
            <span class="material-symbols-outlined">search</span>
            <input
              type="search"
              placeholder="Mau cari apa nih?"
              id="searchInput"
            />
          </form>
          <button
            class="icon-button"
            title="Notifikasi"
            onclick="showNotifications()"
          >
            <span class="material-symbols-outlined" id="notifications-icon">
              notifications
            </span>
          </button>
          <img
            class="avatar"
            src="assets/<?php echo isset($_SESSION['foto']) ? htmlspecialchars($_SESSION['foto']) : 'default.png'; ?>"
            alt="Avatar User"
            onclick="showProfile()"
          />
        </div>
      </header>

      <!-- Content Area -->
      <div class="content-area" id="contentArea">
        <h1 class="page-title" id="pageTitle">
          <span class="material-symbols-outlined">bar_chart_4_bars</span
          >Statistik Parkiran
        </h1>

        <div class="page-subtitle">
          <div class="status-dot"></div>
          <?php echo date('l, j F Y'); ?>
        </div>

        <div id="loading-message" class="loading-message">
          Loading statistik...
        </div>

        <div id="error-message" class="error-message" style="display: none;">
          Gagal memuat data. Silakan refresh halaman.
        </div>

        <div class="stats-grid" id="stats-grid" style="display: none;">
          <!-- Progress Card -->
          <div class="stats-card">
            <div class="progress-container">
              <div class="circular-progress">
                <svg class="progress-ring" width="100%" height="100%">
                  <circle
                    class="progress-ring-bg"
                    cx="50%"
                    cy="50%"
                    r="70"
                  ></circle>
                  <circle
                    id="progress-fill"
                    class="progress-ring-fill"
                    cx="50%"
                    cy="50%"
                    r="70"
                    stroke="url(#gradient)"
                    stroke-dasharray="0 440"
                  ></circle>
                  <defs>
                    <linearGradient
                      id="gradient"
                      x1="0%"
                      y1="0%"
                      x2="100%"
                      y2="0%"
                    >
                      <stop offset="0%" style="stop-color: #10b981" />
                      <stop offset="100%" style="stop-color: #dc2626" />
                    </linearGradient>
                  </defs>
                </svg>
                <div class="progress-text">
                  <div id="progress-value" class="progress-percentage">0%</div>
                  <div class="progress-label">Terisi</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Chart Card -->
          <div class="stats-card">
            <div class="chart-section">
              <div class="chart-header">
                <div class="chart-title">
                  <span class="material-symbols-outlined">schedule</span>
                  Statistik Per Jam (06:00 - 18:00)
                </div>
                <div class="chart-controls">
                  <button
                    class="scroll-button"
                    id="scroll-left"
                    title="Scroll Left"
                  >
                    <span class="material-symbols-outlined">chevron_left</span>
                  </button>
                  <button
                    class="scroll-button"
                    id="scroll-right"
                    title="Scroll Right"
                  >
                    <span class="material-symbols-outlined">chevron_right</span>
                  </button>
                </div>
              </div>

              <div class="chart-container">
                <div class="chart-wrapper">
                  <div class="chart-scroll" id="chart-scroll">
                    <div class="chart-bars" id="chart-bars">
                      <!-- Bars will be generated by JavaScript -->
                    </div>
                  </div>
                </div>

                <div class="chart-indicators">
                  <div class="indicator">
                    <div class="indicator-dot low-occupancy"></div>
                    <span>Rendah (0-30%)</span>
                  </div>
                  <div class="indicator">
                    <div class="indicator-dot medium-occupancy"></div>
                    <span>Sedang (31-70%)</span>
                  </div>
                  <div class="indicator">
                    <div class="indicator-dot high-occupancy"></div>
                    <span>Tinggi (71-100%)</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Parking Gallery -->
        <div class="parking-gallery" id="parking-gallery" style="display: none;">
          <div class="gallery-scroll">
            <div class="gallery-items">
              <div class="gallery-item">
                <img src="./assets/parkiran-tult.png" alt="Parkiran 1" />
                <div class="gallery-overlay" onclick="goToStatistics()">
                  <div class="gallery-text">Parkiran TULT</div>
                </div>
              </div>
              <div class="gallery-item">
                <img src="./assets/parkiran-asrama.png" alt="Parkiran 2" />
                <div class="gallery-overlay">
                  <div class="gallery-text">Parkiran Asrama</div>
                </div>
              </div>
              <div class="gallery-item">
                <img
                  src="https://images.unsplash.com/photo-1590674899484-d5640e854abe?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                  alt="Parkiran 3"
                />
                <div class="gallery-overlay">
                  <div class="gallery-text">Parkiran Basement</div>
                </div>
              </div>
              <div class="gallery-item">
                <img src="./assets/comming-soon-pict.png" alt="Parkiran 4" />
                <div class="gallery-overlay">
                  <div class="gallery-text">Coming Soon</div>
                </div>
              </div>
              <div class="gallery-item">
                <img src="./assets/comming-soon-pict.png" alt="Parkiran 5" />
                <div class="gallery-overlay">
                  <div class="gallery-text">Coming Soon</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Detail Button -->
        <div id="detail-button-container" style="display: none;">
          <button class="detail-button" onclick="goToDetails()">
            Lihat Detail
            <span class="material-symbols-outlined">chevron_right</span>
          </button>
        </div>
      </div>
    </main>

    <script>
      // Global variables
      let hourlyData = [];

      // Navigation functions
      function goToHome() {
        window.location.href = "home-page.php";
      }

      function goToStatistics() {
        window.location.href = "statistics.php";
      }

      function goToSettings() {
        window.location.href = "settings.php";
      }

      function goToDetails() {
        window.location.href = "detail_stat.html";
      }

      // Search function
      function handleSearch(event) {
        event.preventDefault();
        const searchTerm = document.getElementById("searchInput").value;
        console.log("Searching for:", searchTerm);
        if (searchTerm.trim()) {
          window.location.href = `search.html?q=${encodeURIComponent(searchTerm)}`;
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

      // Fungsi untuk update data statistik
      function updateHourlyStatistics(data) {
        console.log('Data received:', data);
        
        if (!data || !Array.isArray(data) || data.length === 0) {
          console.warn('No data received, using dummy data');
          hourlyData = generateDummyData();
        } else {
          hourlyData = data;
        }
        
        // Hide loading, show content
        document.getElementById('loading-message').style.display = 'none';
        document.getElementById('stats-grid').style.display = 'grid';
        document.getElementById('parking-gallery').style.display = 'block';
        document.getElementById('detail-button-container').style.display = 'block';
        
        // Generate chart
        generateHourlyChart();
      }

      // Generate dummy data untuk fallback
      function generateDummyData() {
        return [
          { hour: 6, time: '06:00', value: 75, count: 75, label: '06:00' },
          { hour: 7, time: '07:00', value: 74, count: 74, label: '07:00' },
          { hour: 8, time: '08:00', value: 73, count: 73, label: '08:00' },
          { hour: 9, time: '09:00', value: 74, count: 74, label: '09:00' },
          { hour: 10, time: '10:00', value: 80, count: 80, label: '10:00' },
          { hour: 11, time: '11:00', value: 85, count: 85, label: '11:00' },
          { hour: 12, time: '12:00', value: 90, count: 90, label: '12:00' },
          { hour: 13, time: '13:00', value: 95, count: 95, label: '13:00' },
          { hour: 14, time: '14:00', value: 92, count: 92, label: '14:00' },
          { hour: 15, time: '15:00', value: 88, count: 88, label: '15:00' },
          { hour: 16, time: '16:00', value: 70, count: 70, label: '16:00' },
          { hour: 17, time: '17:00', value: 50, count: 50, label: '17:00' },
          { hour: 18, time: '18:00', value: 30, count: 30, label: '18:00' }
        ];
      }

      // Generate hourly bar chart
      function generateHourlyChart() {
        const chartBars = document.getElementById("chart-bars");
        chartBars.innerHTML = "";

        if (!hourlyData || hourlyData.length === 0) {
          chartBars.innerHTML = '<div class="error-message">Tidak ada data untuk ditampilkan</div>';
          return;
        }

        hourlyData.forEach((data, index) => {
          const barItem = document.createElement("div");
          barItem.className = "bar-item";

          const barContainer = document.createElement("div");
          barContainer.className = "bar-container";

          const bar = document.createElement("div");
          bar.className = "bar";
          bar.style.height = "0px";
          bar.style.animationDelay = `${index * 0.1}s`;

          // Color based on occupancy level
          let gradient;
          if (data.value <= 30) {
            gradient = "linear-gradient(to top, #10b981, #34d399, rgba(255, 255, 255, 0.1))";
          } else if (data.value <= 70) {
            gradient = "linear-gradient(to top, #f59e0b, #fbbf24, rgba(255, 255, 255, 0.1))";
          } else {
            gradient = "linear-gradient(to top, #dc2626, #ef4444, rgba(255, 255, 255, 0.1))";
          }
          bar.style.background = gradient;

          const barValue = document.createElement("div");
          barValue.className = "bar-value";
          barValue.textContent = `${data.value}%`;

          const label = document.createElement("div");
          label.className = "bar-label";
          label.textContent = data.label;

          const timeLabel = document.createElement("div");
          timeLabel.className = "bar-time";
          timeLabel.textContent = `${data.hour}:00`;

          bar.appendChild(barValue);
          barContainer.appendChild(bar);
          barItem.appendChild(barContainer);
          barItem.appendChild(label);
          barItem.appendChild(timeLabel);
          chartBars.appendChild(barItem);

          // Animate bar height
          setTimeout(() => {
            bar.style.height = `${Math.max(data.value * 1.8, 10)}px`;
          }, index * 100 + 500);

          // Add click handler
          bar.addEventListener("click", () => {
            console.log(`${data.time}: ${data.value}% occupancy`);
            bar.style.transform = "scaleY(1.1) scaleX(1.1)";
            setTimeout(() => {
              bar.style.transform = "scaleY(1) scaleX(1)";
            }, 200);
          });
        });
      }

      // Scroll functionality
      function initializeChartScroll() {
        const chartScroll = document.getElementById("chart-scroll");
        const scrollLeftBtn = document.getElementById("scroll-left");
        const scrollRightBtn = document.getElementById("scroll-right");

        if (!chartScroll || !scrollLeftBtn || !scrollRightBtn) return;

        function updateScrollButtons() {
          const { scrollLeft, scrollWidth, clientWidth } = chartScroll;
          scrollLeftBtn.disabled = scrollLeft <= 0;
          scrollRightBtn.disabled = scrollLeft >= scrollWidth - clientWidth - 1;
        }

        function smoothScroll(direction) {
          const scrollAmount = 200;
          const currentScroll = chartScroll.scrollLeft;
          const targetScroll =
            direction === "left"
              ? Math.max(0, currentScroll - scrollAmount)
              : currentScroll + scrollAmount;

          chartScroll.scrollTo({
            left: targetScroll,
            behavior: "smooth",
          });
        }

        scrollLeftBtn.addEventListener("click", () => smoothScroll("left"));
        scrollRightBtn.addEventListener("click", () => smoothScroll("right"));
        chartScroll.addEventListener("scroll", updateScrollButtons);

        // Initial button state
        setTimeout(updateScrollButtons, 100);
      }

      // Animate circular progress
      function animateProgress() {
        if (!hourlyData || hourlyData.length === 0) return;
        
        const progressFill = document.getElementById("progress-fill");
        const progressValue = document.getElementById("progress-value");

        // Calculate current occupancy
        const currentHour = new Date().getHours();
        let targetPercentage = 50; // Default

        // Find current hour data or closest
        const currentData = hourlyData.find(d => d.hour === currentHour) ||
                           hourlyData.find(d => Math.abs(d.hour - currentHour) <= 1) ||
                           hourlyData[Math.floor(hourlyData.length / 2)]; // Default to middle

        if (currentData) {
          targetPercentage = currentData.value;
        }

        const circumference = 2 * Math.PI * 70;
        let currentPercentage = 0;
        const animationDuration = 2000;
        const startTime = Date.now();

        function updateProgress() {
          const elapsed = Date.now() - startTime;
          const progress = Math.min(elapsed / animationDuration, 1);

          // Easing function
          const easeOutCubic = 1 - Math.pow(1 - progress, 3);
          currentPercentage = targetPercentage * easeOutCubic;
          const dashArray = (currentPercentage / 100) * circumference;

          progressFill.style.strokeDasharray = `${dashArray} ${circumference}`;
          progressValue.textContent = `${Math.round(currentPercentage)}%`;

          if (progress < 1) {
            requestAnimationFrame(updateProgress);
          }
        }

        requestAnimationFrame(updateProgress);
      }

      // Highlight current hour
      function highlightCurrentHour() {
        const currentHour = new Date().getHours();
        const bars = document.querySelectorAll(".bar");

        bars.forEach((bar, index) => {
          const hourData = hourlyData[index];
          if (hourData && hourData.hour === currentHour) {
            bar.style.boxShadow = "0 0 20px rgba(255, 255, 255, 0.5)";
            bar.style.border = "2px solid rgba(255, 255, 255, 0.7)";
          }
        });
      }

      // Show error message
      function showError(message) {
        document.getElementById('loading-message').style.display = 'none';
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        
        // Use dummy data sebagai fallback
        setTimeout(() => {
          updateHourlyStatistics(generateDummyData());
        }, 2000);
      }

      // Fetch data from database
      async function fetchParkingData() {
        try {
          console.log('Fetching parking data...');
          
          const response = await fetch(window.location.href + '?ajax=get_stats&name=Parkiran%20TULT');
          
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const text = await response.text();
          console.log('Raw response:', text);

          let data;
          try {
            data = JSON.parse(text);
          } catch (parseError) {
            throw new Error('Invalid JSON response: ' + text);
          }

          if (data.error) {
            throw new Error(data.message || 'Database error');
          }

          console.log('Parsed data:', data);
          return data;

        } catch (error) {
          console.error('Fetch error:', error);
          throw error;
        }
      }

      // Initialize everything when page loads
      document.addEventListener("DOMContentLoaded", async function () {
        console.log('Page loaded, initializing...');
        
        try {
          const data = await fetchParkingData();
          updateHourlyStatistics(data);
          animateProgress();
          initializeChartScroll();
          setTimeout(highlightCurrentHour, 1000);
          
        } catch (error) {
          console.error('Failed to load data:', error);
          showError('Gagal memuat data dari database. Menggunakan data demo.');
        }
      });

      // Gallery item interactions
      document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
          document.querySelectorAll(".gallery-item").forEach((item) => {
            item.addEventListener("click", function () {
              const parkingName = this.querySelector(".gallery-text").textContent;
              console.log(`Selected parking: ${parkingName}`);

              this.style.transform = "scale(1.1)";
              setTimeout(() => {
                this.style.transform = "scale(1)";
              }, 200);
            });
          });
        }, 1000);
      });

      // Touch/swipe support for mobile
      let startX = 0;
      let scrollLeft = 0;

      document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
          document.querySelectorAll(".chart-scroll, .gallery-scroll").forEach((scrollContainer) => {
            scrollContainer.addEventListener("touchstart", function (e) {
              startX = e.touches[0].pageX - this.offsetLeft;
              scrollLeft = this.scrollLeft;
            });

            scrollContainer.addEventListener("touchmove", function (e) {
              e.preventDefault();
              const x = e.touches[0].pageX - this.offsetLeft;
              const walk = (x - startX) * 2;
              this.scrollLeft = scrollLeft - walk;
            });
          });
        }, 1000);
      });

      // Auto-refresh functionality (optional)
      setInterval(async () => {
        try {
          const data = await fetchParkingData();
          if (data && data.length > 0) {
            updateHourlyStatistics(data);
          }
        } catch (error) {
          console.log('Auto-refresh failed:', error);
        }
      }, 300000); // Refresh every 5 minutes

      // Debug function - panggil dari console untuk testing
      window.debugStats = function() {
        console.log('Current hourlyData:', hourlyData);
        fetchParkingData().then(data => {
          console.log('Fresh data from server:', data);
        }).catch(error => {
          console.error('Debug fetch error:', error);
        });
      };

    </script>
  </body>
</html>