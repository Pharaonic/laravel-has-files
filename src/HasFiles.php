<?php

namespace Pharaonic\Laravel\Files;

use Exception;

/**
 * Has Files Trait
 *
 * @version 1.0
 * @author Raggi <support@pharaonic.io>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait HasFiles
{
    /**
     * Files Atrributes on Save/Create
     *
     * @var array
     */
    protected static $filesAttributesAction = [];

    protected static function bootHasFiles()
    {
        $attrs = get_class_vars(self::class);
        $attrs = array_merge(config('Pharaonic.files.fields', []), $attrs['filesAttributes'] ?? []);

        // Created
        self::creating(function ($model) use ($attrs) {
            foreach ($model->getAttributes() as $name => $value) {
                if (in_array($name, $attrs)) {
                    self::$filesAttributesAction[$name] = $value;
                    unset($model->{$name});
                }
            }
        });

        // Created
        self::created(function ($model) {
            if (count(self::$filesAttributesAction) > 0) {
                foreach (self::$filesAttributesAction as $name => $file)
                    $model->setAttribute($name, $model->_setFileAttribute($name, $file));
            }
        });

        // Retrieving
        self::retrieved(function ($model) use ($attrs) {
            try {
                foreach ($attrs as $attr) $model->addGetterAttribute($attr, '_getFileAttribute');
                foreach ($attrs as $attr) $model->addSetterAttribute($attr, '_setFileAttribute');
            } catch (\Throwable $e) {
                throw new Exception('You have to use Pharaonic\Laravel\Helpers\Traits\HasCustomAttributes as a trait in ' . get_class($model));
            }
        });


        // Deleting
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

                return $file;
            } else {
                $file = upload($value, $this->filesOptions[$key] ?? []);

                $this->files()->create([
                    'field'     => $key,
                    'upload_id' => $file->id,
                ]);

                return $file;
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
