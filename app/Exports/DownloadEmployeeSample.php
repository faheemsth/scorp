<?php
namespace App\Exports;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DownloadEmployeeSample implements FromCollection, WithHeadings
{
    // public function collection()
    // {
    //     return collect([
    //         ['Name', 'Email'],
    //         ['John Doe', 'johndoe@example.com'],
    //         ['Jane Doe', 'janedoe@example.com'],
    //     ]);
    // }


     /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data[] = [];

        $branches = Branch::get();

        foreach($branches as $branch){
            $departments = Department::where(['branch_id' => $branch->id])->get();

            foreach($departments as $department){
                $designations = Designation::where(['department_id' => $department->id])->get();
                foreach($designations as $designation){
                    $data[] = [
                        ' ', // name
                        ' ', // email
                        ' ', // password
                        ' ', // phone
                        ' ', // date of birth
                        ' ', // gender
                        ' ', // address
                        $branch->id, // branch_id
                        $department->id, // department_id
                        $designation->id, // designation_id
                        ' ', // company joining date
                        ' ', // account holder name
                        ' ', // account number
                        ' ', // bank name
                        ' ', // bank identifier code
                        ' ', // branch location
                        ' ' // tax payer id
                    ];
                }
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            "Name",
            "Email",
            "Password",
            "Phone",
            "Date of Birth",
            "Gender",
            "Address",
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