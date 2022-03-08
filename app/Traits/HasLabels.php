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
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
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

            return Label::where('id', $label);
        }, Arr::wrap($labels));

        return $query->whereHas('labels', function (Builder $subQuery) use ($labels) {
            $subQuery->whereIn('labels.id', \array_column($labels, 'id'));
        });
    }

    public function addLabel(array|int|Label|Collection ...$labels)
    {
        $labels = collect($labels)
            ->flatten()
            ->reduce(function ($array, $label) {
                if (empty($label)) {
                    return $array;
                }

                $label = $this->getStoredLabel($label);

                if (! $label instanceof Label) {
                    return $array;
                }

                array_push($array, $label->id);

                return $array;
            }, []);

        $model = $this->getModel();

        if ($model->exists) {
            $this->labels()->sync($labels, false);
            $model->load('labels');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($labels, $model) {
                    if ($model->id != $object->id) {
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
        $this->labels()->detach($this->getStoredLabel($label));

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
            return $this->labels->contains('id', $labels);
        }

        if ($labels instanceof Label) {
            return $this->labels->contains('id', $labels->id);
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

    protected function getStoredLabel(Label|int $label): Label
    {
        if (is_numeric($label)) {
            return Label::where('id', $label)->first();
        }

        return $label;
    }
}
