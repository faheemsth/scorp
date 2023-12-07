<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = Employee::get();

        foreach($data as $k => $employee)
        {
            unset($employee->id, $employee->password, $employee->documents, $employee->user_id, $employee->created_by, $employee->created_at, $employee->updated_at, $employee->is_active, $employee->salary_type, $employee->salary);
            $data[$k]["employee_id"] = \Auth::user()->employeeIdFormat($employee->employee_id);
         }

        return $data;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Date of Birth",
            "Gender",
            "Contact",
            "Address",
            "Email",
            "Employee No",
            "Branch ID",
            "Department ID",
            "Designation ID",
            "Company Joining Date",
            "Account Holder Name",
            "Account Number",
            "Bank Name",
            "Bank Identifier Code",
            "Branch Location",
            "Tax Payer ID"
        ];
    }
}
