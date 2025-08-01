<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FieldResource\Pages;
use App\Filament\Resources\FieldResource\RelationManagers;
use App\Models\Field;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ActionGroup;

class FieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Lapangan';

    protected static ?string $modelLabel = 'Lapangan';

    protected static ?string $pluralModelLabel = 'Lapangan';

    protected static ?string $navigationGroup = 'Manajemen Lapangan';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lapangan')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Lapangan Futsal Arena A'),

                                Forms\Components\Select::make('type')
                                    ->label('Jenis Lapangan')
                                    ->options([
                                        'indoor' => 'Indoor',
                                        'outdoor' => 'Outdoor',
                                    ])
                                    ->required()
                                    ->native(false),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->placeholder('Deskripsi detail tentang lapangan futsal...'),

                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->placeholder('Alamat lengkap lapangan')
                            ->columnSpanFull(),
                    ]),

                Section::make('Harga & Operasional')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('price_per_hour')
                                    ->label('Harga per Jam')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('150000'),

                                Forms\Components\TextInput::make('max_hours_per_booking')
                                    ->label('Maksimal Jam per Booking')
                                    ->required()
                                    ->numeric()
                                    ->default(3)
                                    ->minValue(1)
                                    ->maxValue(8),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('open_time')
                                    ->label('Jam Buka')
                                    ->required()
                                    ->default('06:00'),

                                Forms\Components\TimePicker::make('close_time')
                                    ->label('Jam Tutup')
                                    ->required()
                                    ->default('23:00'),
                            ]),

                        Forms\Components\CheckboxList::make('operating_days')
                            ->label('Hari Operasional')
                            ->options([
                                1 => 'Senin',
                                2 => 'Selasa',
                                3 => 'Rabu',
                                4 => 'Kamis',
                                5 => 'Jumat',
                                6 => 'Sabtu',
                                7 => 'Minggu',
                            ])
                            ->default([1, 2, 3, 4, 5, 6, 7])
                            ->columns(4),
                    ]),

                Section::make('Fasilitas & Media')
                    ->schema([
                        Forms\Components\TagsInput::make('facilities')
                            ->label('Fasilitas')
                            ->placeholder('AC, Rumput Sintetis, Toilet, dll.')
                            ->helperText('Tekan Enter setelah mengetik setiap fasilitas'),

                        Forms\Components\FileUpload::make('images')
                            ->label('Foto Lapangan')
                            ->multiple()
                            ->image()
                            ->maxFiles(5)
                            ->directory('fields')
                            ->helperText('Upload maksimal 5 foto lapangan'),
                    ]),

                Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\Textarea::make('payment_info')
                            ->label('Info Rekening')
                            ->rows(3)
                            ->placeholder('Bank BCA: 1234567890 a.n. Nama Pemilik')
                            ->helperText('Informasi rekening untuk transfer pembayaran'),
                    ]),

                Section::make('Pengelola')
                    ->schema([
                        Forms\Components\Select::make('admin_id')
                            ->label('Admin Pengelola')
                            ->relationship('admin', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Admin yang mengelola lapangan ini')
                            ->visible(fn() => auth()->user()->isSuperAdmin()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Gambar')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lapangan')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis')
                    ->colors([
                        'primary' => 'indoor',
                        'success' => 'outdoor',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'indoor' => 'Indoor',
                        'outdoor' => 'Outdoor',
                    }),

                Tables\Columns\TextColumn::make('price_per_hour')
                    ->label('Harga/Jam')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Pengelola')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis Lapangan')
                    ->options([
                        'indoor' => 'Indoor',
                        'outdoor' => 'Outdoor',
                    ]),

                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Tidak Aktif',
                    ]),

                SelectFilter::make('admin_id')
                    ->label('Pengelola')
                    ->relationship('admin', 'name')
                    ->searchable()
                    ->visible(fn() => auth()->user()->isSuperAdmin()),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BookingsRelationManager::class,
            RelationManagers\SchedulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFields::route('/'),
            'create' => Pages\CreateField::route('/create'),
            'edit' => Pages\EditField::route('/{record}/edit'),
            'view' => Pages\ViewField::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Admin can only see their own fields
        if (auth()->user()->isAdmin()) {
            $query->where('admin_id', auth()->id());
        }

        return $query;
    }
}
