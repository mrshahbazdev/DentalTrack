<div class="flex items-center gap-1">
    <a href="?lang=de"
       class="text-xs font-semibold px-2 py-1 rounded transition {{ app()->getLocale() === 'de' ? 'bg-primary-50 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
        DE
    </a>
    <a href="?lang=en"
       class="text-xs font-semibold px-2 py-1 rounded transition {{ app()->getLocale() === 'en' ? 'bg-primary-50 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
        EN
    </a>
    <a href="?lang=ur"
       class="text-xs font-semibold px-2 py-1 rounded transition {{ app()->getLocale() === 'ur' ? 'bg-primary-50 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
        اردو
    </a>
</div>
