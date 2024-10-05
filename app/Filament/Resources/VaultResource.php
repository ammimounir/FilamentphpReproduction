<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VaultResource\Pages;
use App\Filament\Resources\VaultResource\RelationManagers;
use App\Models\Vault;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// Used Inputs
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class VaultResource extends Resource
{
    protected static ?string $model = Vault::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Attachments')
                ->schema([
                    // ----- Inputs START----- //
                    Select::make('year')
                        ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'unique' => 'Le coffre-Fort des documents de cette année a déjà été enregistré.',
                        ])
                        ->options(array_combine(
                            range(date('Y'), date('Y') - 9), // Generates the last 10 years
                            range(date('Y'), date('Y') - 9)
                        ))
                        ->default(date('Y'))
                        // ->disabled()
                        ->required(),
                    Fileupload::make('attachments')
                        ->multiple()
                        ->panelLayout('grid')
                        ->disk('local') // Or use another disk configured in filesystem.php
                        ->visibility('private') // Ensure visibility is set to 'private'
                        ->label('Attachments')
                        ->directory(function (callable $get) {
                            $year = $get('year');
                            return "vault/{$year}";
                        })
                        ->openable()
                        ->downloadable(),

                    // ----- Inputs END----- //
                ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListVaults::route('/'),
            'create' => Pages\CreateVault::route('/create'),
            'edit' => Pages\EditVault::route('/{record}/edit'),
        ];
    }
}
