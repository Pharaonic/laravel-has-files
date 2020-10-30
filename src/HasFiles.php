<?php

namespace Pharaonic\Laravel\Files;

use Exception;

trait HasFiles
{
    protected static function bootHasFiles()
    {
        $attrs = get_class_vars(self::class);
        $attrs = array_merge(config('Pharaonic.files.fields', []), $attrs['filesAttributes'] ?? []);

        self::retrieved(function ($model) use ($attrs) {
            try {
                foreach ($attrs as $attr) $model->addGetterAttribute($attr, '_getFileAttribute');
                foreach ($attrs as $attr) $model->addSetterAttribute($attr, '_setFileAttribute');
            } catch (\Throwable $e) {
                throw new Exception('You have to use Pharaonic\Laravel\Helpers\Traits\HasCustomAttributes as a trait in ' . get_class($model));
            }
        });

        // foreach($attrs as $attr)
        self::deleting(function ($model) {
            $model->clearFiles();
        });
    }

    /**
     * Getting File
     */
    public function _getFileAttribute($key)
    {
        if ($this->isFileAttribute($key)) {
            $file = $this->files()->where('field', $key)->first();
            return $file ? $file->file : null;
        }
    }

    /**
     * Uploading File
     */
    public function _setFileAttribute($key, $value)
    {
        if ($this->isFileAttribute($key)) {
            $file = $this->files()->where('field', $key)->first();

            if ($file) {
                $options = $this->filesOptions[$key] ?? [];
                $options['file'] = $file->file;

                $newFile = upload($value, $options);
                $file->update(['upload_id' => $newFile->id]);
            } else {
                $file = upload($value, $this->filesOptions[$key] ?? []);

                return $this->files()->create([
                    'field'     => $key,
                    'upload_id' => $file->id,
                ]);
            }
        }

        return null;
    }

    /**
     * Getting files attributes
     */
    public function getFilesAttributes(): array
    {
        $fields = isset($this->filesAttributes) && is_array($this->filesAttributes) ? $this->filesAttributes : [];
        return array_merge(config('Pharaonic.files.fields', []), $fields);
    }

    /**
     * Check if file attribute
     */
    public function isFileAttribute(string $key): bool
    {
        return in_array($key, $this->getFilesAttributes());
    }

    /**
     * Get All Files
     */
    public function files()
    {
        return $this->morphMany(File::class, 'model');
    }

    /**
     * Clear All Files
     */
    public function clearFiles()
    {
        foreach ($this->files()->get() as $file) {
            $file->file->delete();
        }
    }
}
