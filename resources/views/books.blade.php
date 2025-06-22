<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Книги') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="main-content">
                        <div class="d-flex bd-highlight">
                            <div class="w-25 ms-4 bd-highlight mb-3">
                                <label for="search" class="form-label">Поиск</label>
                                <input type="text" class="form-control search" id="search" aria-describedby="search">  
                            </div>
                            <div class="ms-auto bd-highlight mb-3">
                                <div class="form-check">
                                    <input class="form-check-input filter" type="radio" name="flexRadioDefault" data-filter="normal" id="flexRadioDefault1" checked>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Без фильтров
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input filter" type="radio" name="flexRadioDefault" data-filter="photo" id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Есть фото
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input filter" type="radio" name="flexRadioDefault" data-filter="count" id="flexRadioDefault3">
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        Сортировка по количеству
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input filter" type="radio" name="flexRadioDefault" data-filter="popular" id="flexRadioDefault4">
                                    <label class="form-check-label" for="flexRadioDefault4">
                                        Популярность
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="container" data-entity = "books">
                            
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
