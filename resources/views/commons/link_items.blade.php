@if (Auth::check())
    <li>
        <a class="link link-hover flex items-center gap-2" 
            href="{{ route('users.show', Auth::user()->id) }}">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
            </svg>
            My Profile
        </a>
    </li>
    <li>
        <a class="link link-hover flex items-center gap-2" 
            href="{{ route('users.favorites', Auth::user()->id) }}">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
            </svg>
            Favorites
        </a>
    </li>
    {{-- コミュニティ関連リンクを追加 --}}
    <li>
        <a class="link link-hover flex items-center gap-2" 
            href="{{ route('communities.index') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            My Communities
        </a>
    </li>
    <li>
        <a class="link link-hover flex items-center gap-2" 
            href="{{ route('communities.create') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Community
        </a>
    </li>
    <li class="divider lg:hidden"></li>
    <li>
        <a class="link link-hover flex items-center gap-2" href="#" 
            onclick="event.preventDefault(); this.closest('form').submit();">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </a>
    </li>
@else
    <li>
        <a class="link link-hover" href="{{ route('register') }}">
            Sign Up
        </a>
    </li>
    <li class="divider lg:hidden"></li>
    <li>
        <a class="link link-hover" href="{{ route('login') }}">
            Login
        </a>
    </li>
@endif