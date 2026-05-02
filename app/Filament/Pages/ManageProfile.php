<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class ManageProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user';
    protected static ?string $title = 'Data Pribadi';
    protected static ?string $navigationLabel = 'Data Pribadi';
    protected static ?int $navigationSort = 1;

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
                \Filament\Schemas\Components\Section::make('Informasi Profil')
                    ->description('Kelola informasi dasar portofolio pribadi Anda.')
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Foto Profil')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->disk('public')
                            ->visibility('public')
                            ->columnSpanFull(),
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('job_title')
                            ->label('Pekerjaan / Jabatan')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('city')
                            ->label('Kota')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('province')
                            ->label('Provinsi')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Nomor Telepon/WhatsApp')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Ceritakan sedikit tentang diri Anda...')
                            ->rows(5)
                            ->columnSpanFull(),
                        Repeater::make('links')
                            ->label('Daftar Tautan (Sosial Media / Web)')
                            ->schema([
                                TextInput::make('logo')->label('Logo (Ikon atau URL)'),
                                TextInput::make('title')->label('Judul Tautan')->required(),
                                TextInput::make('link')->label('URL / Link')->url()->required(),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                        FileUpload::make('cv_path')
                            ->label('Unggah CV (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->action(fn () => $this->save()),
        ];
    }

    public function save(): void
    {
        $data = $this->getSchema('content')->getState();

        Profile::updateOrCreate(['id' => 1], $data);
        
        Notification::make()
            ->success()
            ->title('Profil Berhasil Diperbarui')
            ->send();
    }
}
