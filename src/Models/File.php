<?php

namespace Pharaonic\Laravel\Files\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Uploader\Models\Upload;

/**
 * @property int $id
 * @property string $field
 * @property int $upload_id
 * @property string $model_type
 * @property string $model_id
 * @property-read string $url
 * @property-read Upload $upload
 * @property-read Upload $thumbnail
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class File extends Model
{
    /**
     * The Caller Model instance.
     *
     * @var Model
     */
    public $caller;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field',
        'upload_id',
        'model_type',
        'model_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (!$value) {
            return $this->upload->{$key};
        }

        return $value;
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, [
            'size',
            'thumbnail',
            'visibility',
            'public',
            'private',
            'getUrlAttribute',
            'getTemporaryUrlAttribute',
            'url',
            'temporaryUrl'
        ])) {
            return $this->upload->{$method}(...$parameters);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Set the Caller Model instance.
     *
     * @param Model $model
     * @return void
     */
    public function setCaller(Model &$caller)
    {
        $this->caller = $caller;

        return $this;
    }
}
