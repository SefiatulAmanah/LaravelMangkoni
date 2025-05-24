@php
$linkStyle = '
display: block;
font-size: 0.9rem;
color: #495057;
font-weight: 350;
padding: 0.75rem 1.5rem;
border-radius: 10px;
margin-bottom: 6px;
transition: background-color 0.3s ease, color 0.3s ease;
';
$collapseItemStyle = '
font-size: 0.9rem;
color: #495057;
font-weight: 400;
padding: 0.5rem 1.25rem;
border-radius: 6px;
display: block;
margin-bottom: 4px;
transition: background-color 0.3s ease, color 0.3s ease;
';
@endphp
<style>
.nav-link:hover,
.collapse-item:hover {
    background-color: #adb5bd;
    color: rgb(138, 139, 139);
}

.nav-link.active,
.collapse-item.active {
    background-color: rgb(255, 255, 255);
    color: #fff;
    font-weight: 500;
}
</style>
<ul class="navbar-nav sidebar accordion" id="accordionSidebar"
    style="background-color: #ced4da; font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; height: 100vh; overflow-y: auto;">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('login/index') }}"
        style="font-weight: 700; font-size: 1.6rem; color: #343a40; letter-spacing: 3px; padding: 1.5rem 1rem;">
        <div class="sidebar-brand-text mx-3">MANGKONI</div>
    </a>

    <hr class="sidebar-divider my-2">

    <!-- Menu Utama -->
    <li class="nav-item">
        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}" style="{{ $linkStyle }}">
            Dashboard
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Request::is('produk*') ? 'active' : '' }}" href="{{ route('produk.index') }}"
            style="{{ $linkStyle }}">
            Data Produk
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Request::is('produksi*') ? 'active' : '' }}" href="{{ route('produksi.index') }}"
            style="{{ $linkStyle }}">
            Data Produksi
        </a>
    </li>

    <!-- Collapse: Data Penjualan -->
    <li class="nav-item">
        <a class="nav-link {{ Request::is('transaksi*') || Request::is('riwayat*') ? '' : 'collapsed' }}" href="#"
            data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="{{ Request::is('transaksi*') || Request::is('riwayat*') ? 'true' : 'false' }}"
            aria-controls="collapseTwo" style="{{ $linkStyle }}">
            Data Penjualan
        </a>

        <div id="collapseTwo" class="collapse {{ Request::is('transaksi*') || Request::is('riwayat*') ? 'show' : '' }}"
            data-parent="#accordionSidebar" style="margin-left: 0.8rem;">

            <div class="collapse-inner rounded" style="padding: 0.25rem 0;">
                <a class="collapse-item {{ Request::is('transaksi*') ? 'active' : '' }}"
                    href="{{ route('transaksi.index') }}" style="{{ $collapseItemStyle }}">
                    Data Transaksi
                </a>
                <a class="collapse-item {{ Request::is('riwayat*') ? 'active' : '' }}"
                    href="{{ route('riwayat.index') }}" style="{{ $collapseItemStyle }}">
                    Data Riwayat
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Request::is('stok*') ? 'active' : '' }}" href="{{ route('stok.index') }}"
            style="{{ $linkStyle }}">
            Data Stok
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Request::is('retur*') ? 'active' : '' }}" href="{{ route('retur.index') }}"
            style="{{ $linkStyle }}">
            Data Retur
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Request::is('peramalan*') ? 'active' : '' }}" href="{{ url('/peramalan') }}"
            style="{{ $linkStyle }}">
            Peramalan
        </a>
    </li>

    <!-- LOGOUT -->
    <li class="nav-item">
        <a class="nav-link" href="#" style="{{ $linkStyle }}" id="logout-link">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </li>




    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"
            style="background-color: #ced4da; width: 38px; height: 38px;"></button>
    </div>

    <hr class="sidebar-divider mt-4">
</ul>