<x-layout page="Manage users">

    @if ((Auth::user()->Active && Auth::user()->UserType == 1) || Auth::user()->UserType == 0)
        <div id="users">
            <App/>
        </div>
    @else
        <h1 class="text-center py-3">
            Il tuo account da Admin è stato disattivato.
        </h1>
    @endif
</x-layout>