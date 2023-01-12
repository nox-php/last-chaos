<?php

namespace Nox\LastChaos\Filament\Resources\AccountResource\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Nox\LastChaos\Filament\Resources\AccountResource;
use Nox\LastChaos\Models\Account;

class EditAccount extends EditRecord
{
    protected static string $resource = AccountResource::class;

    public function banAccount(): void
    {
        $record = $this->getRecord();

        $record->ban();

        Notification::make()
            ->success()
            ->title($record->user_id)
            ->body('User has been banned')
            ->send();
    }

    public function unbanAccount(): void
    {
        $record = $this->getRecord();

        $record->unban();

        Notification::make()
            ->success()
            ->title($record->user_id)
            ->body('User has been un-banned')
            ->send();
    }

    protected function getActions(): array
    {
        return [
            Action::make('ban-account')
                ->label('Ban')
                ->icon('heroicon-o-x')
                ->color('danger')
                ->requiresConfirmation()
                ->action('banAccount')
                ->hidden(fn(): bool => $this->getRecord()->is_banned),
            Action::make('unban-account')
                ->label('Un-ban')
                ->icon('heroicon-o-check')
                ->color('danger')
                ->requiresConfirmation()
                ->action('unbanAccount')
                ->hidden(fn(): bool => !$this->getRecord()->is_banned),
            DeleteAction::make()
        ];
    }
}
