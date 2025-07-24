<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
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
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Booking';

    protected static ?string $modelLabel = 'Booking';

    protected static ?string $pluralModelLabel = 'Booking';

    protected static ?string $navigationGroup = 'Manajemen Booking';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationBadgeTooltip = 'Total booking hari ini';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::today()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Booking')
                    ->schema([
                        Forms\Components\TextInput::make('booking_code')
                            ->label('Kode Booking')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn($context) => $context === 'edit'),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Customer')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('field_id')
                                    ->label('Lapangan')
                                    ->relationship('field', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $field = Field::find($state);
                                            if ($field) {
                                                $set('price_per_hour', $field->price_per_hour);
                                            }
                                        }
                                    }),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\DatePicker::make('booking_date')
                                    ->label('Tanggal Booking')
                                    ->required()
                                    ->minDate(now())
                                    ->native(false),

                                Forms\Components\TimePicker::make('start_time')
                                    ->label('Jam Mulai')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if ($state && $get('duration_hours')) {
                                            $startTime = \Carbon\Carbon::parse($state);
                                            $durationHours = (int) $get('duration_hours'); // Convert to integer
                                            $endTime = $startTime->copy()->addHours($durationHours);
                                            $set('end_time', $endTime->format('H:i'));

                                            // Calculate total price
                                            $fieldId = $get('field_id');
                                            if ($fieldId) {
                                                $field = Field::find($fieldId);
                                                $totalPrice = $field->price_per_hour * $durationHours;
                                                $set('total_price', $totalPrice);
                                            }
                                        }
                                    }),

                                Forms\Components\TimePicker::make('end_time')
                                    ->label('Jam Selesai')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated(), // This ensures the value is included in form data even when disabled
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('duration_hours')
                                    ->label('Durasi (Jam)')
                                    ->options([
                                        1 => '1 Jam',
                                        2 => '2 Jam',
                                        3 => '3 Jam',
                                        4 => '4 Jam',
                                        5 => '5 Jam',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if ($state && $get('start_time')) {
                                            $startTime = \Carbon\Carbon::parse($get('start_time'));
                                            $durationHours = (int) $state; // Convert to integer
                                            $endTime = $startTime->copy()->addHours($durationHours);
                                            $set('end_time', $endTime->format('H:i'));

                                            // Calculate total price
                                            $fieldId = $get('field_id');
                                            if ($fieldId) {
                                                $field = Field::find($fieldId);
                                                $totalPrice = $field->price_per_hour * $durationHours;
                                                $set('total_price', $totalPrice);
                                            }
                                        }
                                    }),

                                Forms\Components\TextInput::make('total_price')
                                    ->label('Total Harga')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ]),
                    ]),

                Section::make('Informasi Customer')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('customer_name')
                                    ->label('Nama Customer')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('customer_phone')
                                    ->label('No. Telepon')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email Customer')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Customer')
                            ->rows(2)
                            ->placeholder('Catatan khusus dari customer...'),
                    ]),

                Section::make('Status & Konfirmasi')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Booking')
                            ->options([
                                'pending' => 'Menunggu Konfirmasi',
                                'confirmed' => 'Dikonfirmasi',
                                'cancelled' => 'Dibatalkan',
                                'completed' => 'Selesai',
                            ])
                            ->required()
                            ->default('pending')
                            ->reactive(),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->rows(2)
                            ->placeholder('Catatan internal admin...'),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('confirmed_at')
                                    ->label('Dikonfirmasi Pada')
                                    ->disabled()
                                    ->visible(fn($get) => $get('status') === 'confirmed'),

                                Forms\Components\DateTimePicker::make('cancelled_at')
                                    ->label('Dibatalkan Pada')
                                    ->disabled()
                                    ->visible(fn($get) => $get('status') === 'cancelled'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->weight('semibold')
                    ->copyable()
                    ->copyMessage('Kode booking disalin!')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn(Booking $record): string => $record->customer_phone),

                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('booking_time')
                    ->label('Waktu')
                    ->getStateUsing(
                        fn(Booking $record): string =>
                        \Carbon\Carbon::parse($record->start_time)->format('H:i') . ' - ' .
                        \Carbon\Carbon::parse($record->end_time)->format('H:i')
                    ),

                Tables\Columns\TextColumn::make('duration_hours')
                    ->label('Durasi')
                    ->suffix(' jam')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                        'primary' => 'completed',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'cancelled' => 'Dibatalkan',
                        'completed' => 'Selesai',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Account')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('confirmed_at')
                    ->label('Dikonfirmasi')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Konfirmasi',
                        'confirmed' => 'Dikonfirmasi',
                        'cancelled' => 'Dibatalkan',
                        'completed' => 'Selesai',
                    ]),

                SelectFilter::make('field_id')
                    ->label('Lapangan')
                    ->relationship('field', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('booking_date')
                    ->form([
                        DatePicker::make('date_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('date_to')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date),
                            );
                    }),

                Tables\Filters\Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn(Builder $query): Builder => $query->whereDate('booking_date', today())),

                Tables\Filters\Filter::make('upcoming')
                    ->label('Akan Datang')
                    ->query(fn(Builder $query): Builder => $query->where('booking_date', '>=', today())),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('confirm')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn(Booking $record): bool => $record->canBeConfirmed())
                        ->requiresConfirmation()
                        ->action(function (Booking $record) {
                            $record->confirm(auth()->id());
                            Notification::make()
                                ->title('Booking berhasil dikonfirmasi')
                                ->success()
                                ->send();
                        }),

                    Action::make('cancel')
                        ->label('Batalkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn(Booking $record): bool => $record->canBeCancelled())
                        ->requiresConfirmation()
                        ->action(function (Booking $record) {
                            $record->cancel();
                            Notification::make()
                                ->title('Booking berhasil dibatalkan')
                                ->success()
                                ->send();
                        }),

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
            RelationManagers\PaymentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
            'view' => Pages\ViewBooking::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Admin can only see bookings for their fields
        if (auth()->user()->isAdmin()) {
            $query->whereHas('field', function ($q) {
                $q->where('admin_id', auth()->id());
            });
        }

        return $query;
    }
}
