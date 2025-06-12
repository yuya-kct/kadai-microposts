@if (Auth::check())
    {{-- ユーザー一覧ページへのリンク --}}
    {{-- <li><a class="link link-hover" href="{{ route('users.index') }}">Users</a></li> --}}
    {{-- ユーザー詳細ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('users.show', Auth::user()->id) }}">My profile</a></li>
    {{-- お気に入り一覧ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('users.favorites', ['id' => Auth::user()->id]) }}">Favorites</a></li>
    <li class="divider lg:hidden"></li>
    {{-- ログアウトへのリンク --}}
    <li><a class="link link-hover" href="#" onclick="event.preventDefault();this.closest('form').submit();">Logout</a></li>
@else
    {{-- ユーザー登録ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('register') }}">Signup</a></li>
    <li class="divider lg:hidden"></li>
    {{-- ログインページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('login') }}">Login</a></li>
@endif