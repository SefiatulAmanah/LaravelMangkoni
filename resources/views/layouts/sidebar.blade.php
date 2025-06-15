@php
$active = fn($route) => Request::is($route) ? 'active' : '';
$activeCollapse = fn($patterns) => collect($patterns)->contains(fn($pattern) => Request::is($pattern)) ? 'show' : '';
$collapsedClass = fn($patterns) => collect($patterns)->contains(fn($pattern) => Request::is($pattern)) ? '' :
'collapsed';
$ariaExpanded = fn($patterns) => collect($patterns)->contains(fn($pattern) => Request::is($pattern)) ? 'true' : 'false';
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
/* Sidebar styling */
#accordionSidebar {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 1.05rem;
    font-weight: 500;
    height: 100vh;
    border-right: 1px solid #dee2e6;
    padding: 0 1.5rem;
    width: 280px;
    box-sizing: border-box;
    position: fixed;
    top: 0px;
    /* sesuaikan navbar height */
    left: 0;
    z-index: 1040;
    overflow-y: auto;
    transition: transform 0.3s ease;
}

/* Saat sidebar disembunyikan */
#accordionSidebar.collapsed {
    transform: translateX(-100%);
}

/* Konten utama geser saat sidebar muncul (optional) */
#mainContent {
    margin-left: 280px;
    transition: margin-left 0.3s ease;
}

#mainContent.expanded {
    margin-left: 0;
}

/* Sidebar Brand */
.sidebar-brand {
    font-size: 1.8rem;
    font-weight: 700;
    color: #212529 !important;
    text-align: left;
    padding: 1.5rem 0 1rem 0;
    margin-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

/* Nav-link styling */
.nav-link {
    color: #343a40;
    padding: 0.65rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border-radius: 8px;
    transition: background-color 0.2s ease;
}

.nav-link:hover {
    background-color: #e2e6ea;
    color: #212529;
}

.nav-link.active {
    background-color: rgb(48, 48, 49);
    color: #fff !important;
    font-weight: 600;
    border-left: 4px solid rgb(12, 12, 12);
    padding-left: calc(1.25rem - 4px);
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease-in-out;
}

.collapse .nav-link {
    padding-left: 1.5rem;
    padding-top: 0.6rem;
    padding-bottom: 0.6rem;
    font-size: 1rem;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    border-radius: 0.25rem;
    transition: background-color 0.2s ease;
}

.collapse .nav-link:hover {
    background-color: #e2e6ea;
    color: #212529;
}

.collapse .nav-link.active {
    background-color: rgb(48, 48, 49);
    color: #fff !important;
    font-weight: 600;
    border-left: 4px solid rgb(48, 48, 49);
    padding-left: calc(1.5rem - 4px);
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
    border-radius: 0.25rem;
}

.sidebar-divider {
    border-top: 1px solid #dee2e6;
    margin: 1rem 0;
}

.bi {
    font-size: 1.2rem;
}

#sidebarToggle {
    position: fixed;
    top: 10px;
    left: 10px;
    z-index: 9999 !important;
    background-color: rgba(54, 55, 56, 0.9);
    border: none;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: none;
    justify-content: center;
    align-items: center;
    font-size: 28px;
    cursor: pointer;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
}

#sidebarToggle:hover {
    background-color: #495057;
}

/* Media query */
@media (max-width: 991.98px) {
    #sidebarToggle {
        display: flex !important;
    }
}


#sidebarToggle:hover {
    background-color: #495057;
    cursor: pointer;
}

/* Responsive: tombol toggle muncul di layar kecil */
@media (max-width: 991.98px) {
    #sidebarToggle {
        display: flex;
    }

    #accordionSidebar {
        top: 56px;
        position: fixed;
        height: calc(100vh - 56px);
        transform: translateX(-100%);
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        width: 250px;
    }

    #accordionSidebar.show {
        transform: translateX(0);
        background-color: #f8f9fa;
    }

    #mainContent {
        margin-left: 0 !important;
        transition: none;
    }
}
</style>

<!-- Toggle Button -->
<button id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
</button>

<!-- Sidebar -->
<ul class="navbar-nav" id="accordionSidebar">
    <a class="sidebar-brand" href="{{ url('/') }}">MANGKONI</a>

    <li class="nav-item">
        <a class="nav-link {{ $active('/') }}" href="{{ url('/') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}" href="{{ route('produk.index') }}">
            <i class="bi bi-box-seam"></i> Data Produk
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}" href="{{ route('produksi.index') }}">
            <i class="bi bi-hammer"></i> Data Produksi
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ $collapsedClass(['transaksi*', 'riwayat*']) }}" href="#collapsePenjualan"
            data-bs-toggle="collapse" role="button" aria-expanded="{{ $ariaExpanded(['transaksi*', 'riwayat*']) }}"
            aria-controls="collapsePenjualan">
            <i class="bi bi-cart4"></i> Data Penjualan
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <div class="collapse {{ $activeCollapse(['transaksi*', 'riwayat*']) }}" id="collapsePenjualan">
            <ul class="list-unstyled ps-3">
                <li>
                    <a class="nav-link {{ $active('transaksi*') }}" href="{{ route('transaksi.index') }}">
                        <i class="bi bi-bag"></i> Data Transaksi
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ $active('riwayat*') }}" href="{{ route('riwayat.index') }}">
                        <i class="bi bi-clock-history"></i> Data Riwayat
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ $active('stok*') }}" href="{{ route('stok.index') }}">
            <i class="bi bi-boxes"></i> Data Stok
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ $active('retur*') }}" href="{{ route('retur.index') }}">
            <i class="bi bi-arrow-return-left"></i> Data Retur
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ $active('peramalan*') }}" href="{{ url('/peramalan') }}">
            <i class="bi bi-graph-up-arrow"></i> Peramalan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-danger" href="#"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </li>
</ul>

<!-- Main content wrapper -->
<div id="mainContent">
    {{-- Konten utama halaman di sini --}}
</div>

<script>
const sidebar = document.getElementById('accordionSidebar');
const toggleBtn = document.getElementById('sidebarToggle');
const mainContent = document.getElementById('mainContent');

toggleBtn.addEventListener('click', () => {
    // Untuk mobile toggle class show
    if (window.innerWidth < 992) {
        sidebar.classList.toggle('show');
    } else {
        // Untuk desktop toggle collapsed class + margin mainContent
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
    }
});

// Optional: otomatis sembunyikan sidebar saat klik link di mobile
sidebar.querySelectorAll('a.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 992) {
            sidebar.classList.remove('show');
        }
    });
});
</script>