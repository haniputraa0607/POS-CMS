<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorSchedule\Request as DoctorScheduleRequest;
use App\Lib\MyHelper;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleDate;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiDoctorScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DoctorSchedule::query()
            ->with('user')
            ->select('doctor_schedules.*') // Mengambil semua kolom dari tabel doctor_schedules
            ->leftJoin('users', 'doctor_schedules.user_id', '=', 'users.id'); // Bergabungkan dengan tabel users

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('schedule_month', function ($row) {
                return MyHelper::getMonth($row->schedule_month);
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->name; // Kolom 'user_name' akan digunakan untuk pencarian
            })
            ->addColumn('outlet', function ($row) {
                return $row->outlet->name;
            })
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('doctor-schedule.detail', ['id' => $row->id]) . '">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </a>
                        <a  href="javascript:void(0)" class="btn btn-sm btn-danger" id="btn-delete" data-id="' . $row->id . '" data-name="' . $row->name . '">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>';
            })
            ->rawColumns(['action'])
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereRaw("users.name like ?", ["%$keyword%"]); // Konfigurasi pencarian kolom 'user_name'
            })
            ->make(true);
    }


    public function nameId(): JsonResponse
    {
        return $this->ok("success get DoctorShift", DoctorSchedule::with(['user', 'outlet'])->get()->map(function ($item) {
            return array_merge($item->toArray(), [
                'schedule_month' => MyHelper::getMonth($item->schedule_month),
            ]);
        }));
    }
    public function show(DoctorSchedule $doctorSchedule): JsonResponse
    {
        $doctorSchedule->user;
        $doctorSchedule->outlet;
        $doctorSchedule->dates;
        return $this->ok("succes", $doctorSchedule);
    }
    public function store(DoctorScheduleRequest $request): JsonResponse
    {
        $doctorSchedule = DoctorSchedule::create($request->all());
        $doctorScheduleId = $doctorSchedule->id;
        foreach ($request->schedule_date as $key) {
            DoctorScheduleDate::create([
                "doctor_schedule_id" => $doctorScheduleId,
                "date" => $request->schedule_year . '-' . $request->schedule_month . '-' . $key
            ]);
        }
        return $this->ok("succes", $doctorSchedule);
    }
    public function update(DoctorScheduleRequest $request, DoctorSchedule $doctorSchedule): JsonResponse
    {
        $doctorSchedule->update($request->all());
        $doctorSchedule->dates()->delete();
        foreach ($request->schedule_date as $key) {
            DoctorScheduleDate::create([
                "doctor_schedule_id" => $doctorSchedule->id, // Menggunakan $doctorSchedule->id untuk menghubungkannya dengan DoctorSchedule yang baru
                "date" => $request->schedule_year . '-' . $request->schedule_month . '-' . $key
            ]);
        }
        return $this->ok("success", $doctorSchedule);
    }

    public function destroy(DoctorSchedule $doctorSchedule): JsonResponse
    {
        $doctorSchedule->delete();
        return $this->ok("succes", $doctorSchedule);
    }
}
