<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class PelangganResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?string $pluralModelLabel = 'Pelanggan';
    protected static ?string $modelLabel = 'Pelanggan';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pelanggan')
                    ->icon('heroicon-o-identification')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('id_pelanggan')->label('ID Pelanggan')->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('nolangg')->label('No. Langganan'),
                        Forms\Components\TextInput::make('tahun')->label('Tahun'),
                        Forms\Components\TextInput::make('nama')->label('Nama Lengkap')->required()->columnSpanFull(),
                        Forms\Components\Textarea::make('alamat')->label('Alamat')->columnSpanFull()->rows(2),
                        Forms\Components\TextInput::make('telepon')->label('Telepon')->tel(),
                        Forms\Components\TextInput::make('KEL')->label('Kelurahan'),
                        Forms\Components\TextInput::make('kode_unit')->label('Kode Unit'),
                    ]),

                Forms\Components\Section::make('Info Teknis')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('nometer')->label('No. Meter'),
                        Forms\Components\TextInput::make('merk_meter')->label('Merk Meter'),
                        Forms\Components\TextInput::make('diameter')->label('Diameter'),
                        Forms\Components\TextInput::make('tarif')->label('Tarif / Golongan'),
                        Forms\Components\TextInput::make('jenis_pelayanan')->label('Jenis Pelayanan'),
                        Forms\Components\TextInput::make('kode_alamat')->label('Kode Alamat'),
                        Forms\Components\TextInput::make('kas')->label('Kas'),
                        Forms\Components\Select::make('status')->label('Status')
                            ->options(['aktif' => 'Aktif', 'tutup' => 'Tutup', 'bongkar' => 'Bongkar'])
                            ->default('aktif'),
                    ]),

                Forms\Components\Section::make('BA & Tanggal')
                    ->icon('heroicon-o-calendar')
                    ->columns(3)
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('BApasang')->label('BA Pasang'),
                        Forms\Components\DatePicker::make('tglPasang')->label('Tgl Pasang'),
                        Forms\Components\TextInput::make('BAtutup')->label('BA Tutup'),
                        Forms\Components\DatePicker::make('tglTutup')->label('Tgl Tutup'),
                        Forms\Components\TextInput::make('BAbuka')->label('BA Buka'),
                        Forms\Components\DatePicker::make('tglBuka')->label('Tgl Buka'),
                        Forms\Components\DatePicker::make('tglBongkar')->label('Tgl Bongkar'),
                    ]),

                Forms\Components\Section::make('Lokasi & Map')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Forms\Components\ViewField::make('location_picker')
                            ->view('filament.forms.components.location-picker')
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Hidden::make('lati'),
                            Forms\Components\Hidden::make('longi'),
                            Forms\Components\TextInput::make('alti')->label('Altitude')->placeholder('Opsional'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_pelanggan')->label('ID Pel')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nolangg')->label('No. Langganan')->searchable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('KEL')->label('Kelurahan')->searchable(),
                Tables\Columns\TextColumn::make('tarifRel.golongan')->label('Tarif')->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Unit')
                    ->state(fn ($record) => ($record->kode_unit ?? '-') . ' - ' . ($record->unitRel?->namaunit ?? '-'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('nometer')->label('No. Meter')->searchable(),
                Tables\Columns\TextColumn::make('statusRel.nama')->label('Status')
                    ->badge()
                    ->color(fn (string $state, $record): string => match ($record->statusRel?->nama) {
                        'Aktif' => 'success',
                        'Tutup' => 'danger',
                        'Bongkar' => 'gray',
                        default => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(fn () => \App\Models\Status::pluck('nama', 'kode')->toArray()),
                Tables\Filters\SelectFilter::make('tarif')
                    ->label('Tarif')
                    ->options(fn () => \App\Models\Tarif::pluck('golongan', 'id_tarif')->toArray()),
                Tables\Filters\SelectFilter::make('unit')
                    ->label('Unit')
                    ->attribute('kode_unit')
                    ->options(fn () => \App\Models\Unit::pluck('namaunit', 'kode')->toArray()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->recordAction(Tables\Actions\ViewAction::class)
            ->extremePaginationLinks()
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ])
            ->defaultSort('nolangg');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Informasi Pelanggan')
                    ->schema([
                        Components\Grid::make(3)->schema([
                            Components\TextEntry::make('id_pelanggan')->label('ID Pelanggan')->weight('bold'),
                            Components\TextEntry::make('nolangg')->label('No. Langganan'),
                            Components\TextEntry::make('nama')->label('Nama Pelanggan')->weight('bold'),
                        ]),
                        Components\TextEntry::make('alamat')->label('Alamat Lengkap'),
                        Components\Grid::make(3)->schema([
                            Components\TextEntry::make('KEL')->label('Kelurahan'),
                            Components\TextEntry::make('telepon')->label('No. Telepon'),
                            Components\TextEntry::make('statusRel.nama')->label('Status')->badge()->color('success'),
                        ]),
                    ]),
                Components\Grid::make(2)->schema([
                    Components\Section::make('Detail Teknis & Tarif')
                        ->columnSpan(1)
                        ->schema([
                            Components\TextEntry::make('tarifRel.golongan')->label('Golongan Tarif'),
                            Components\TextEntry::make('unitRel.namaunit')->label('Unit Pelayanan'),
                            Components\TextEntry::make('nometer')->label('No. Meteran'),
                            Components\TextEntry::make('merk_meter')->label('Merk Meter'),
                            Components\TextEntry::make('diameter')->label('Diameter'),
                        ]),
                    Components\Section::make('Data Pasang / Tutup')
                        ->columnSpan(1)
                        ->schema([
                            Components\TextEntry::make('tglPasang')->label('Tgl Pasang')->date(),
                            Components\TextEntry::make('tglTutup')->label('Tgl Tutup')->date(),
                            Components\TextEntry::make('tglBongkar')->label('Tgl Bongkar')->date(),
                        ]),
                ]),
                Components\Section::make('Lokasi Geospasial')
                    ->schema([
                        Components\Grid::make(3)->schema([
                            Components\TextEntry::make('lati')->label('Latitude'),
                            Components\TextEntry::make('longi')->label('Longitude'),
                            Components\TextEntry::make('alti')->label('Altitude'),
                        ]),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
