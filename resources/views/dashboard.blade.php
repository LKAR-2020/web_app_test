<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <form  method="POST" action="{{ route('profile.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_photo">
                    <button type="submit">Télécharger</button>
                </form>
                <div class="flex items-center space-x-4">
                    @if(Auth::user()->photo_de_profil)
                    <img src="{{ asset(Auth::user()->photo_de_profil) }}" alt="Photo de profil" width="88" height="88" class="flex-none rounded-lg bg-slate-100" loading="lazy" />
                    @else
                    <p>Aucune photo de profil</p>
                    @endif
                    <div class="min-w-0 flex-auto space-y-1 font-semibold">
                      <p class="text-cyan-500 dark:text-cyan-400 text-sm leading-6">
                        {{ Auth::user()->name }}
                      </p>
                      <h2 class="text-slate-500 dark:text-slate-400 text-sm leading-6 truncate">
                        {{ Auth::user()->email }}
                      </h2>
                      <p class="text-slate-900 dark:text-slate-50 text-lg">
                        Full Stack Radio
                      </p>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</x-app-layout>
