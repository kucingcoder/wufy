<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use BackedEnum;
use Illuminate\Support\Facades\File;
use ZipArchive;

class Settings extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $title = 'Pengaturan Akun';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?int $navigationSort = 10;

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        $profile = \App\Models\Profile::first();

        $this->data['email'] = $user->email;
        if ($profile) {
            $this->data['hide_services'] = (bool) $profile->hide_services;
            $this->data['hide_hobbies'] = (bool) $profile->hide_hobbies;
            $this->data['hide_languages'] = (bool) $profile->hide_languages;
        }
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                \Filament\Schemas\Components\Section::make('Tampilan Portofolio')
                    ->description('Atur bagian mana saja yang ingin disembunyikan dari halaman depan (default: mati/tidak disembunyikan).')
                    ->schema([
                        \Filament\Forms\Components\Toggle::make('hide_services')
                            ->label('Sembunyikan Jasa'),
                        \Filament\Forms\Components\Toggle::make('hide_hobbies')
                            ->label('Sembunyikan Minat & Hobi'),
                        \Filament\Forms\Components\Toggle::make('hide_languages')
                            ->label('Sembunyikan Bahasa'),
                        \Filament\Schemas\Components\Actions::make([
                            Action::make('updateDisplaySettings')
                                ->label('Simpan Pengaturan Tampilan')
                                ->color('primary')
                                ->action(fn () => $this->updateDisplaySettings()),
                        ]),
                    ]),
                \Filament\Schemas\Components\Section::make('Ganti Email')
                    ->description('Perbarui alamat email yang digunakan untuk login.')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email Baru')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', auth()->user()),
                        \Filament\Schemas\Components\Actions::make([
                            Action::make('updateEmail')
                                ->label('Perbarui Email')
                                ->color('primary')
                                ->action(fn () => $this->updateEmail()),
                        ]),
                    ]),
                \Filament\Schemas\Components\Section::make('Ganti Password')
                    ->description('Pastikan password Anda kuat dan unik.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->required()
                            ->currentPassword(),
                        TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->required()
                            ->rule(Password::default()),
                        TextInput::make('new_password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->required()
                            ->same('new_password'),
                        \Filament\Schemas\Components\Actions::make([
                            Action::make('updatePassword')
                                ->label('Perbarui Password')
                                ->color('primary')
                                ->action(fn () => $this->updatePassword()),
                        ]),
                    ]),
                \Filament\Schemas\Components\Section::make('Backup & Restore')
                    ->description('Backup database dan gambar (storage/app/public), atau restore dari file backup (.zip).')
                    ->schema([
                        \Filament\Schemas\Components\Actions::make([
                            Action::make('backupData')
                                ->label('Download Backup')
                                ->color('success')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->action(fn () => $this->backupData()),

                            Action::make('restoreData')
                                ->label('Restore Data')
                                ->color('danger')
                                ->icon('heroicon-o-arrow-up-tray')
                                ->requiresConfirmation()
                                ->modalHeading('Restore Data')
                                ->modalDescription('Peringatan: Data saat ini akan ditimpa dengan data dari file backup. Pastikan Anda memiliki backup terbaru.')
                                ->form([
                                    \Filament\Forms\Components\FileUpload::make('backup_file')
                                        ->label('File Backup (.zip)')
                                        ->disk('local')
                                        ->directory('temp-restores')
                                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                                        ->required(),
                                ])
                                ->action(fn (array $data) => $this->restoreData($data)),
                        ]),
                    ]),
            ]);
    }

    public function updateDisplaySettings(): void
    {
        $profile = \App\Models\Profile::first();
        if ($profile) {
            $profile->hide_services = $this->data['hide_services'] ?? false;
            $profile->hide_hobbies = $this->data['hide_hobbies'] ?? false;
            $profile->hide_languages = $this->data['hide_languages'] ?? false;
            $profile->save();
            
            Notification::make()
                ->success()
                ->title('Pengaturan Tampilan Berhasil Disimpan')
                ->send();
        }
    }

    public function updateEmail(): void
    {
        $user = auth()->user();
        $user->email = $this->data['email'];
        $user->save();

        Notification::make()
            ->success()
            ->title('Email Berhasil Diperbarui')
            ->send();
    }

    public function updatePassword(): void
    {
        $user = auth()->user();
        
        if ($this->data['new_password'] ?? false) {
            $user->password = Hash::make($this->data['new_password']);
            $user->save();

            Notification::make()
                ->success()
                ->title('Password Berhasil Diperbarui')
                ->send();
                
            $this->data['current_password'] = null;
            $this->data['new_password'] = null;
            $this->data['new_password_confirmation'] = null;
        }
    }

    public function backupData()
    {
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');
        $dbName = env('DB_DATABASE', 'wufy');

        $timestamp = now()->format('Y-m-d-H-i-s');
        $tempDir = storage_path('app/temp-backup-' . $timestamp);
        File::makeDirectory($tempDir, 0755, true);

        // 1. Dump database
        $dbDumpPath = $tempDir . '/database.sql';
        $passwordArg = $dbPass ? "-p\"{$dbPass}\"" : "";
        $command = "mysqldump -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passwordArg} {$dbName} > {$dbDumpPath}";
        exec($command);

        // 2. Zip everything
        $zipPath = storage_path('app/backup-' . $timestamp . '.zip');
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Add database
            if (File::exists($dbDumpPath)) {
                $zip->addFile($dbDumpPath, 'database.sql');
            }

            // Add public storage (images)
            $publicPath = storage_path('app/public');
            if (File::exists($publicPath)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($publicPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                    if (! $file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = 'public/' . str_replace('\\', '/', substr($filePath, strlen($publicPath) + 1));
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            }
            
            $zip->close();
        }

        // Cleanup temp dir
        File::deleteDirectory($tempDir);

        if (File::exists($zipPath)) {
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        Notification::make()
            ->danger()
            ->title('Gagal membuat backup')
            ->send();
    }

    public function restoreData(array $data): void
    {
        $filePath = storage_path('app/' . $data['backup_file']);
        $tempExtractDir = storage_path('app/temp-restore-' . now()->timestamp);
        
        if (!File::exists($filePath)) {
            Notification::make()
                ->danger()
                ->title('File backup tidak ditemukan')
                ->send();
            return;
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) === true) {
            $zip->extractTo($tempExtractDir);
            $zip->close();
            
            // 1. Restore Database
            $dbDumpPath = $tempExtractDir . '/database.sql';
            if (File::exists($dbDumpPath)) {
                $dbHost = env('DB_HOST', '127.0.0.1');
                $dbPort = env('DB_PORT', '3306');
                $dbUser = env('DB_USERNAME', 'root');
                $dbPass = env('DB_PASSWORD', '');
                $dbName = env('DB_DATABASE', 'wufy');
                
                $passwordArg = $dbPass ? "-p\"{$dbPass}\"" : "";
                $command = "mysql -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passwordArg} {$dbName} < {$dbDumpPath}";
                exec($command);
            }
            
            // 2. Restore Images (public folder)
            $extractedPublicPath = $tempExtractDir . '/public';
            if (File::exists($extractedPublicPath)) {
                $targetPublicPath = storage_path('app/public');
                File::copyDirectory($extractedPublicPath, $targetPublicPath);
            }
            
            // Cleanup
            File::deleteDirectory($tempExtractDir);
            File::delete($filePath);
            
            Notification::make()
                ->success()
                ->title('Restore Berhasil')
                ->body('Database dan gambar telah dipulihkan.')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Gagal membaca file backup')
                ->send();
        }
    }
}
