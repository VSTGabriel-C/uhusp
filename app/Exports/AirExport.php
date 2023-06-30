<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class AirExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Temperature',
            'Humidity',
            'Power',
            'Days_on',
            'Date',
        ];
    }
    public function map($row): array
    {
        //dd($row);
        return [
            $row['temperature'] === 0.00 ? '0' : $row['temperature'],
            $row['humidity'] === 0.00 ? '0' : $row['humidity'],
            $row['power'] === 0 ? '0' : $row['power'],
            $row['days'] === 0 ? '0' : $row['days'],
            $row['created_at'] =  $row['created_at']
        ];
    }
}
