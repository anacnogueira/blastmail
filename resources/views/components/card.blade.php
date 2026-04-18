<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
            <div {{ $attributes->class(['p-6 py-2 text-gray-900 dark:text-gray-100']) }}>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
