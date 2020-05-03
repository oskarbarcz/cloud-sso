<?php

declare(strict_types=1);

namespace App\Transformer\Serializer;

use League\Fractal\Serializer\ArraySerializer;

class SimpleArraySerializer extends ArraySerializer
{
    /** @inheritDoc */
    public function collection($resourceKey, array $data): array
    {
        return $data;
    }
}
