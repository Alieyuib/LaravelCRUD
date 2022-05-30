<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta name="csrf_token" content="{{ csrf_token() }}" /> --}}
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}" /> --}}
    <title>CRUD Application With Image Upload Using Laravel 7 and Ajax</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <style type="text/css" rel="stylesheet">
        /* #avatar-div{
            width: 50% !important;
        } */
    </style>

</head>
<body class="bg-dark">
    {{-- add new employee modal start --}}
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="add_employee_form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="fname">First Name</label>
              <input type="text" name="fname" class="form-control" placeholder="First Name" required>
            </div>
            <div class="col-lg">
              <label for="lname">Last Name</label>
              <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
            </div>
          </div>
          <div class="my-2">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
          </div>
          <div class="my-2">
            <label for="phone">Phone</label>
            <input type="tel" name="phone" class="form-control" placeholder="Phone" required>
          </div>
          <div class="my-2">
            <label for="post">Post</label>
            <input type="text" name="post" class="form-control" placeholder="Post" required>
          </div>
          <div class="my-2">
            <label for="avatar">Select Avatar</label>
            <input type="file" name="avatar" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="add_employee_btn" class="btn btn-primary">Add Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- add new employee modal end --}}

{{-- edit employee modal start --}}
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="edit_employee_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="emp_id" id="emp_id">
        <input type="hidden" name="emp_avatar" id="emp_avatar">
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="fname">First Name</label>
              <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name" required>
            </div>
            <div class="col-lg">
              <label for="lname">Last Name</label>
              <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name" required>
            </div>
          </div>
          <div class="my-2">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" required>
          </div>
          <div class="my-2">
            <label for="phone">Phone</label>
            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone" required>
          </div>
          <div class="my-2">
            <label for="post">Post</label>
            <input type="text" name="post" id="post" class="form-control" placeholder="Post" required>
          </div>
          <div class="my-2">
            <label for="avatar">Select Avatar</label>
            <input type="file" name="avatar" class="form-control">
          </div>
          <div class="mt-2" id="avatar avatar-div">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="edit_employee_btn" class="btn btn-success">Update Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- edit employee modal end --}}
  <div class="container">
    <div class="row my-5">
      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-header bg-success d-flex justify-content-between align-items-center">
            <h3 class="text-light">Manage Employees</h3>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i
                class="bi-plus-circle me-2"></i>Add New Employee</button>
          </div>
          <div class="card-body" id="show_all_employees">
            <h1 class="text-center text-secondary my-5">
                <img src="{{asset('images/hourglass.gif')}}" alt="" srcset="">
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fetch All Employee
        setTimeout(() => {
            fetchAllEmployees();
        }, 2500);
        function fetchAllEmployees() {
            $.ajax({
                url: '{{ route('fetchAll') }}',
                method: 'get',
                success: function(res){
                    $('#show_all_employees').html(res);
                    $('table').DataTable({
                        order: [0, 'desc'],
                    });
                }
            })
        }

        // Adding New Employees
        $('#add_employee_form').submit(function(e){
            e.preventDefault();
            // var myModal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
            const fd = new FormData(this);
            $('#add_employee_btn').text('Adding...');
            $.ajax({
                url: '{{ route('store') }}',
                method: 'post',
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                success: function(res){
                    if (res.status == 200) {
                        Swal.fire(
                            'Employee',
                            'Added Sucessfully',
                            'success'
                        )
                        fetchAllEmployees();
                        $('#add_employee_form').trigger('reset');
                        $('#add_employee_btn').text('Add Employee');
                        $('#addEmployeeModal').modal('hide');
                    }else if (res.status == 300) {
                        Swal.fire(
                            'Employee',
                            'Not Added Sucessfully',
                            'error'
                        )
                    } 
                }
            });
        });

        // update employee ajax 
        $('#edit_employee_form').submit(function(e){
            e.preventDefault();
            const fd  = new FormData(this);
            $('#edit_employee_btn').text('Updating....');
            $.ajax({
                url: '{{ route('update') }}',
                method: 'post',
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                success: function(res){
                    console.log(res);
                    if (res.status == 200) {
                        Swal.fire(
                            'Employee',
                            'Updated Successfully',
                            'success'
                        );
                        fetchAllEmployees();
                        $('#edit_employee_form').trigger('reset');
                        $('#editEmployeeModal').modal('hide');
                        $('#edit_employee_btn').text('Update Employee');

                    }else if (res.status == 300) {
                        Swal.fire(
                            'Employee',
                            'Not Updated',
                            'error'
                        );
                        $('#edit_employee_btn').text('Update');
                    } 
                }
            })
        })

        // edit employee data ajax
        $(document).on('click', '.editIcon', function(e){
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res){
                    console.log(res);
                    $('#fname').val(res.first_name);
                    $('#lname').val(res.last_name);
                    $('#email').val(res.email);
                    $('#phone').val(res.phone);
                    $('#post').val(res.post);
                    $('#avatar').html(`<img src="storage/images/${res.avatar}" class="img-thumbnail">`);
                    $('#emp_id').val(res.id);
                    $('#emp_avatar').val(res.avatar);
                }
            })

        })


        // delete employee data 
        $(document).on('click', '.deleteIcon', function(e){
            e.preventDefault();
            let id = $(this).attr('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('delete') }}',
                    method: 'post',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res){
                       console.log(res);
                       Swal.fire(
                           'Deleted',
                           'Employee Data',
                           'success'
                       )  
                       fetchAllEmployees();
                    }
                })
            }
            })
        })
        
    </script>
</body>
</html>