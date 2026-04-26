<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-x-3">
            <div class="flex-1">
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                    SEO & Sitemap
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Perbarui sitemap.xml untuk membantu mesin pencari mengindeks website Anda.
                </p>
            </div>
            
            <div class="flex items-center gap-x-3">
                <x-filament::button 
                    href="/sitemap.xml"
                    tag="a"
                    target="_blank"
                    icon="heroicon-m-eye"
                    color="gray"
                    variant="outline"
                >
                    Lihat Sitemap
                </x-filament::button>

                <x-filament::button 
                    wire:click="generateSitemap"
                    icon="heroicon-m-globe-alt"
                    color="primary"
                >
                    Generate Sitemap
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
