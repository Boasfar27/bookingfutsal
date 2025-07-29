<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
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
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Schedules';

    protected static ?string $modelLabel = 'Schedule';

    protected static ?string $pluralModelLabel = 'Schedules';

    protected static ?string $navigationGroup = 'Manajemen Lapangan';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationBadgeTooltip = 'Scheduled maintenance & events';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('date', today())->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Schedule Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('field_id')
                                    ->label('Nama Lapangan')
                                    ->relationship('field', 'name')
                                    ->getOptionLabelFromRecordUsing(
                                        fn(Field $record): string =>
                                        "{$record->name} - {$record->location}"
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->visible(fn() => auth()->user()->isSuperAdmin())
                                    ->default(fn() => auth()->user()->isAdmin() ? auth()->user()->ownedFields->first()?->id : null),

                                Forms\Components\Hidden::make('field_id')
                                    ->default(fn() => auth()->user()->ownedFields->first()?->id)
                                    ->visible(fn() => auth()->user()->isAdmin()),

                                Forms\Components\Select::make('type')
                                    ->label('Schedule Type')
                                    ->options([
                                        'maintenance' => 'Maintenance',
                                        'blocked' => 'Blocked/Unavailable',
                                        'event' => 'Special Event',
                                        'cleaning' => 'Cleaning',
                                        'renovation' => 'Renovation',
                                        'private' => 'Private Use',
                                    ])
                                    ->required()
                                    ->default('maintenance')
                                    ->reactive(),
                            ]),

                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->placeholder('e.g., Weekly maintenance, Tournament preparation')
                            ->maxLength(255)
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Additional details about this schedule...')
                            ->rows(3),
                    ]),

                Section::make('Date & Time')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\DatePicker::make('date')
                                    ->label('Date')
                                    ->required()
                                    ->default(today())
                                    ->minDate(today()),

                                Forms\Components\TimePicker::make('start_time')
                                    ->label('Start Time')
                                    ->required()
                                    ->default('08:00')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('end_time')) {
                                            // Validate start time is before end time
                                            if (Carbon::parse($state) >= Carbon::parse($get('end_time'))) {
                                                $set('end_time', Carbon::parse($state)->addHour()->format('H:i'));
                                            }
                                        }
                                    }),

                                Forms\Components\TimePicker::make('end_time')
                                    ->label('End Time')
                                    ->required()
                                    ->default('09:00')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('start_time')) {
                                            // Validate end time is after start time
                                            if (Carbon::parse($state) <= Carbon::parse($get('start_time'))) {
                                                $set('start_time', Carbon::parse($state)->subHour()->format('H:i'));
                                            }
                                        }
                                    }),
                            ]),
                    ]),

                Section::make('Recurring Schedule')
                    ->schema([
                        Forms\Components\Toggle::make('is_recurring')
                            ->label('Recurring Schedule')
                            ->helperText('Enable if this schedule should repeat')
                            ->reactive(),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('recurring_type')
                                    ->label('Recurring Type')
                                    ->options([
                                        'daily' => 'Daily',
                                        'weekly' => 'Weekly',
                                        'monthly' => 'Monthly',
                                    ])
                                    ->visible(fn($get) => $get('is_recurring'))
                                    ->reactive(),

                                Forms\Components\DatePicker::make('recurring_end_date')
                                    ->label('End Date')
                                    ->helperText('When should this recurring schedule stop?')
                                    ->visible(fn($get) => $get('is_recurring'))
                                    ->minDate(fn($get) => $get('date') ?: today()),
                            ]),

                        Forms\Components\CheckboxList::make('recurring_days')
                            ->label('Days of Week')
                            ->options([
                                '1' => 'Monday',
                                '2' => 'Tuesday',
                                '3' => 'Wednesday',
                                '4' => 'Thursday',
                                '5' => 'Friday',
                                '6' => 'Saturday',
                                '0' => 'Sunday',
                            ])
                            ->columns(4)
                            ->visible(fn($get) => $get('is_recurring') && $get('recurring_type') === 'weekly')
                            ->helperText('Select which days of the week this schedule applies to'),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Hidden::make('created_by')
                            ->default(auth()->id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('field.name')
                    ->label('Nama Lapangan')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Deskripsi')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (Schedule $record): ?string {
                        return $record->description ?: null;
                    }),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis ')
                    ->colors([
                        'warning' => 'maintenance',
                        'danger' => 'blocked',
                        'success' => 'event',
                        'info' => 'cleaning',
                        'secondary' => 'renovation',
                        'primary' => 'private',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'maintenance' => 'Maintenance',
                        'blocked' => 'Blocked',
                        'event' => 'Event',
                        'cleaning' => 'Cleaning',
                        'renovation' => 'Renovation',
                        'private' => 'Private',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('D, d M Y')
                    ->sortable()
                    ->color(fn(Schedule $record) => $record->date->isPast() ? 'gray' : 'primary'),

                Tables\Columns\TextColumn::make('time_range')
                    ->label('Waktu')
                    ->getStateUsing(
                        fn(Schedule $record): string =>
                        Carbon::parse($record->start_time)->format('H:i') . ' - ' .
                        Carbon::parse($record->end_time)->format('H:i')
                    ),

                Tables\Columns\IconColumn::make('is_recurring')
                    ->label('Berulang')
                    ->boolean()
                    ->tooltip(
                        fn(Schedule $record): ?string =>
                        $record->is_recurring ? "Recurring {$record->recurring_type}" : null
                    ),

                Tables\Columns\TextColumn::make('recurring_type')
                    ->label('Repeat')
                    ->formatStateUsing(
                        fn(?string $state): string =>
                        $state ? ucfirst($state) : '-'
                    )
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('recurring_end_date')
                    ->label('Ends')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Schedule Type')
                    ->options([
                        'maintenance' => 'Maintenance',
                        'blocked' => 'Blocked',
                        'event' => 'Event',
                        'cleaning' => 'Cleaning',
                        'renovation' => 'Renovation',
                        'private' => 'Private',
                    ]),

                SelectFilter::make('field_id')
                    ->label('Field')
                    ->relationship('field', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('is_recurring')
                    ->label('Recurring')
                    ->options([
                        true => 'Recurring',
                        false => 'One-time',
                    ]),

                Tables\Filters\Filter::make('today')
                    ->label('Today\'s Schedules')
                    ->query(fn(Builder $query): Builder => $query->whereDate('date', today())),

                Tables\Filters\Filter::make('this_week')
                    ->label('This Week')
                    ->query(fn(Builder $query): Builder => $query->whereBetween('date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ])),

                Tables\Filters\Filter::make('upcoming')
                    ->label('Upcoming')
                    ->query(fn(Builder $query): Builder => $query->whereDate('date', '>=', today())),

                Tables\Filters\Filter::make('past')
                    ->label('Past Schedules')
                    ->query(fn(Builder $query): Builder => $query->whereDate('date', '<', today())),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('generate_recurring')
                        ->label('Generate Recurring')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->visible(
                            fn(Schedule $record): bool =>
                            $record->is_recurring &&
                            $record->recurring_end_date &&
                            $record->recurring_end_date->isFuture()
                        )
                        ->action(function (Schedule $record) {
                            $generated = $record->generateRecurringSchedules();
                            Notification::make()
                                ->title('Recurring schedules generated')
                                ->body("Generated {$generated} recurring schedule(s)")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Generate Recurring Schedules')
                        ->modalDescription('This will create individual schedule entries for all recurring dates. Continue?'),

                    Action::make('duplicate')
                        ->label('Duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('gray')
                        ->action(function (Schedule $record) {
                            $newSchedule = $record->replicate();
                            $newSchedule->date = $record->date->addDay();
                            $newSchedule->is_recurring = false;
                            $newSchedule->recurring_type = null;
                            $newSchedule->recurring_end_date = null;
                            $newSchedule->recurring_days = null;
                            $newSchedule->created_by = auth()->id();
                            $newSchedule->save();

                            Notification::make()
                                ->title('Schedule duplicated')
                                ->body('Schedule duplicated for next day')
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

                    Tables\Actions\BulkAction::make('bulk_duplicate')
                        ->label('Duplicate Selected')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('gray')
                        ->action(function ($records) {
                            $duplicatedCount = 0;
                            foreach ($records as $record) {
                                $newSchedule = $record->replicate();
                                $newSchedule->date = $record->date->addDay();
                                $newSchedule->is_recurring = false;
                                $newSchedule->recurring_type = null;
                                $newSchedule->recurring_end_date = null;
                                $newSchedule->recurring_days = null;
                                $newSchedule->created_by = auth()->id();
                                $newSchedule->save();
                                $duplicatedCount++;
                            }
                            Notification::make()
                                ->title("{$duplicatedCount} schedules duplicated")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('bulk_change_type')
                        ->label('Change Type')
                        ->icon('heroicon-o-tag')
                        ->color('info')
                        ->form([
                            Forms\Components\Select::make('type')
                                ->label('New Schedule Type')
                                ->options([
                                    'maintenance' => 'Maintenance',
                                    'blocked' => 'Blocked',
                                    'event' => 'Event',
                                    'cleaning' => 'Cleaning',
                                    'renovation' => 'Renovation',
                                    'private' => 'Private',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            $updatedCount = 0;
                            foreach ($records as $record) {
                                $record->update(['type' => $data['type']]);
                                $updatedCount++;
                            }
                            Notification::make()
                                ->title("{$updatedCount} schedules updated")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('date', 'desc')
            ->recordUrl(null);
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
            'view' => Pages\ViewSchedule::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Admin can only see schedules for their fields
        if (auth()->user()->isAdmin()) {
            $query->whereHas('field', function ($q) {
                $q->where('admin_id', auth()->id());
            });
        }

        return $query;
    }
}
