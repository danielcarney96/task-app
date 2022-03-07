<?php

namespace App\Traits;

use App\Models\Label;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait HasLabels
{
    public static function bootHasLabels()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                return;
            }

            $model->labels()->detach();
        });
    }

    public function labels(): BelongsToMany
    {
        return $this->morphToMany(Label::class, 'model', 'model_has_labels', 'model_id');
    }

    public function scopeLabel(Builder $query, array|int|Label|Collection $labels): Builder
    {
        if ($labels instanceof Collection) {
            $labels = $labels->all();
        }

        $labels = array_map(function ($label) {
            if ($label instanceof Label) {
                return $label;
            }

            return Label::findById($label);
        }, Arr::wrap($labels));

        return $query->whereHas('labels', function (Builder $subQuery) use ($labels) {
            $key = (new Label())->getKeyName();
            $subQuery->whereIn('labels' . ".$key", \array_column($labels, $key));
        });
    }

    public function addLabel(array|int|Label|Collection ...$labels)
    {
        $labels = collect($labels)->flatten();

        $model = $this->getModel();

        if ($model->exists) {
            $this->labels()->sync($labels, false);
            $labels->load('labels');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($labels, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }

                    $model->labels()->sync($labels, false);
                    $model->load('labels');
                }
            );
        }

        return $this;
    }

    public function removeLabel(int|Label $label)
    {
        $this->labels()->detach($label);

        $this->load('labels');

        return $this;
    }

    public function syncLabels(array|int|Label|Collection ...$labels)
    {
        $this->labels()->detach();

        return $this->addLabel($labels);
    }

    public function hasLabel(array|int|Label|Collection $labels): bool
    {
        if (is_int($labels)) {
            $key = (new Label())->getKeyName();

            return $this->labels->contains($key, $labels);
        }

        if ($labels instanceof Label) {
            return $this->labels->contains($labels->getKeyName(), $labels->getKey());
        }

        if (is_array($labels)) {
            foreach ($labels as $label) {
                if ($this->hasLabel($label)) {
                    return true;
                }
            }

            return false;
        }

        return $labels->intersect($this->labels)->isNotEmpty();
    }
}
