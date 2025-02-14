<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
  
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="products">
    <div class="sidebar-brand-text">PPIC</div>
  </a>
  
  <!-- Divider -->
  <hr class="sidebar-divider my-0">
  
  <!-- Heading -->
  <div class="sidebar-heading">PRODUCTION SCHEDULE</div>

  <li class="nav-item">
    <a class="nav-link d-flex align-items-center" href="{{ route('products') }}">
      <i class="fas fa-industry"></i>
      <span class="sidebar-text">INJECTION MOLDING</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler -->
  <div class="sidebar-toggle-container">
    <button id="sidebarToggle" onclick="toggleSidebar()">
      <i class="fas fa-chevron-left"></i>
    </button>
  </div>

</ul>

<!-- CSS -->
<style>
  /* Sidebar */
  .sidebar {
    background: linear-gradient(180deg, #ADE8F4, #03045E);
    width: 250px;
    height: 125vh;
    transition: width 0.4s ease-in-out;
    position: relative;
  }

  /* PPIC Title */
  .sidebar-brand-text {
    font-size: 2.2rem;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  /* Heading */
  .sidebar-heading {
    color: white;
    font-size: 1.4rem;
    padding-left: 20px;
    font-weight: bold;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* Menu Item */
  .nav-link {
    color: white;
    display: flex;
    align-items: center;
    padding: 14px 20px;
    font-size: 1.4rem;
    font-weight: bold;
    transition: background 0.3s ease-in-out, transform 0.2s;
    border-radius: 8px;
  }

  /* Ikon */
  .nav-link i {
    font-size: 1.6rem;
    margin-right: 12px;
    transition: margin 0.3s ease-in-out;
  }

  /* Hover Efek */
  .nav-link:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateX(5px);
  }

  /* Sidebar Toggle Button */
  .sidebar-toggle-container {
    position: absolute;
    top: 50%;
    right: -25px;
    transform: translate(0, -50%);
  }

  #sidebarToggle {
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: none;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease-in-out;
    cursor: pointer;
  }

  /* Hover Efek untuk Tombol Toggle */
  #sidebarToggle:hover {
    background: rgba(255, 255, 255, 0.5);
    transform: scale(1.1);
  }

  /* Klik Efek */
  #sidebarToggle:active {
    transform: scale(0.9);
  }

  /* Saat sidebar ditutup */
  .sidebar.closed {
    width: 80px;
  }

  .sidebar.closed .sidebar-text {
    opacity: 0;
  }

  .sidebar.closed .nav-link i {
    margin-right: 0;
  }

  .sidebar.closed .sidebar-brand-text {
    font-size: 1.5rem;
  }
</style>

<!-- JavaScript -->
<script>
  function toggleSidebar() {
    let sidebar = document.getElementById("accordionSidebar");
    let buttonIcon = document.getElementById("sidebarToggle").querySelector("i");

    sidebar.classList.toggle("closed");

    if (sidebar.classList.contains("closed")) {
      buttonIcon.classList.replace("fa-chevron-left", "fa-chevron-right");
    } else {
      buttonIcon.classList.replace("fa-chevron-right", "fa-chevron-left");
    }
  }
</script>
