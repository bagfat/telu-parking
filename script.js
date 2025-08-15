// Fungsi untuk navigasi ke halaman statistik
function goToStatistics() {
  window.location.href = "statistics.html";
}

// Fungsi untuk navigasi ke halaman settings
function goToSettings() {
  window.location.href = "settings.html";
}

// Fungsi untuk navigasi kembali ke home
function goToHome() {
  window.location.href = "index.html"; // sesuaikan dengan nama file home Anda
}

// Fungsi untuk detail parking
function goToDetails() {
  window.location.href = "details.html";
}

// Fungsi untuk search
function handleSearch(event) {
  event.preventDefault();
  const searchTerm = document.getElementById("searchInput").value;
  console.log("Searching for:", searchTerm);
  // Implementasi search logic di sini
  if (searchTerm.trim()) {
    // Contoh: redirect ke halaman search dengan parameter
    window.location.href = `search.html?q=${encodeURIComponent(searchTerm)}`;
  }
}

// Fungsi untuk notifikasi
function showNotifications() {
  alert("Menampilkan notifikasi");
  // Anda bisa ganti dengan modal atau dropdown
}

// Fungsi untuk profile
function showProfile() {
  alert("Menampilkan profil user");
  // Anda bisa redirect ke halaman profile atau buka modal
  // window.location.href = 'profile.html';
}

// Optional: Fungsi untuk update active state sidebar
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
