<?php

namespace App\Helpers\CoreApp\Traits;

use App\Models\ActivityLogs\AuthorInfo;
use Carbon\Carbon;

trait AuthorInfoTrait
{
    protected AuthorInfo $author;

    public function __construct(AuthorInfo $author)
    {
        $this->author = $author;
    }

    public function createdBy($model)
    {
        try {
            $author                     = new AuthorInfo;
            $author->authorable_type    = get_class($model);
            $author->authorable_id      = $model->id;
            $author->created_by         = auth()->id();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function updatedBy($model)
    {
        $author = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
        if (!empty($author)) {
            $author->updated_by = auth()->id();
            $author->save();
            return $author;
        } else {
            $this->createdBy($model);
        }
    }

    public function approvedBy($model)
    {
        try {
            $author = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->approved_by = auth()->id();
            $author->approved_at = Carbon::now();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function rejectedBy($model)
    {
        try {
            $author              = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->rejected_by = auth()->id();
            $author->rejected_at = Carbon::now();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function cancelledBy($model)
    {
        try {
            $author                 = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->cancelled_by   = auth()->id();
            $author->cancelled_at   = Carbon::now();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function publishedBy($model)
    {
        try {
            $author                 = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->published_by   = auth()->id();
            $author->published_at   = Carbon::now();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function unpublishedBy($model)
    {
        try {
            $author                     = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->unpublished_by     = auth()->id();
            $author->unpublished_at     = Carbon::now();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function deletedBy($model)
    {
        try {
            $author             = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->deleted_by = auth()->id();
            $author->deleted_at = Carbon::now();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function archivedBy($model)
    {
        try {
            $author                 = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->archived_by    = auth()->id();
            $author->archived_at    = Carbon::now();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function restoredBy($model)
    {
        try {
            $author                 = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->restored_by    = auth()->id();
            $author->restored_at    = Carbon::now();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function referredBy($model)
    {
        try {
            $author                 = AuthorInfo::where(['authorable_type' => get_class($model), 'authorable_id' => $model->id])->first();
            $author->referred_by    = auth()->id();
            $author->referred_at    = Carbon::now();
            $author->save();
            return $author;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
