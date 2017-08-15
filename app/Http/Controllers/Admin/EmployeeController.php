<?php

namespace App\Http\Controllers\Admin;

use App\Employees\Employee;
use App\Admins\Requests\CreateEmployeeRequest;
use App\Admins\Requests\UpdateEmployeeRequest;
use App\Employees\Repositories\EmployeeRepository;
use App\Employees\Repositories\Interfaces\EmployeeRepositoryInterface;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    private $employeeRepo;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepo = $employeeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->employeeRepo->listEmployees('created_at', 'desc');

        return view('admin.employees.list', [
            'employees' => $this->employeeRepo->paginateArrayResults($list)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.employees.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateEmployeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEmployeeRequest $request)
    {
        $this->employeeRepo->createEmployee($request->all());

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $employee = $this->employeeRepo->findEmployeeById($id);
        return view('admin.employees.show', ['employee' => $employee]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $employee = $this->employeeRepo->findEmployeeById($id);
        return view('admin.employees.edit', ['employee' => $employee]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = $this->employeeRepo->findEmployeeById($id);

        $update = new EmployeeRepository($employee);
        $update->updateEmployee($request->all());

        $request->session()->flash('message', 'Update successful');
        return redirect()->route('employees.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->employeeRepo->delete($id);

        request()->session()->flash('message', 'Delete successful');
        return redirect()->route('employees.index');
    }
}
