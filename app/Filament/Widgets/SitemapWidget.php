<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use App\Models\Profile;

class SitemapWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.sitemap-widget';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'seo_keywords' => Profile::first()?->seo_keywords,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Textarea::make('seo_keywords')
                    ->label('Custom SEO Keywords')
                    ->placeholder('Contoh: laravel, web development, svelte, inertiajs')
                    ->helperText('Daftar kata kunci tambahan untuk meningkatkan SEO. Pisahkan setiap kata kunci dengan koma.')
                    ->rows(3),
            ])
            ->statePath('data');
    }

    public function saveKeywords()
    {
        $profile = Profile::first();
        if ($profile) {
            $profile->update([
                'seo_keywords' => $this->data['seo_keywords'],
            ]);

            Notification::make()
                ->title('Keywords Berhasil Disimpan')
                ->body('Daftar kata kunci SEO telah diperbarui.')
                ->success()
                ->send();
        }
    }


}
