<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class BaseModel extends Model
{
    /**
     * Format tanggal saat model dikonversi ke array atau JSON.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Jakarta'))
                    ->format('Y-m-d H:i:s');
    }
}
