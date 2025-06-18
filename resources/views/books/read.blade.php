<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reading: {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" x-data="reader({ currentPage: {{ $userLibrary->current_page ?? 0 }}, totalPages: {{ $book->page_count ?? 500 }}, bookId: {{ $book->id }} })">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 leading-relaxed">
                    <h3 class="text-2xl font-bold mb-4">{{ $book->title }}</h3>
                    <h4 class="text-lg font-medium text-gray-700 mb-6">by {{ $book->authors_string }}</h4>

                    <div class="prose max-w-none">
                        <p>This is a simulated reading experience. The full text of the book is not available here.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                            voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                            cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <p>Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et
                            commodo pharetra, est eros bibendum elit, nec luctus magna felis sollicitudin mauris.
                            Integer in mauris eu nibh euismod gravida. Duis ac tellus et risus vulputate vehicula. Donec
                            lobortis risus a elit. Etiam tempor. Ut ullamcorper, ligula eu tempor congue, eros est
                            euismod turpis, id tincidunt sapien risus a quam. Maecenas fermentum consequat mi. Donec vel
                            mi sed turpis facilisis luctus. Integer non enim in tortor consectetuer lacinia. Nam rhoncus
                            hrisus vel metus.</p>
                    </div>
                </div>
            </div>

            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-md p-4">
                <div class="max-w-4xl mx-auto flex items-center justify-between">
                    <button @click="prevPage()" :disabled="currentPage === 0"
                        class="px-4 py-2 bg-gray-200 rounded-md disabled:opacity-50">
                        Previous
                    </button>

                    <div class="text-center">
                        <p class="font-semibold text-gray-800">Page <span x-text="currentPage + 1"></span> of <span
                                x-text="totalPages"></span></p>
                        <progress :value="currentPage + 1" :max="totalPages" class="w-48 mt-1"></progress>
                    </div>

                    <button @click="nextPage()" :disabled="currentPage >= totalPages - 1"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md disabled:opacity-50">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function reader({
            currentPage,
            totalPages,
            bookId
        }) {
            return {
                currentPage: currentPage,
                totalPages: totalPages,
                bookId: bookId,

                init() {
                    // Save progress every 15 seconds
                    setInterval(() => {
                        this.saveProgress();
                    }, 15000);
                },

                nextPage() {
                    if (this.currentPage < this.totalPages - 1) {
                        this.currentPage++;
                        this.saveProgress();
                    }
                },

                prevPage() {
                    if (this.currentPage > 0) {
                        this.currentPage--;
                        this.saveProgress();
                    }
                },

                saveProgress() {
                    const url = `/books/${this.bookId}/progress`;

                    axios.patch(url, {
                            current_page: this.currentPage,
                        })
                        .then(response => {
                            console.log('Progress saved:', this.currentPage);
                        })
                        .catch(error => {
                            console.error('Failed to save progress:', error);
                        });
                }
            }
        }
    </script>
</x-app-layout>
