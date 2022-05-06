<?php

namespace Filament\Resources\Pages\ListRecords\Concerns;

use Filament\Resources\Pages\Concerns\HasActiveLocaleSelect;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Translatable
{
    use HasActiveLocaleSelect;

    public function mount(): void
    {
        static::authorizeResourceAccess();

        $this->setActiveFormLocale();
    }

    protected function setActiveFormLocale(): void
    {
        $this->activeLocale = static::getResource()::getDefaultTranslatableLocale();
    }

    public function getTableRecords(): Collection | Paginator
    {
        parent::getTableRecords();

        $this->records->transform(fn (Model $record) => $record->setLocale($this->activeLocale));

        return $this->records;
    }

    protected function getActions(): array
    {
        return array_merge(
            [$this->getActiveLocaleSelectAction()],
            parent::getActions(),
        );
    }
}
