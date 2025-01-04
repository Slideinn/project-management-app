<?php
namespace App\Traits;

trait HasCreatorId
{
    /**
     * Add creator_id to the data before creating the model.
     *
     * @param  array  $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // execute the parent method in case its overriden in the class
        if (method_exists($this, 'mutateFormDataBeforeCreate')) {
            $data = parent::mutateFormDataBeforeCreate($data);
        }

        $data['creator_id'] = auth()->id();
        
        return $data;
    }
}