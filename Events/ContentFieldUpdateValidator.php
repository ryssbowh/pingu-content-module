<?php

namespace Pingu\Content\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\Validator;
use Pingu\Content\Contracts\ContentFieldContract;
use Pingu\Content\Entities\ContentType;

class ContentFieldUpdateValidator
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Validator $validator, ContentFieldContract $field)
    {
        $this->validator = $validator;
        $this->field = $field;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
