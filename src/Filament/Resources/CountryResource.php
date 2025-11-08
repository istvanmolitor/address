<?php

namespace Molitor\Address\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Molitor\Address\Filament\Resources\CountryResource\Pages;
use Molitor\Address\Models\Country;
use Molitor\Language\Filament\Components\TranslatableFields;
use Molitor\Language\Repositories\LanguageRepositoryInterface;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static \BackedEnum|null|string $navigationIcon = 'heroicon-o-flag';

    public static function getNavigationGroup(): string
    {
        return __('address::common.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('address::country.title');
    }

    public static function canAccess(): bool
    {
        return Gate::allows('acl', 'country');
    }

    public static function form(Schema $schema): Schema
    {
        /** @var LanguageRepositoryInterface $languageRepository */

        return $schema
            ->components([
                Forms\Components\TextInput::make('code')
                    ->label(__('address::country.form.code'))
                    ->required()
                    ->maxLength(5)
                    ->unique(ignoreRecord: true),
                TranslatableFields::schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('address::country.form.name'))
                        ->required()
                        ->maxLength(255),
                ])->columns(2),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('address::country.table.code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('translation.name')
                    ->label(__('address::country.table.name'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
