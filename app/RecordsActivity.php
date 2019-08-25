<?php


namespace App;


trait RecordsActivity
{

    # Trait will listen for when Model is created and Generate new activity
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            # when new reccord created in database
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        # listen when reply been deleted
        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * @param $event
     * @return string
     * @throws \ReflectionException
     */
    protected function getActivityType($event): string
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
//        return $event . '_' . $type;
        return "{$event}_{$type}";
    }

    protected function recordActivity($event)
    {
        # timestamp will always default to now.
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
        # replaced by activity method below.
//        Activity::create([
//            'user_id' => auth()->id(),
//            'type' => $this->getActivityType($event),
//            'subject_id' => $this->id,
//            'subject_type' => get_class($this)
//        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
}
