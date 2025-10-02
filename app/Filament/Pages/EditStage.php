<?php

namespace App\Filament\Pages;

use App\Models\RegistrationStage;
use App\Enums\StageNameEnum;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class EditStage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'null';
    protected static ?string $title = 'Edit Tahapan Pendaftaran';
    protected static ?string $slug = 'edit-stage';
    protected static string $view = 'filament.pages.edit-stage';

    public ?array $formData = [];

    //untuk menyembunyikan opsi halaman ini pada sidebar
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $active = RegistrationStage::where('is_active', true)->first();

        $this->form->fill([
            'stage_id' => $active?->id,
            'stage_name' => $active?->stage_name,
            'start_date' => $active?->start_date,
            'end_date' => $active?->end_date,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema($this->getFormSchema())->statePath('formData');
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('stage_id')
                ->label('Pilihan Tahapan')
                ->options(RegistrationStage::all()->mapWithKeys(fn($t) => [$t->id => $t->stage_name->value])->toArray())
                ->required(),
            Select::make('stage_name')->label('Nama Tahapan')->options(StageNameEnum::options())->required(),
            DatePicker::make('start_date')->label('Tanggal Mulai')->required(),
            DatePicker::make('end_date')->label('Tanggal Selesai')->required(),
        ];
    }

    protected function getFormActions(): array
    {
        return [Action::make('submit')->label('simpan')->submit('submit')->color('success')];
    }

    public function submit()
    {
        $data = $this->form->getState();

        // Set semua is_active ke false
        RegistrationStage::query()->update(['is_active' => false]);

        // Update tahapan terpilih
        RegistrationStage::find($data['stage_id'])->update([
            'stage_name' => $data['stage_name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_active' => true,
        ]);
        Notification::make()->title('Success')->body('Tahapan Pendaftaran Berhasil Diperbarui')->success()->send();

        return redirect()->route('filament.admin.pages.dashboard-admin');
    }
}
