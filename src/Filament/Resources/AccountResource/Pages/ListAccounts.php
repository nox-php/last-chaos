<?php

namespace Nox\LastChaos\Filament\Resources\AccountResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Collection;
use Nox\LastChaos\Filament\Resources\AccountResource;
use Nox\LastChaos\Models\Account;

class ListAccounts extends ListRecords
{
    protected static string $resource = AccountResource::class;

    public function banAccount(Account $record): void
    {
        $record->ban();

        Notification::make()
            ->success()
            ->title($record->user_id)
            ->body('User has been banned')
            ->send();
    }

    public function banAccounts(Collection $records): void
    {
        foreach($records as $record) {
            $this->banAccount($record);
        }
    }

    public function unbanAccount(Account $record): void
    {
        $record->unban();

        Notification::make()
            ->success()
            ->title($record->user_id)
            ->body('User has been un-banned')
            ->send();
    }

    public function unbanAccounts(Collection $records): void
    {
        foreach($records as $record) {
            $this->unbanAccount($record);
        }
    }
}