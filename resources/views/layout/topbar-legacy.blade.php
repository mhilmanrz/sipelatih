<div class="topbar" id="topbar">
  <i class="fa fa-bars" onclick="toggleSidebar()"></i>

  <div class="profile-area">
    <i class="fa fa-bell"></i>

    <span class="user-badge" onclick="toggleProfileMenu()">
      <i class="fa fa-user"></i> {{ auth()->check() ? auth()->user()->name : 'Guest' }}
    </span>

    <div class="profile-menu" id="profileMenu">
      <a href="#" onclick="showPage('password');return false;">🔑 Ubah Password</a>
      
      <form method="POST" action="{{ url('/logout') }}" id="logout-form" style="display: none;">
          @csrf
      </form>
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Log Out</a>
    </div>
  </div>
</div>
