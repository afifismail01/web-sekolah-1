<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use App\Enums\GenderEnum;
use App\Enums\StudentStatusEnum;
use App\Enums\AdmissionTrackEnum;
use App\Enums\EducationLevelEnum;
use App\Enums\CitizenshipEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
// use Filament\Tables\Columns\DateColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Data Calon Siswa';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('Nama Lengkap')->required(),
            TextInput::make('nisn')->label('NISN')->required(),
            // TextInput::make('address')->label('Alamat')->required(),
            DatePicker::make('birth_date')->label('Tanggal Lahir')->required(),
            TextInput::make('birth_place')->label('Tempat Lahir')->required(),
            TextInput::make('national_id_number')->label('NIK')->minLength(16)->maxLength(16)->rule('digits:16')->required(),
            Select::make('status')->label('Status')->options(StudentStatusEnum::options()),
            Select::make('admission_track')->label('Jalur Pendaftaran')->options(AdmissionTrackEnum::options())->required(),
            Select::make('education_level')->label('Jenjang Pendaftaran')->options(EducationLevelEnum::options())->required(),
            // TextInput::make('postal_code')->label('Kode Pos')->minLength(5)->maxLength(5)->rule('digits:5')->required(),
            Radio::make('citizenship')->label('Kewarganegaraan')->options(CitizenshipEnum::options())->inline()->required(),
            Radio::make('gender')->label('Jenis Kelamin')->options(GenderEnum::options())->inline()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('No')->sortable(),
                TextColumn::make('parents.user_id')->label('Cek ID Relasi'),
                TextColumn::make('name')->label('Nama Lengkap')->searchable(),
                TextColumn::make('birth_date')->label('Tanggal Lahir')->date('d M Y'),
                TextColumn::make('birth_place')->label('Tempat Lahir'),
                TextColumn::make('gender')->label('Jenis Kelamin'),
                TextColumn::make('national_id_number')->label('NIK'),
                TextColumn::make('status')->label('Status'),
                TextColumn::make('education_level')->label('Jenjang Pendaftaran'),
                TextColumn::make('admission_track')->label('Jalur Pendaftaran'),
                TextColumn::make('parents.postal_code')->label('Kode Pos'),
                TextColumn::make('parents.father_name')->label('Nama Ayah')->default('-'),
                TextColumn::make('parents.mother_name')->label('Nama Ibu')->default('-'),
                TextColumn::make('parents.guardian_name')->label('Nama Wali')->default('-'),
                TextColumn::make('parents.father_main_job')->label('Pekerjaan Ayah')->default('-'),
                TextColumn::make('parents.mother_main_job')->label('Pekerjaan Ibu')->default('-'),
                TextColumn::make('parents.guardian_main_job')->label('Pekerjaan Wali')->default('-'),
                TextColumn::make('parents.father_phone')->label('Nomor Telepon Ayah')->default('-'),
                TextColumn::make('parents.mother_phone')->label('Nomor Telepon Ibu')->default('-'),
                TextColumn::make('parents.guardian_phone')->label('Nomor Telepon Wali')->default('-'),
                TextColumn::make('lihat_file')
                    ->label('File Upload')
                    ->getStateUsing(fn() => 'lihat') //Untuk membuat dummy state karena tombol dan kolom yang akan tampil bersifat virtual dan tidak ada di dalam database
                    ->icon('heroicon-o-eye')
                    ->formatStateUsing(fn() => 'Lihat File') // untuk menampilkan teks
                    ->url(fn($record) => route('admin.students.files', $record)) //custom route ke detail
                    ->openUrlInNewTab()
                    ->color('info')
                    ->extraAttributes(['class' => 'font-bold text-blue-600']),
            ])
            ->searchable()
            ->filters([SelectFilter::make('admission_track')->label('Filter Berdasar Jalur')->options(AdmissionTrackEnum::options()), SelectFilter::make('education_level')->label('Filter Berdasar Jenjang')->options(EducationLevelEnum::options())])
            ->headerActions([
                Action::make('Export Excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    // ->action(function () {
                    //     try {
                    //         // memanggil route export excel
                    //         return redirect(route('admin.students.export.excel'));
                    //     } catch (\Exception $e) {
                    //         Notification::make()->title('Gagal Export')->body('Terjadi kesalahan saat mencoba mengeksport data. Silahkan coba lagi.')->danger()->presistent()->send();
                    //     }
                    // })
                    ->url(route('admin.students.export.excel'), true)
                    ->color('success'),

                Action::make('Export PDF')
                    ->label('Export PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    // ->action(function () {
                    //     try {
                    //         // memanggil route export excel
                    //         return redirect(route('admin.students.export.pdf'));
                    //     } catch (\Exception $e) {
                    //         Notification::make()->title('Gagal Export')->body('Terjadi kesalahan saat mencoba mengeksport data. Silahkan coba lagi.')->danger()->presistent()->send();
                    //     }
                    // })
                    ->url(route('admin.students.export.pdf'), true)
                    ->color('danger'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('resend_account_info')
                    ->label('Kirim Ulang Info Akun')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $phone = $record->user->whatsapp;
                        if (!$phone) {
                            Notification::make()->title('Gagal Mengirim')->body('Nomor Whatsapp Calon Siswa Tidak Ditemukan')->danger()->send();
                            return;
                        }
                        $message = "Assalamu'alaikum,\n" . "Berikut adalah informasi akun siswa anda: \n" . "Email: {$record->user->email}\n" . "Nomor Telepon: {$record->user->whatsapp}\n" . "Silahkan pergi ke halaman https://registrasi.miftahunnajah.sch.id/login dan klik 'Lupa Password' jika anda belum bisa login. \n" . 'terimakasih';
                        $response = Http::withHeaders([
                            'Authorization' => env('FONNTE_TOKEN'),
                        ])
                            ->asForm()
                            ->post('https://api.fonnte.com/send', ['target' => $phone, 'message' => $message, 'countryCode' => '62']);
                        if ($response->successful()) {
                            Notification::make()->title('Pesan Terkirim')->body('Informasi akun berhasil dikirim ulang.')->success()->send();
                        } else {
                            Notification::make()->title('Pesan Gagal Terkirim')->body('Terjadi kesalahan saat mengirim pesan')->danger()->send();
                        }
                    }),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['parents', 'UploadedFiles', 'user']);
    }
    // untuk mengatur urutan navigasi
    public static function getNavigationSort(): int
    {
        return 20; //menggunakan kelipatan sepuluh agar jika ingin menyisipkan page lain masih bisa
    }
}
