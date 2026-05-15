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
        $this->data['name'] = $user->name;
        $this->data['email'] = $user->email;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                \Filament\Schemas\Components\Section::make('Ganti Nama')
                    ->description('Perbarui nama tampilan akun Anda.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        \Filament\Schemas\Components\Actions::make([
                            Action::make('updateName')
                                ->label('Perbarui Nama')
                                ->color('primary')
                                ->action(fn () => $this->updateName()),
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
            ]);
    }

    public function updateName(): void
    {
        $user = auth()->user();
        $user->name = $this->data['name'];
        $user->save();

        Notification::make()
            ->success()
            ->title('Nama Berhasil Diperbarui')
            ->send();
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
}
