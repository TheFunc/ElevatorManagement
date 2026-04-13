<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Device;
use App\Models\Files;
use App\Models\Campus;

class ElevatorController extends Controller
{
    // 系统管理页面
    public function ledger()
    {
        // 获取电梯列表
        $devices = Device::latest()->get();
        
        // 统计各类资料数量
        $fileStats = [
            'prepare'      => Files::where('type', 'prepare')->count(),
            'maintenance'  => Files::where('type', 'maintenance')->count(),
            'inspection'   => Files::where('type', 'inspection')->count(),
            'fault'        => Files::where('type', 'fault')->count(),
            'repair'       => Files::where('type', 'repair')->count(),
            'accident'     => Files::where('type', 'accident')->count(),
            'rescue'       => Files::where('type', 'rescue')->count(),
        ];

        return view('elevator.ledger', compact('devices', 'fileStats'));
    }

    public function maintenance(Request $request)
    {
        $query = Files::where('type', 'maintenance')->latest();
        
        // 搜索功能
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('desc', 'like', "%{$keyword}%");
            });
        }
        
        $files = $query->paginate(10);
        
        return view('elevator.maintenance', compact('files'));
    }

    public function warning()
    {
        return view('elevator.warning');
    }

    // 资料管理页面
    public function device()
    {
        $campuses = Campus::all();
        return view('data.device', compact('campuses'));
    }

    public function prepare()
    {
        return view('data.prepare');
    }

    public function maintenanceData()
    {
        return view('data.maintenance');
    }

    public function inspection()
    {
        return view('data.inspection');
    }

    public function fault()
    {
        return view('data.fault');
    }

    public function repair()
    {
        return view('data.repair');
    }

    public function accident()
    {
        return view('data.accident');
    }

    public function rescue()
    {
        return view('data.rescue');
    }

    public function query(Request $request)
    {
        $query = Files::latest();
        
        // 关键词搜索
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('desc', 'like', "%{$keyword}%");
            });
        }
        
        // 类型过滤
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        $files = $query->paginate(15);
        
        // 文件类型配置
        $fileTypes = [
            'prepare'      => ['name' => '准备资料', 'color' => 'blue', 'icon' => 'ri-file-copy-line'],
            'maintenance'  => ['name' => '维保资料', 'color' => 'green', 'icon' => 'ri-tools-line'],
            'inspection'   => ['name' => '巡检资料', 'color' => 'yellow', 'icon' => 'ri-checkbox-circle-line'],
            'fault'        => ['name' => '故障记录', 'color' => 'red', 'icon' => 'ri-error-warning-line'],
            'repair'       => ['name' => '维修记录', 'color' => 'purple', 'icon' => 'ri-hammer-line'],
            'accident'     => ['name' => '事故记录', 'color' => 'orange', 'icon' => 'ri-alarm-warning-line'],
            'rescue'       => ['name' => '救援演练', 'color' => 'teal', 'icon' => 'ri-lifebuoy-line'],
        ];
        
        return view('data.query', compact('files', 'fileTypes'));
    }

    /**
     * 显示添加电梯表单
     */
    public function createDevice()
    {
        $campuses = Campus::all();
        return view('elevator.create', compact('campuses'));
    }

    /**
     * 保存电梯信息
     */
    public function storeDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|string|max:100|unique:devices',
            'Position' => 'required|string|max:255',
            'desc' => 'required|string|max:500',
            'Campus' => 'required|string|max:100',
            'building' => 'required|string|max:100',
        ], [
            'number.required' => '请填写电梯编号',
            'number.unique' => '该电梯编号已存在',
            'Position.required' => '请填写电梯位置',
            'desc.required' => '请填写电梯描述',
            'Campus.required' => '请填写校区',
            'building.required' => '请填写楼号',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Device::create($request->all());

        return redirect()->route('elevator.ledger')->with('success', '电梯添加成功！');
    }

    /**
     * 查看电梯详情
     */
    public function showDevice($id)
    {
        $device = Device::findOrFail($id);
        return view('elevator.show', compact('device'));
    }

    /**
     * 显示编辑电梯表单
     */
    public function editDevice($id)
    {
        $device = Device::findOrFail($id);
        $campuses = Campus::all();
        return view('elevator.edit', compact('device', 'campuses'));
    }

    /**
     * 更新电梯信息
     */
    public function updateDevice(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'number' => 'required|string|max:100|unique:devices,number,'.$id,
            'Position' => 'required|string|max:255',
            'desc' => 'required|string|max:500',
            'Campus' => 'required|string|max:100',
            'building' => 'required|string|max:100',
        ], [
            'number.required' => '请填写电梯编号',
            'number.unique' => '该电梯编号已存在',
            'Position.required' => '请填写电梯位置',
            'desc.required' => '请填写电梯描述',
            'Campus.required' => '请填写校区',
            'building.required' => '请填写楼号',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $device->update($request->all());

        return redirect()->route('device.show', $id)->with('success', '电梯信息更新成功！');
    }

    /**
     * 查看文件详情
     */
    public function showFile($id)
    {
        $file = Files::findOrFail($id);
        
        // 文件类型配置
        $fileTypes = [
            'prepare'      => ['name' => '准备资料', 'color' => 'blue', 'icon' => 'ri-file-copy-line'],
            'maintenance'  => ['name' => '维保资料', 'color' => 'green', 'icon' => 'ri-tools-line'],
            'inspection'   => ['name' => '巡检资料', 'color' => 'yellow', 'icon' => 'ri-checkbox-circle-line'],
            'fault'        => ['name' => '故障记录', 'color' => 'red', 'icon' => 'ri-error-warning-line'],
            'repair'       => ['name' => '维修记录', 'color' => 'purple', 'icon' => 'ri-hammer-line'],
            'accident'     => ['name' => '事故记录', 'color' => 'orange', 'icon' => 'ri-alarm-warning-line'],
            'rescue'       => ['name' => '救援演练', 'color' => 'teal', 'icon' => 'ri-lifebuoy-line'],
        ];
        
        return view('file.show', compact('file', 'fileTypes'));
    }

    /**
     * 校区管理页面
     */
    public function campus()
    {
        $campuses = Campus::latest()->get();
        return view('campus.index', compact('campuses'));
    }

    /**
     * 保存校区
     */
    public function storeCampus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Campus' => 'required|string|max:100|unique:campuses',
            'description' => 'required|string|max:500',
        ], [
            'Campus.required' => '请填写校区名称',
            'Campus.unique' => '该校区已存在',
            'description.required' => '请填写校区描述',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Campus::create($request->all());

        return redirect()->route('campus.index')->with('success', '校区添加成功！');
    }

    /**
     * 删除校区
     */
    public function deleteCampus($id)
    {
        $campus = Campus::findOrFail($id);
        $campus->delete();
        
        return redirect()->route('campus.index')->with('success', '校区删除成功！');
    }
}
