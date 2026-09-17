<?php

namespace App\Imports;

use App\Models\DataLabelSbsite;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DataLabelSbsiteImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private string $uploadVersion;

    public function __construct(string $uploadVersion)
    {
        $this->uploadVersion = $uploadVersion;
    }

    public function model(array $row): ?DataLabelSbsite
    {
        return new DataLabelSbsite([
            'no_urut'        => $row['no_urut'] ?? null,
            'upload_version' => $this->uploadVersion,
            'id_sb_site'     => $row['id_sb_site'] ?? null,
            'id_vendor'      => $row['id_vendor'] ?? null,
            'po'             => $row['po'] ?? null,
            'item'           => $row['item'] ?? null,
            'country'        => $row['country'] ?? null,
            'building'       => $row['building'] ?? null,
            'cell'           => $row['cell'] ?? null,
            'sdd'            => $row['sdd'] ?? null,
            'qty'            => $row['qty'] ?? null,
            'status_received' => 0,
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
