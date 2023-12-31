<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    private string $path = 'be/product/';

    public function index()
    {
        $data = [
            'title'             => 'Manage Treatment',
            'sub_title'         => 'List',
            'menu_active'       => 'treatment',
        ];

        return view('pages.treatment.index', $data);
    }

    public function create()
    {
        $data = [
            'title'             => 'Create Treatment',
            'sub_title'         => 'List',
            'menu_active'       => 'treatment',
        ];
        $category = MyHelper::get('be/product-category');
        if (isset($category['status']) && $category['status'] == 'success') {
            $data['categorys'] = $category['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.']);
        }
        return view('pages.treatment.create', $data);
    }

    public function store(Request $request)
    {
        $payload = $request->except('_token');
        $save = MyHelper::post($this->path, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('treatment')->withSuccess(['New Treatment successfully added.']);
        } else {
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Treatment',
            'sub_title'         => 'Detail',
        ];
        $treatment = MyHelper::get($this->path . $id);
        $category = MyHelper::get('be/product-category');
        if (isset($treatment['status']) && $treatment['status'] == "success" && isset($category['status']) && $category['status'] == 'success') {
            $data['detail'] = $treatment['result'];
            $data['categorys'] = $category['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.treatment.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = $request->except('_token');
        $save = MyHelper::patch($this->path . $id, $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('treatment')->withSuccess(['CMS Treatment detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors(!empty($save['error']) ? $save['error'] : $save['message'])->withInput();
        }
    }

    public function deleteTreatment($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Treatment deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
