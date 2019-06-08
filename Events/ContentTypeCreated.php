<?php

namespace Pingu\Content\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Content\Entities\ContentType;

class ContentTypeCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ContentType $contentType)
    {
        $this->contentType = $contentType;
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
