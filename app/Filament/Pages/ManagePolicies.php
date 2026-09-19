<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use Filament\Forms\Components\RichEditor;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class ManagePolicies extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'Kebijakan';
    protected static ?string $navigationLabel = 'Kebijakan';
    protected static ?int $navigationSort = 3;

    public ?array $data = [];

    public function mount(): void
    {
        $profile = Profile::first();
        if ($profile) {
            $this->getSchema('content')->fill($profile->toArray());
        }
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                \Filament\Schemas\Components\Section::make('Halaman Kebijakan (Legal)')
                    ->description('Kelola konten untuk halaman Syarat & Ketentuan, Kebijakan Privasi, dan Kebijakan Pengembalian Dana.')
                    ->schema([
                        RichEditor::make('terms_and_conditions')
                            ->label('Syarat & Ketentuan')
                            ->columnSpanFull(),
                        RichEditor::make('privacy_policy')
                            ->label('Kebijakan Privasi')
                            ->columnSpanFull(),
                        RichEditor::make('refund_policy')
                            ->label('Kebijakan Pengembalian Dana')
                            ->columnSpanFull(),
                    ])
                    ->footerActions([
                        Action::make('save')
                            ->label('Simpan Perubahan')
                            ->action(fn () => $this->save()),
                    ])
                    ->footerActionsAlignment(\Filament\Support\Enums\Alignment::End)
            ]);
    }

    public function save(): void
    {
        $data = $this->getSchema('content')->getState();

        Profile::updateOrCreate(['id' => 1], $data);
        
        Notification::make()
            ->success()
            ->title('Kebijakan Berhasil Diperbarui')
            ->send();
    }
}
