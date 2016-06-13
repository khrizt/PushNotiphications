<?php

namespace Khrizt\PushNotiphications\Collection;

use ArrayIterator;

class Collection extends ArrayIterator
{
    public function append($item)
    {
        parent::append($item);
    }

    public function clear()
    {
        for ($this->rewind(); $this->valid(); $this->next()) {
            $this->offsetUnset($this->key());
        }
    }
}
