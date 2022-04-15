<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Models\Admin\Country;

class CountryImport extends DefaultValueBinder implements ToModel, ToCollection, WithCustomValueBinder, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        $countries = new Country();
        $countries->id = $row['country_id'];
        $countries->name = $row['name'];
        $countries->iso_code_2 = $row['iso_code_2'];
        $countries->iso_code_3 = $row['iso_code_3'];
        $countries->address_format = $row['address_format'];
        $countries->postcode_required = $row['postcode_required'];
        $countries->save();
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //
    }
}
