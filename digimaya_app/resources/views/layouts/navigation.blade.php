<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}">
                        <x-application-logo class="block h-6 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @auth
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'marketing']))
                            <div class="relative inline-flex" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.marketing.*', 'admin.leads.*', 'admin.blog-categories.*', 'admin.blog-posts.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                                    <div>{{ __('Marketing') }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="open" @click="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 mt-12 start-0 w-48 rounded-md shadow-lg origin-top-left">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="{{ route('admin.marketing.overview') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Overview') }}</a>
                                        <a href="{{ route('admin.leads.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Leads') }}</a>
                                        <a href="{{ route('admin.blog-posts.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Blog Posts') }}</a>
                                        <a href="{{ route('admin.blog-categories.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Blog Categories') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <div class="relative inline-flex" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.crm.*', 'admin.clients.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                                    <div>{{ __('CRM') }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="open" @click="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 mt-12 start-0 w-48 rounded-md shadow-lg origin-top-left">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="{{ route('admin.crm.overview') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Overview') }}</a>
                                        <a href="{{ route('admin.clients.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Clients') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'account_manager', 'advertiser']))
                            <div class="relative inline-flex" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.projects.*', 'admin.operations.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                                    <div>{{ __('Operations') }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="open" @click="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 mt-12 start-0 w-48 rounded-md shadow-lg origin-top-left">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="{{ route('admin.operations.overview') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Overview') }}</a>
                                        <a href="{{ route('admin.projects.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Projects') }}</a>
                                        @if(auth()->user()->isAccountManager())
                                            <a href="{{ route('admin.operations.clients.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('My Clients') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin']))
                            <div class="relative inline-flex" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.academy.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                                    <div>{{ __('Academy') }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="open" @click="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 mt-12 start-0 w-48 rounded-md shadow-lg origin-top-left">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="{{ route('admin.academy.overview') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Overview') }}</a>
                                        <a href="{{ route('admin.academy.modules.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Modules') }}</a>
                                        <a href="{{ route('admin.academy.members.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Members') }}</a>
                                        <a href="{{ route('admin.academy.certificate-requests.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Certificate Requests') }}</a>
                                        <a href="{{ route('admin.academy.certificates.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Certificates') }}</a>

                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <div class="relative inline-flex" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.incomes.*', 'admin.expenses.*', 'admin.invoices.*', 'admin.services.*', 'admin.balances.*', 'admin.finance.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                                    <div>{{ __('Finance') }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="open" @click="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 mt-12 start-0 w-48 rounded-md shadow-lg origin-top-left">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="{{ route('admin.finance.overview') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Overview') }}</a>
                                        <a href="{{ route('admin.incomes.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Income') }}</a>
                                        <a href="{{ route('admin.expenses.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Expense') }}</a>
                                        <a href="{{ route('admin.invoices.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Invoices') }}</a>
                                        @if(auth()->user()->isSuperAdmin())
                                            <a href="{{ route('admin.services.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{__('Services') }}</a>
                                        @endif
                                        <a href="{{ route('admin.balances.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Balance') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->isSuperAdmin())
                            <div class="relative inline-flex" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.public-services.*', 'admin.comparison-rows.*', 'admin.testimonials.*', 'admin.case-studies.*', 'admin.faqs.*', 'admin.logo-wall.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                                    <div>{{ __('Components') }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="open" @click="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 mt-12 start-0 w-48 rounded-md shadow-lg origin-top-left">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="{{ route('admin.public-services.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Services') }}</a>
                                        <a href="{{ route('admin.comparison-rows.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Comparison Table') }}</a>
                                        <a href="{{ route('admin.testimonials.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{__('Testimonials') }}</a>
                                        <a href="{{ route('admin.case-studies.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{__('Case Studies') }}</a>
                                        <a href="{{ route('admin.faqs.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('FAQs') }}</a>
                                        <a href="{{ route('admin.logo-wall.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Logo Wall') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->isSuperAdmin())
                            <div class="relative inline-flex" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.users.*', 'admin.activity-log.*', 'admin.issue-categories.*', 'admin.troubleshooter.*', 'admin.settings.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                                    <div>{{ __('System') }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="open" @click="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 mt-12 start-0 w-48 rounded-md shadow-lg origin-top-left">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="{{ route('admin.users.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Users') }}</a>
                                        <a href="{{ route('admin.activity-log.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Activity Log') }}</a>
                                        <a href="{{ route('admin.issue-categories.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Issue Categories') }}</a>
                                        <a href="{{ route('admin.troubleshooter.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Troubleshooter') }}</a>
                                        <a href="{{ route('admin.settings.index') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">{{ __('Settings') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-gray-400">
                            {{ Auth::user()->role_label }}
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    class="text-red-600 hover:text-red-700"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'marketing']))
                    <div class="pt-2 pb-1 border-t border-gray-200">
                        <div class="px-4 py-1 text-xs font-semibold text-gray-400 uppercase">Marketing</div>
                    </div>
                    <x-responsive-nav-link :href="route('admin.marketing.overview')" :active="request()->routeIs('admin.marketing.*')">
                        {{ __('Overview') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.leads.index')" :active="request()->routeIs('admin.leads.*')">
                        {{ __('Leads') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.blog-posts.index')" :active="request()->routeIs('admin.blog-posts.*')">
                        {{ __('Blog Posts') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.blog-categories.index')" :active="request()->routeIs('admin.blog-categories.*')">
                        {{ __('Blog Categories') }}
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="pt-2 pb-1 border-t border-gray-200">
                        <div class="px-4 py-1 text-xs font-semibold text-gray-400 uppercase">CRM</div>
                    </div>
                    <x-responsive-nav-link :href="route('admin.crm.overview')" :active="request()->routeIs('admin.crm.*')">
                        {{ __('Overview') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.clients.index')" :active="request()->routeIs('admin.clients.*')">
                        {{ __('Clients') }}
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'account_manager', 'advertiser']))
                    <div class="pt-2 pb-1 border-t border-gray-200">
                        <div class="px-4 py-1 text-xs font-semibold text-gray-400 uppercase">Operations</div>
                    </div>
                    <x-responsive-nav-link :href="route('admin.operations.overview')" :active="request()->routeIs('admin.operations.*')">
                        {{ __('Overview') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.projects.index')" :active="request()->routeIs('admin.projects.*')">
                        {{ __('Projects') }}
                    </x-responsive-nav-link>
                    @if(auth()->user()->isAccountManager())
                        <x-responsive-nav-link :href="route('admin.operations.clients.index')" :active="request()->routeIs('admin.operations.clients.*')">
                            {{ __('My Clients') }}
                        </x-responsive-nav-link>
                    @endif
                @endif

                @if(auth()->user()->hasAnyRole(['super_admin', 'admin']))
                    <div class="pt-2 pb-1 border-t border-gray-200">
                        <div class="px-4 py-1 text-xs font-semibold text-gray-400 uppercase">Academy</div>
                    </div>
                    <x-responsive-nav-link :href="route('admin.academy.overview')" :active="request()->routeIs('admin.academy.overview')">
                        {{ __('Overview') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.academy.modules.index')" :active="request()->routeIs('admin.academy.modules.*')">
                        {{ __('Modules') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.academy.members.index')" :active="request()->routeIs('admin.academy.members.*')">
                        {{ __('Members') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.academy.certificate-requests.index')" :active="request()->routeIs('admin.academy.certificate-requests.*')">
                        {{ __('Certificate Requests') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.academy.certificates.index')" :active="request()->routeIs('admin.academy.certificates.*')">
                        {{ __('Certificates') }}
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="pt-2 pb-1 border-t border-gray-200">
                        <div class="px-4 py-1 text-xs font-semibold text-gray-400 uppercase">Finance</div>
                    </div>
                    <x-responsive-nav-link :href="route('admin.finance.overview')" :active="request()->routeIs('admin.finance.*')">
                        {{ __('Overview') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.incomes.index')" :active="request()->routeIs('admin.incomes.*')">
                        {{ __('Income') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.expenses.index')" :active="request()->routeIs('admin.expenses.*')">
                        {{ __('Expense') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.invoices.index')" :active="request()->routeIs('admin.invoices.*')">
                        {{ __('Invoices') }}
                    </x-responsive-nav-link>
                    @if(auth()->user()->isSuperAdmin())
                        <x-responsive-nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')">
                            {{ __('Services') }}
                        </x-responsive-nav-link>
                    @endif
                    <x-responsive-nav-link :href="route('admin.balances.index')" :active="request()->routeIs('admin.balances.*')">
                        {{ __('Balance') }}
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    <div class="pt-2 pb-1 border-t border-gray-200">
                        <div class="px-4 py-1 text-xs font-semibold text-gray-400 uppercase">Components</div>
                    </div>
                    <x-responsive-nav-link :href="route('admin.public-services.index')" :active="request()->routeIs('admin.public-services.*')">
                        {{ __('Services') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.comparison-rows.index')" :active="request()->routeIs('admin.comparison-rows.*')">
                        {{ __('Comparison Table') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')">
                        {{ __('Testimonials') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.case-studies.index')" :active="request()->routeIs('admin.case-studies.*')">
                        {{ __('Case Studies') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.faqs.index')" :active="request()->routeIs('admin.faqs.*')">
                        {{ __('FAQs') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.logo-wall.index')" :active="request()->routeIs('admin.logo-wall.*')">
                        {{ __('Logo Wall') }}
                    </x-responsive-nav-link>

                    <div class="pt-2 pb-1 border-t border-gray-200">
                        <div class="px-4 py-1 text-xs font-semibold text-gray-400 uppercase">System</div>
                    </div>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.activity-log.index')" :active="request()->routeIs('admin.activity-log.*')">
                        {{ __('Activity Log') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.issue-categories.index')" :active="request()->routeIs('admin.issue-categories.*')">
                        {{ __('Issue Categories') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.troubleshooter.index')" :active="request()->routeIs('admin.troubleshooter.*')">
                        {{ __('Troubleshooter') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="font-medium text-xs text-gray-400 mt-1">{{ Auth::user()->role_label }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            class="text-red-600 hover:text-red-700"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>