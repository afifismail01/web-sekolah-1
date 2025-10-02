<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Student;
use App\Enums\StudentStatusEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Data Pengumuman';
    protected static ?string $slug = 'Announcement';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([Placeholder::make('user_id')->label('Nama Lengkap')->content(fn($record) => $record->user->name ?? '-')->disabled(), Select::make('status')->label('Status Seleksi')->options(StudentStatusEnum::options())->required()]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Lengkap')->searchable(),
                TextColumn::make('status')->label('Status Seleksi')->badge()->color(
                    fn($state) => match ($state->value ?? null) {
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                        'Cadangan' => 'warning',
                        default => 'secondary',
                    },
                ),
            ])
            ->filters([SelectFilter::make('status')->label('Status Siswa')->options(StudentStatusEnum::options())])
            ->headerActions([
                Action::make('Export Excel')->label('Export Excel')->icon('heroicon-o-arrow-down-tray')->url(route('admin.announcements.export.excel'), true)// ->action(function () {
                //     try {
                //         // memanggil route export excel
                //         return redirect(route('admin.announcement.export.excel'));
                //     } catch (\Exception $e) {
                //         Notification::make()->title('Gagal Export')->body('Terjadi kesalahan saat mencoba mengeksport data. Silahkan coba lagi.')->danger()->presistent()->send();
                //     }
                // })
                ->color('success'),
                Action::make('Export PDF')
                    ->label('Export PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    // ->action(function () {
                    //     try {
                    //         // memanggil route export excel
                    //         return redirect(route('admin.announcement.export.pdf'));
                    //     } catch (\Exception $e) {
                    //         Notification::make()->title('Gagal Export')->body('Terjadi kesalahan saat mencoba mengeksport data. Silahkan coba lagi.')->danger()->presistent()->send();
                    //     }
                    // })
                    ->url(route('admin.announcements.export.pdf'), true)
                    ->color('danger'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
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
            'index' => Pages\ListAnnouncement::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }
    // untuk mengatur urutan navigasi
    public static function getNavigationSort(): int
    {
        return 40; //menggunakan kelipatan sepuluh agar jika ingin menyisipkan page lain masih bisa
    }
}
