<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Авторы') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="main-content">
                        <div class="w-25 ms-4 mb-3">
                            <label for="search" class="form-label">Поиск</label>
                            <input type="text" class="form-control search" id="search" aria-describedby="search">  
                        </div>
                        <div class="container" data-entity = "authors">
                            
                        </div>
                        <div class="ms-4 mb-3">
                            <button type="button" class="btn btn-primary previous_page">Предыдущая страница</button>
                            <button type="button" class="btn btn-primary next_page">Следующая страница</button>
                        </div>
                        <div class="page-info">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
