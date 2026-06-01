<section class="bg-gradient-to-r from-[#2D6A4F] to-[#52B788] py-16 text-white text-center"
    x-data="{ email: '', msg: '' }">
    <div class="max-w-xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-3">{{ __('general.get_tips_in_inbox') }}</h2>
        <p class="text-[#95D5B2] mb-6">{{ __('general.subscribe_for_offers') }}</p>
        <div class="flex gap-2 max-w-md mx-auto">
            <input x-model="email" type="email" placeholder="{{ __('Enter your email') }}"
                class="flex-1 rounded-l-lg px-4 py-3 text-gray-800 outline-none">
            <button @click="
                fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({email})
                }).then(r => r.json()).then(d => { msg = d.message; email = ''; });"
                class="bg-[#8B6914] text-white px-6 py-3 rounded-r-lg font-semibold hover:bg-[#95D5B2] hover:text-[#2D6A4F] transition">
                {{ __('general.subscribe') }}
            </button>
        </div>
        <p x-show="msg" x-text="msg" class="mt-3 text-[#95D5B2] text-sm"></p>
    </div>
</section>
