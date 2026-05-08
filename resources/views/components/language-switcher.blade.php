<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
        class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-primary-600 transition font-medium">
        <span>{{ app()->getLocale() === 'bn' ? '🇧🇩 বাংলা' : '🇬🇧 EN' }}</span>
        <svg class="w-3 h-3 transition" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div x-show="open" x-cloak @click.away="open = false" x-transition
        class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
        <a href="{{ route('language.switch', 'bn') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 transition
                   {{ app()->getLocale() === 'bn' ? 'text-primary-600 font-semibold bg-primary-50' : 'text-gray-700' }}">
            🇧🇩 বাংলা
        </a>
        <a href="{{ route('language.switch', 'en') }}"
            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 transition
                   {{ app()->getLocale() === 'en' ? 'text-primary-600 font-semibold bg-primary-50' : 'text-gray-700' }}">
            🇬🇧 English
        </a>
    </div>
</div>
