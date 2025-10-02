<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use App\Enums\PaymentStatusEnum;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatetimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Data Administrasi';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Placeholder::make('user_id')->label('Nama Lengkap')->content(fn($record) => $record->user->name ?? '-')->disabled(),
            Placeholder::make('nopendaftaran')->label('ID Transaksi')->content(fn($record) => $record->nopendaftaran ?? '-')->disabled(),
            Placeholder::make('nominal')->label('Total Tagihan')->content(fn($record) => $record->nominal ?? '-')->disabled(),
            // TextInput::make('gross_amount')->label('Jumlah Dibayar')->numeric()->minValue(150000)->maxValue(150000)->required(),
            Select::make('status')->label('Status Pembayaran')->options(PaymentStatusEnum::options())->required(),
            // DateTimePicker::make('paid_at')->label('Waktu Pembayaran')->nullable()->required(),
            Placeholder::make('paid_at')->label('Waktu Pembayaran')->content(fn($record) => $record->paid_at ?? '-')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Nama Siswa')->searchable(),
                TextColumn::make('nopendaftaran')->label('Nomor Pendaftaran')->searchable(),
                TextColumn::make('nominal')->label('Nominal')->money('IDR'),
                TextColumn::make('status')->label('Status')->badge()->color(
                    fn($state) => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'cancelled' => 'danger',
                        'refunded' => 'gray',
                        default => 'secondary',
                    },
                ),
                TextColumn::make('paid_at')->label('Dibayar Pada')->dateTime('d M Y H:i'),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status Pembayaran')->options(PaymentStatusEnum::options()),

                SelectFilter::make('user_id')->label('Nama Siswa')->relationship('user', 'name'),

                Filter::make('Tahun Pembayaran')->form([
                    Select::make('tahun')
                        ->label('Tahun')
                        ->options(collect(range(now()->year, now()->year - 5))->mapWithKeys(fn($year) => [$year => $year]))
                        ->searchable(),
                ]),
                Filter::make('Bulan Pembayaran')->form([
                    Select::make('bulan')
                        ->label('Bulan')
                        ->options(collect(range(now()->month, now()->month))->mapWithKeys(fn($month) => [$month => $month]))
                        ->searchable(),
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->headerActions([
                Action::make('Export Excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    // ->action(function () {
                    //     try {
                    //         // memanggil route export excel
                    //         return redirect(route('admin.payments.export.excel'));
                    //     } catch (\Exception $e) {
                    //         Notification::make()->title('Gagal Export')->body('Terjadi kesalahan saat mencoba mengeksport data. Silahkan coba lagi.')->danger()->presistent()->send();
                    //     }
                    // })
                    ->url(route('admin.payments.export.excel'), true)
                    ->color('success'),
                Action::make('Export PDF')
                    ->label('Export PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    // ->action(function () {
                    //     try {
                    //         // memanggil route export excel
                    //         return redirect(route('admin.payments.export.pdf'));
                    //     } catch (\Exception $e) {
                    //         Notification::make()->title('Gagal Export')->body('Terjadi kesalahan saat mencoba mengeksport data. Silahkan coba lagi.')->danger()->presistent()->send();
                    //     }
                    // })
                    ->url(route('admin.payments.export.pdf'), true)
                    ->color('danger'),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
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
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user'); //sesuaikan dengan nama relasi di file model
    }
    // untuk mengatur urutan navigasi
    public static function getNavigationSort(): int
    {
        return 30; //menggunakan kelipatan sepuluh agar jika ingin menyisipkan page lain masih bisa
    }
}
