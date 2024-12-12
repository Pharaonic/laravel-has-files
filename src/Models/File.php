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
 * @property-read Upload $file
 * @property-read Upload $thumbnail
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class File extends Model
{
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
    public function file()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Get Url Directly
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->file->url;
    }

    /**
     * Get Thumbnail Object
     *
     * @return Upload|null
     */
    public function getThumbnailAttribute()
    {
        return $this->file->thumbnail ?? null;
    }
}
