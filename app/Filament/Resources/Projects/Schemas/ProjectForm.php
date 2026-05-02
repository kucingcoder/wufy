<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Proyek')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (\Filament\Schemas\Components\Utilities\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug / URL')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->dehydrated(),
                FileUpload::make('thumbnail')
                    ->label('Thumbnail Proyek')
                    ->image()
                    ->formatStateUsing(fn ($state) => (array) $state)
                    ->disk('public')
                    ->visibility('public')
                    ->columnSpanFull(),
                Grid::make(2)
                    ->schema([
                        Select::make('month')
                            ->label('Bulan')
                            ->options([
                                'Januari' => 'Januari',
                                'Februari' => 'Februari',
                                'Maret' => 'Maret',
                                'April' => 'April',
                                'Mei' => 'Mei',
                                'Juni' => 'Juni',
                                'Juli' => 'Juli',
                                'Agustus' => 'Agustus',
                                'September' => 'September',
                                'Oktober' => 'Oktober',
                                'November' => 'November',
                                'Desember' => 'Desember',
                            ]),
                        TextInput::make('year')
                            ->label('Tahun')
                            ->numeric()
                            ->length(4),
                        Toggle::make('is_opensource')
                            ->label('Open Source?')
                            ->live()
                            ->inline(false),
                        TextInput::make('github_link')
                            ->label('GitHub Repository Link')
                            ->url()
                            ->required(fn (callable $get) => $get('is_opensource'))
                            ->visible(fn (callable $get) => $get('is_opensource'))
                            ->placeholder('https://github.com/username/repo')
                            ->prefix('github.com/')
                            ->columnSpanFull(),
                    ]),
                TextInput::make('link')
                    ->label('Link Website / Lainnya (Opsional)')
                    ->url()
                    ->placeholder('https://example.com')
                    ->columnSpanFull(),
                Textarea::make('short_description')
                    ->label('Deskripsi Singkat')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                RichEditor::make('blog_content')
                    ->label('Konten Blog / Detail Proyek')
                    ->columnSpanFull(),
                Repeater::make('galleries')
                    ->label('Galeri Foto Proyek')
                    ->relationship('galleries')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Foto')
                            ->image()
                            ->formatStateUsing(fn ($state) => (array) $state)
                            ->disk('public')
                            ->visibility('public')
                            ->required(),
                        TextInput::make('title')
                            ->label('Keterangan / Judul Foto')
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Foto'),
            ]);
    }
}
