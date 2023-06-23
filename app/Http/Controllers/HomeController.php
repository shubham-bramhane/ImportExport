<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Reader\Exception;

use PhpOffice\PhpSpreadsheet\Writer\Xls;

use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }



    // upload file

    public function uploadFile(Request $request)
    {

        $request->validate([

            'file' => 'required|mimes:xls,xlsx'

        ]);

        $the_file = $request->file('file');

        try {

            $reader = IOFactory::createReader('Xlsx');

            $spreadsheet = $reader->load($the_file);



            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $data = [];

            foreach ($sheetData as $key => $value) {

                if ($key > 0) {

                    // get by column name



                    $data[] = [

                        // 'name' => where column name is name

                        'name' => $value[ 0],



                        'email' => $value[1],

                        'password' => $value[2],

                    ];
                }
            }

            User::insert($data);



        } catch (Exception $e) {

            $reader = IOFactory::createReader('Xls');

            $spreadsheet = $reader->load($the_file);

        }







        return 'File has been uploaded successfully in laravel 8';
    }



    // export file

    public function export(){


        $user = User::all();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Name');

        $sheet->setCellValue('B1', 'Email');

        $sheet->setCellValue('C1', 'Password');

        // fill data from database to excel

        $cell = 2;

        foreach ($user as $key => $value) {

            $sheet->setCellValue('A' . $cell, $value->name);

            $sheet->setCellValue('B' . $cell, $value->email);

            $sheet->setCellValue('C' . $cell, $value->password);

            $cell++;

        }

        // download excel file

        $writer = new Xls($spreadsheet);

        $writer->save('users.xls');

        return response()->download(public_path('users.xls'));



    }


}
