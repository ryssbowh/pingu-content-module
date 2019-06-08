<?php

namespace Pingu\Content\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Content\Entities\Content;

class CreatingContent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Content $content)
    {
        $this->content = $content;
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
