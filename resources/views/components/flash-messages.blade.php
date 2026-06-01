@php
    $flashTypes = [
        'success' => ['bg' => 'bg-green-50',  'border' => 'border-green-200',  'text' => 'text-green-800',  'btn' => 'text-green-400 hover:text-green-700'],
        'error'   => ['bg' => 'bg-red-50',    'border' => 'border-red-200',    'text' => 'text-red-800',    'btn' => 'text-red-400 hover:text-red-700'],
        'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-800', 'btn' => 'text-yellow-400 hover:text-yellow-700'],
        'info'    => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'text' => 'text-blue-800',   'btn' => 'text-blue-400 hover:text-blue-700'],
    ];
@endphp

@foreach($flashTypes as $type => $classes)
    @if(session($type))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="{{ $classes['bg'] }} {{ $classes['border'] }} {{ $classes['text'] }} border rounded-xl px-4 py-3 flex justify-between items-start gap-3">
                <span>{{ session($type) }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="{{ $classes['btn'] }} text-xl leading-none mt-0.5">&times;</button>
            </div>
        </div>
    @endif
@endforeach
