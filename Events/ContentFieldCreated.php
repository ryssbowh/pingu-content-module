<?php

namespace Pingu\Content\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Content\Entities\Field;

class ContentFieldCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Field $field)
    {
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
