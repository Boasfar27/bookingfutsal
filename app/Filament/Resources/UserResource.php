<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
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
use Filament\Tables\Actions\Action;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    protected static ?string $navigationGroup = 'Manajemen User';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationBadgeTooltip = 'Total users registered';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi User')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukan nama lengkap'),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Masukan email'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->minLength(6)
                                    ->dehydrated(fn($state) => filled($state))
                                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                    ->placeholder('Masukan password'),

                                Forms\Components\DateTimePicker::make('email_verified_at')
                                    ->label('Email Terverifikasi')
                                    ->nullable(),
                            ]),
                    ]),

                Section::make('Role & Hak Akses')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->label('Role User')
                            ->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->multiple()
                            ->helperText('Silahkan pilih role untuk setiap user')
                            ->placeholder('Pilih role(s)'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('User Aktif')
                            ->default(true)
                            ->helperText('User yang tidak aktif tidak dapat mengakses sistem'),
                    ])
                    ->visible(fn() => auth()->user()->isSuperAdmin()),

                Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->placeholder('Catatan internal untuk superadmin dan admin')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email berhasil disalin!')
                    ->copyMessageDuration(1500),

                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('Role')
                    ->colors([
                        'danger' => 'superadmin',
                        'warning' => 'admin',
                        'success' => 'user',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'superadmin' => 'Super Admin',
                        'admin' => 'Admin',
                        'user' => 'User',
                        default => ucfirst($state),
                    }),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Terverifikasi')
                    ->boolean()
                    ->getStateUsing(fn(User $record): bool => !is_null($record->email_verified_at))
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('success')
                    ->falseColor('warning'),

                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('Pemesanan')
                    ->counts('bookings')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ownedFields_count')
                    ->label('Lapangan milik user')
                    ->counts('ownedFields')
                    ->alignCenter()
                    ->sortable()
                    ->visible(fn() => auth()->user()->isSuperAdmin()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar pada tanggal')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Update pada tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),

                SelectFilter::make('email_verified_at')
                    ->label('Email Status')
                    ->options([
                        'verified' => 'Verified',
                        'unverified' => 'Unverified',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'] === 'verified',
                                fn(Builder $query): Builder => $query->whereNotNull('email_verified_at'),
                            )
                            ->when(
                                $data['value'] === 'unverified',
                                fn(Builder $query): Builder => $query->whereNull('email_verified_at'),
                            );
                    }),

                Tables\Filters\Filter::make('has_bookings')
                    ->label('Has Bookings')
                    ->query(fn(Builder $query): Builder => $query->has('bookings')),

                Tables\Filters\Filter::make('recent_users')
                    ->label('Recent Users (30 days)')
                    ->query(fn(Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30))),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('verify_email')
                        ->label('Verifikasi Email')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn(User $record): bool => is_null($record->email_verified_at))
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            $record->update(['email_verified_at' => now()]);
                            Notification::make()
                                ->title('Email berhasil terverifikasi')
                                ->success()
                                ->send();
                        }),

                    Action::make('unverify_email')
                        ->label('Batalkan Verifikasi Email')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->visible(fn(User $record): bool => !is_null($record->email_verified_at))
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            $record->update(['email_verified_at' => null]);
                            Notification::make()
                                ->title('Email berhasil dibatalkan verifikasinya')
                                ->warning()
                                ->send();
                        }),

                    Action::make('reset_password')
                        ->label('Ubah Kata Sandi')
                        ->icon('heroicon-o-key')
                        ->color('warning')
                        ->form([
                            Forms\Components\TextInput::make('new_password')
                                ->label('Kata Sandi Baru')
                                ->password()
                                ->required()
                                ->minLength(6),
                        ])
                        ->action(function (User $record, array $data) {
                            $record->update(['password' => Hash::make($data['new_password'])]);
                            Notification::make()
                                ->title('Kata Sandi Berhasil Diubah')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn(User $record) => $record->id !== auth()->id()), // Can't delete yourself
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            // Prevent deleting current user
                            $records = $records->reject(fn($record) => $record->id === auth()->id());
                            $records->each->delete();
                        }),

                    Tables\Actions\BulkAction::make('verify_emails')
                        ->label('Verifikasi Email')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(fn(User $record) => $record->update(['email_verified_at' => now()]));
                            Notification::make()
                                ->title('Email Berhasil Diverifikasi')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BookingsRelationManager::class,
            RelationManagers\OwnedFieldsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Only Superadmin can access UserResource
        if (!auth()->user()->isSuperAdmin()) {
            return $query->whereRaw('1 = 0'); // Return empty results
        }

        return $query;
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isSuperAdmin();
    }
}
