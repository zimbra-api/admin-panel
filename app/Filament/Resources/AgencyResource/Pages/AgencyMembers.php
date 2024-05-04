<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources\AgencyResource\Pages;

use App\Filament\Resources\AgencyResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class AgencyMembers extends EditRecord
{
    protected static string $resource = AgencyResource::class;

    public function getTitle(): string
    {
        return __('Agency Members');
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
