<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center px-4 py-2 border
                border-gray-400 active:border-gray-300 rounded-md
                font-semibold text-sm text-gray-100 hover:text-gray-500 active:text-gray-100 dark:text-gray-400 dark:hover:text-gray-800 dark:active:text-gray-900
                bg-gray-400 hover:bg-gray-300 active:bg-gray-500 dark:bg-gray-700 dark:hover:bg-gray-500 dark:active:bg-gray-400
                focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition
                ']) }}>
    {{ $slot }}
</button>
