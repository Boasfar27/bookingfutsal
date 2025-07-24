<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use App\Models\Booking;
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
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Payments';

    protected static ?string $modelLabel = 'Payment';

    protected static ?string $pluralModelLabel = 'Payments';

    protected static ?string $navigationGroup = 'Manajemen Booking';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationBadgeTooltip = 'Payments pending verification';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Booking Information')
                    ->schema([
                        Forms\Components\Select::make('booking_id')
                            ->label('Booking')
                            ->relationship('booking', 'booking_code')
                            ->getOptionLabelFromRecordUsing(
                                fn(Booking $record): string =>
                                "{$record->booking_code} - {$record->customer_name} ({$record->field->name})"
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $booking = Booking::find($state);
                                    if ($booking) {
                                        $set('amount', $booking->total_price);
                                    }
                                }
                            }),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('amount')
                                    ->label('Amount')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\Select::make('payment_method')
                                    ->label('Payment Method')
                                    ->options([
                                        'bank_transfer' => 'Bank Transfer',
                                        'cash' => 'Cash',
                                        'ewallet' => 'E-Wallet',
                                    ])
                                    ->required()
                                    ->default('bank_transfer'),
                            ]),
                    ]),

                Section::make('Payment Proof')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('bank_name')
                                    ->label('Bank Name')
                                    ->placeholder('e.g., BCA, Mandiri, BNI')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('account_holder_name')
                                    ->label('Account Holder Name')
                                    ->placeholder('Name on bank account')
                                    ->maxLength(255),
                            ]),

                        Forms\Components\FileUpload::make('proof_file_path')
                            ->label('Payment Proof')
                            ->directory('payment-proofs')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'])
                            ->maxSize(5120) // 5MB
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->helperText('Upload payment proof (max 5MB). Accepted: JPG, PNG, PDF')
                            ->downloadable()
                            ->previewable(),

                        Forms\Components\Textarea::make('payment_notes')
                            ->label('Payment Notes')
                            ->placeholder('Additional notes about the payment...')
                            ->rows(3),

                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Payment Date & Time')
                            ->default(now())
                            ->required(),
                    ]),

                Section::make('Verification Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'Pending Verification',
                                        'verified' => 'Verified',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->required()
                                    ->default('pending')
                                    ->reactive(),

                                Forms\Components\Select::make('verified_by')
                                    ->label('Verified By')
                                    ->relationship('verifiedBy', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn($get) => in_array($get('status'), ['verified', 'rejected'])),
                            ]),

                        Forms\Components\Textarea::make('verification_notes')
                            ->label('Admin Verification Notes')
                            ->placeholder('Admin notes about verification...')
                            ->rows(3)
                            ->visible(fn($get) => in_array($get('status'), ['verified', 'rejected'])),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('verified_at')
                                    ->label('Verified At')
                                    ->disabled()
                                    ->visible(fn($get) => $get('status') === 'verified'),

                                Forms\Components\DateTimePicker::make('rejected_at')
                                    ->label('Rejected At')
                                    ->disabled()
                                    ->visible(fn($get) => $get('status') === 'rejected'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.booking_code')
                    ->label('Booking Code')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->copyable()
                    ->copyMessage('Booking code copied!')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('booking.customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn(Payment $record): string => $record->booking->customer_phone),

                Tables\Columns\TextColumn::make('booking.field.name')
                    ->label('Field')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label('Method')
                    ->colors([
                        'primary' => 'bank_transfer',
                        'success' => 'cash',
                        'warning' => 'ewallet',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'bank_transfer' => 'Bank Transfer',
                        'cash' => 'Cash',
                        'ewallet' => 'E-Wallet',
                        default => ucfirst($state),
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'verified',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                        default => ucfirst($state),
                    }),

                Tables\Columns\ImageColumn::make('proof_file_path')
                    ->label('Proof')
                    ->circular()
                    ->defaultImageUrl('/images/no-image.png')
                    ->size(40),

                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Bank')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Payment Date')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('verified_at')
                    ->label('Verified At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('verifiedBy.name')
                    ->label('Verified By')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending Verification',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ]),

                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'bank_transfer' => 'Bank Transfer',
                        'cash' => 'Cash',
                        'ewallet' => 'E-Wallet',
                    ]),

                SelectFilter::make('booking.field_id')
                    ->label('Field')
                    ->relationship('booking.field', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('pending_only')
                    ->label('Pending Only')
                    ->query(fn(Builder $query): Builder => $query->where('status', 'pending')),

                Tables\Filters\Filter::make('today')
                    ->label('Today\'s Payments')
                    ->query(fn(Builder $query): Builder => $query->whereDate('paid_at', today())),

                Tables\Filters\Filter::make('has_proof')
                    ->label('Has Payment Proof')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('proof_file_path')),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('verify')
                        ->label('Verify Payment')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn(Payment $record): bool => $record->canBeVerified())
                        ->form([
                            Forms\Components\Textarea::make('verification_notes')
                                ->label('Verification Notes')
                                ->placeholder('Add notes about verification...')
                                ->rows(3),
                        ])
                        ->action(function (Payment $record, array $data) {
                            $record->verify(auth()->id(), $data['verification_notes'] ?? null);
                            Notification::make()
                                ->title('Payment verified successfully')
                                ->body('Booking has been automatically confirmed.')
                                ->success()
                                ->send();
                        }),

                    Action::make('reject')
                        ->label('Reject Payment')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn(Payment $record): bool => $record->canBeRejected())
                        ->form([
                            Forms\Components\Textarea::make('verification_notes')
                                ->label('Rejection Reason')
                                ->placeholder('Explain why payment is rejected...')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (Payment $record, array $data) {
                            $record->reject(auth()->id(), $data['verification_notes']);
                            Notification::make()
                                ->title('Payment rejected')
                                ->body('Customer will be notified about rejection.')
                                ->warning()
                                ->send();
                        }),

                    Action::make('view_proof')
                        ->label('View Proof')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->visible(fn(Payment $record): bool => !empty($record->proof_file_path))
                        ->url(fn(Payment $record): string => Storage::url($record->proof_file_path))
                        ->openUrlInNewTab(),

                    Action::make('download_proof')
                        ->label('Download Proof')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->visible(fn(Payment $record): bool => !empty($record->proof_file_path))
                        ->action(function (Payment $record) {
                            return response()->download(
                                Storage::path($record->proof_file_path),
                                $record->proof_file_name ?? 'payment-proof.jpg'
                            );
                        }),

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('bulk_verify')
                        ->label('Verify Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $verifiedCount = 0;
                            foreach ($records as $record) {
                                if ($record->canBeVerified()) {
                                    $record->verify(auth()->id(), 'Bulk verification');
                                    $verifiedCount++;
                                }
                            }
                            Notification::make()
                                ->title("{$verifiedCount} payments verified successfully")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('bulk_reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->label('Rejection Reason')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function ($records, array $data) {
                            $rejectedCount = 0;
                            foreach ($records as $record) {
                                if ($record->canBeRejected()) {
                                    $record->reject(auth()->id(), $data['rejection_reason']);
                                    $rejectedCount++;
                                }
                            }
                            Notification::make()
                                ->title("{$rejectedCount} payments rejected")
                                ->warning()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Admin can only see payments for their fields
        if (auth()->user()->isAdmin()) {
            $query->whereHas('booking.field', function ($q) {
                $q->where('admin_id', auth()->id());
            });
        }

        return $query;
    }
}
