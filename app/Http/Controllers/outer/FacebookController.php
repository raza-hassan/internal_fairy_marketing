<?php

namespace App\Http\Controllers\outer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\LeadSource;
use App\Models\Clients;
use App\Models\Project;
use Maatwebsite\Excel\Facades\Excel;

class FacebookController extends Controller {


    public function facebookImport() {
        return view('facebook-import');
    }

    public function facebookfileExport() {
        return Excel::download(new UsersExport, 'users-collection.xlsx');
    }

    public function facebookfileImport(Request $request) {
        $path = $request->file('file')->getRealPath();
        $records = array_map('str_getcsv', file($path));

        if (!count($records) > 0) {
            return 'Error...';
        }

        $fields = array_map('strtolower', preg_replace('/[^a-zA-Z0-9\']/', '', $records[0]));
        array_shift($records);

        foreach ($records as $record) {
            if (count($fields) != count($record)) {
                return 'csv_upload_invalid_data';
            }

            // Decode unwanted html entities
            $record = array_map("html_entity_decode", $record);

            // Set the field name as key
            $record = array_combine($fields, $record);

            // Get the clean data
            $this->rows[] = $record;//$this->clear_encoding_str($record);
        }

        foreach ($this->rows as $data) {

            $client = Clients::where('phone', $data['phonenumber'])->orWhere('email', $data['emailaddress'])->first();echo '<pre>';
            if ($data['source'] == 'Safa Burj Mall Overseas' || $data['source'] == 'Safa Burj Mall') {
                $data['source'] = 'Safa Burj Mall';
            }
            $project = Project::where('name', $data['source'])->first();

            if (empty($client)) {
                $client = Clients::firstOrCreate(
                                [
                            'name' => $data['name'],
                            'email' => $data['emailaddress'],
                            'phone' => $data['phonenumber'],
                            'source_id' => 3,
                                ], [
                            'email' => $data['emailaddress'],
                            'phone' => $data['phonenumber']
                                ]
                );
                Leads::create(['client_id' => $client->id, 'project_id' => $project['id'], 'source_id' => 3]);
            }
        }
        return redirect('newleads')->withStatus(__('Client Created Successfully.'));
    }

    private function clear_encoding_str($value) {

    }

}
