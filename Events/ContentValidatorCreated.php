<?php

namespace Pingu\Content\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\Validator;
use Pingu\Content\Entities\ContentType;

class ContentValidatorCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Validator $validator, ContentType $type)
    {
        $this->validator = $validator;
        $this->contentType = $type;
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
