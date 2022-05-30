<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee as Employee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('index');
    }
    
    // Store Employee Data

    public function store(Request $request)
    {
        $file = $request->file('avatar');
        $fileName = time(). ".". $file->getClientOriginalExtension();
        $file->storeAs('public/images', $fileName);

        $empData = [
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'post' => $request->post,
            'avatar' => $fileName,
        ];

        $stmt = Employee::create($empData);
        if ($stmt) {
            return response()->json([
                'status' => 200
            ]);
        }else{
            return response()->json([
                'status' => 300
            ]);
        };
    }

    // Fetch All Employees Data

    public function fetchAll()
    {
        $stmt = Employee::all();
        $output = '';
        if ($stmt->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Post</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
                foreach ($stmt as $item) {
                    $output .= '<tr>
                        <td>'.$item->id.'</td>
                        <td>
                            <img src="storage/images/'.$item->avatar.'" width="50" class="img-thumbnail rounded-circle" />
                        </td>
                        <td> '.$item->first_name.' '.$item->last_name.' </td>
                        <td>'.$item->email.'</td>
                        <td>'.$item->post.'</td>
                        <td>'.$item->phone.'</td>
                        <td>
                            <a href="#" id="'.$item->id.'" class="mx-2 editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"><i class="bi-pencil-square text-success"></i></a>
                            <a href="#" id="'.$item->id.'" class="mx-2 deleteIcon"><i class="bi-trash text-danger"></i></a>
                        </td>
                    </tr>';
                }

                $output .= '</tbody></table>';
                echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">
                No records present in the database
            </h1>';
        }
    }

    // handle edit ajax

    public function edit(Request $request)
    {
        $id = $request->id;
        $stmt = Employee::find($id);
        return response()->json($stmt);
    }

    // instant update 
    public function update(Request $request)
    {
        $fileName = '';
        $stmt = Employee::find($request->emp_id);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time(). '.' .$file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            if ($stmt->avatar) {
                Storage::delete('public/images/' .$stmt->avatar);
            }
        }else {

            $fileName = $request->emp_avatar;
        }

        $empData = [
                'first_name' => $request->fname,
                'last_name' => $request->lname,
                'email' => $request->email,
                'phone' => $request->phone,
                'post' => $request->post,
                'avatar' => $fileName,
            ];
            
            $final_stmt = $stmt->update($empData);
            if ($final_stmt) {
                return response()->json([
                    'status' => 200
                ]);
            }else {
                return response()->json([
                    'status' => 300
                ]);
            }
    }

    // delete data for Employee
    public function delete(Request $request)
    {
        $id = $request->id;
        $stmt = Employee::find($id);
        // return $stmt;
        if (Storage::delete('public/images/'.$stmt->avatar)) {
            return Employee::destroy($id);
        }
    }
}
