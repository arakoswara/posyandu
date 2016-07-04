<div class="vernav iconmenu">
	<ul>
        @if($role_user->role_id != 1)
        <li><a href="{{ route('dashboard') }}" class="elements">Dashboard</a></li>
    	<li><a href="{{ route('data-balita') }}" class="inbox">Data Balita</a></li>
        <li><a href="{{ route('edit_profil') }}" class="support">Profil Saya</a></li>

        @else

        <li><a href="{{ route('admin-index') }}" class="support">Tambah Petugas</a></li>

        @endif


    </ul>
    <a class="togglemenu"></a>
    <br /><br />
</div><!--leftmenu-->