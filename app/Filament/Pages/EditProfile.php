<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\FormsComponent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;

class EditProfile extends Page implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.pages.edit-profile';
    protected static ?string $title = 'Ubah Profil';

    public $email;
    public $whatsapp;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    //untuk menyembunyikan opsi halaman ini pada sidebar
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function mount()
    {
        $user = Auth::user();
        $this->form->fill([
            'email' => $user->email,
            'whatsapp' => $user->whatsapp,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [Forms\Components\TextInput::make('email')->label('email')->email(), Forms\Components\TextInput::make('whatsapp')->label('whatsapp'), Forms\Components\TextInput::make('current_password')->label('Password Lama')->password()->required(), Forms\Components\TextInput::make('new_password')->label('Password Baru')->password()->minLength(8), Forms\Components\TextInput::make('new_password_confirmation')->label('Konfirmasi Password Baru')->password()->same('new_password')];
    }
    protected function getFormActions(): array
    {
        return [Action::make('submit')->label('Simpan Perubahan')->submit('submit')->color('primary')];
    }
    public function submit()
    {
        $user = Auth::user();
        //validasi perubahan email dan whatsapp
        $user->email = $this->email;
        $user->whatsapp = $this->whatsapp;

        if (!$user) {
            Notification::make()->title('User Tidak Ditemukan, silahkan login kembali')->danger()->send();
            return;
        }
        //notifikasi jika salah memasukkan password lama
        if (!$this->current_password || !Hash::check($this->current_password, $user->password)) {
            Notification::make()->title('Password Anda Salah!')->danger()->send();
            return;
        }
        //update password
        $user->password = Hash::make($this->new_password);
        $user->save();
        //notifikasi berhasil update
        Notification::make()->title('Profile Berhasil Diubah')->success()->send();
        //mengosongkan form setelah update pasword berhasil
        $this->form->fill([
            'current_password' => '',
            'new_password' => '',
            'new_password_confirmation' => '',
        ]);
    }
    //Untuk mengatur urutan navigasi
    public static function getNavigationSort(): int
    {
        return 50;
    }
}
