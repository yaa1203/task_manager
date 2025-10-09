<section class="space-y-4 sm:space-y-6">
    <header>
        <h2 class="text-base sm:text-lg font-bold text-gray-900">
            Delete Your Account
        </h2>
        <p class="mt-1 text-xs sm:text-sm text-gray-600">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-red-800 mb-1">Warning: This action is irreversible</h4>
                <p class="text-xs text-red-700">All your tasks, projects, and personal data will be permanently deleted.</p>
            </div>
        </div>
    </div>

    <button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Delete My Account
    </button>

    {{-- Delete Confirmation Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                    Confirm Account Deletion
                </h2>
            </div>

            <p class="text-sm text-gray-600 mb-6">
                Are you absolutely sure you want to delete your account? This action cannot be undone. All of your data, including tasks and projects, will be permanently removed from our servers.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirm with your password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password"
                               name="password"
                               type="password"
                               class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm sm:text-base"
                               placeholder="Enter your password">
                    </div>
                    @if($errors->userDeletion->get('password'))
                        <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button"
                            x-on:click="$dispatch('close')"
                            class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Yes, Delete My Account
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</section>