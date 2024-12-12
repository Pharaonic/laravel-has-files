<?php

namespace Pharaonic\Laravel\Files\Observers;

use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    /**
     * Handle the model "saved" event.
     *
     * @param Model $model
     * @return void
     */
    public function saved(Model $model)
    {
        foreach ($model->getDirtyFiles() as $attribute) {
            if ($attribute->isDirty() && $attribute->getOriginal()) {
                $attribute->getOriginal()->delete();
            }

            $upload = upload(
                $attribute->get(),
                $model->getFileOptions($attribute->getName())
            );

            $file = $model->files()->create([
                'field' => $attribute->getName(),
                'upload_id' => $upload->id
            ])
                ->setRelation('upload', $upload)
                ->setCaller($model);

            $attribute->reset($file);
        }
    }

    /**
     * Handle the model "deleting" event.
     *
     * @param  Model $model
     * @return void
     */
    public function deleting(Model $model)
    {
        foreach ($model->getFilesAttributes() as $attribute) {
            $model->{$attribute}?->delete();
        }
    }
}
