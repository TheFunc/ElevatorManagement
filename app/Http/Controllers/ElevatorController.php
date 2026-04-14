<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Device;
use App\Models\Files;
use App\Models\Campus;
use App\Models\Maintenance;
use App\Models\RepairOrder;
use Illuminate\Support\Facades\Storage;

class ElevatorController extends Controller
{
    // 系统管理页面
    public function ledger(Request $request)
    {
        // 获取电梯列表
        $query = Device::query();
        
        // 关键词搜索
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('number', 'like', "%{$keyword}%")
                  ->orWhere('name', 'like', "%{$keyword}%")
                  ->orWhere('Model', 'like', "%{$keyword}%")
                  ->orWhere('Position', 'like', "%{$keyword}%");
            });
        }
        
        // 状态过滤
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $devices = $query->latest()->get();
        
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

    public function warning(Request $request)
    {
        // 更新状态：自动标记已过期的记录
        Maintenance::whereDate('next_inspection_date', '<', now()->toDateString())
            ->where('status', 0)
            ->update(['status' => 2]);
        
        // 获取预警记录并处理查询
        $query = Maintenance::query();
        
        // 关键词搜索
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('inspection_devices', 'like', "%{$keyword}%")
                  ->orWhere('responsible_person', 'like', "%{$keyword}%")
                  ->orWhere('contact_phone', 'like', "%{$keyword}%");
            });
        }
        
        // 状态过滤
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $maintenances = $query->orderBy('next_inspection_date', 'asc')->get();
        
        // 获取电梯列表
        $devices = Device::all();
        
        // 计算今日需要预警的记录
        $todayWarning = $maintenances->filter(function($item) {
            return $item->next_inspection_date->isToday() && $item->status == 0;
        });
        
        return view('elevator.warning', compact('maintenances', 'todayWarning', 'devices'));
    }

    /**
     * 更新年检状态
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }
        
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->update(['status' => $request->status]);
        
        return back()->with('success', '状态更新成功！');
    }

    /**
     * 添加年检记录
     */
    public function storeMaintenance(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }
        $validator = Validator::make($request->all(), [
            'inspection_devices' => 'required|string',
            'next_inspection_date' => 'required|date',
            'responsible_person' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'remark' => 'nullable|string|max:500',
        ], [
            'inspection_devices.required' => '请选择电梯',
            'next_inspection_date.required' => '请选择年检日期',
            'responsible_person.required' => '请填写负责人',
            'contact_phone.required' => '请填写联系电话',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Maintenance::create([
            'inspection_devices' => $request->inspection_devices,
            'next_inspection_date' => $request->next_inspection_date,
            'responsible_person' => $request->responsible_person,
            'contact_phone' => $request->contact_phone,
            'remark' => $request->remark,
            'status' => 0,
        ]);

        return redirect()->route('elevator.warning')->with('success', '年检记录添加成功！');
    }

    // 资料管理页面
    public function device()
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }
        
        $campuses = Campus::all();
        return view('data.device', compact('campuses'));
    }


    public function upload()
    {
        return view('data.upload');
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
     * 保存电梯信息
     */
    public function storeDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|string|max:100|unique:devices',
            'register' => 'nullable|string|max:100',
            'FactorySerial' => 'nullable|string|max:100',
            'name' => 'nullable|string|max:100',
            'Model' => 'nullable|string|max:100',
            'Manufacturer' => 'nullable|string|max:100',
            'status' => 'nullable|integer|in:0,1',
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
            'register' => 'nullable|string|max:100',
            'FactorySerial' => 'nullable|string|max:100',
            'name' => 'nullable|string|max:100',
            'Model' => 'nullable|string|max:100',
            'Manufacturer' => 'nullable|string|max:100',
            'status' => 'nullable|integer|in:0,1',
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
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }
        
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

    /**
     * 电梯单管理
     */
    public function repairOrders()
    {
        $orders = RepairOrder::latest()->get();
        return view('repair.orders', compact('orders'));
    }

    /**
     * 上传电梯单
     */
    public function uploadRepairOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ], [
            'title.required' => '请填写标题',
            'images.*.required' => '请选择图片文件',
            'images.*.image' => '仅支持图片格式',
            'images.*.max' => '单张图片大小不能超过10MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $images = $request->file('images');
        $description = $request->description ?? '';

        foreach ($images as $image) {
            $path = $image->store('repair_orders', 'public');
            
            RepairOrder::create([
                'title' => $request->title,
                'description' => $description,
                'path' => 'storage/' . $path,
                'time' => now(),
            ]);
        }

        return redirect()->route('repair.orders')->with('success', '电梯单上传成功！共上传 ' . count($images) . ' 张图片');
    }

    /**
     * 删除电梯单
     */
    public function deleteRepairOrder($id)
    {
        $order = RepairOrder::findOrFail($id);
        
        // 删除物理文件
        $filePath = public_path($order->path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $order->delete();
        
        return redirect()->route('repair.orders')->with('success', '电梯单已删除');
    }

    /**
     * 下载电梯单
     */
    public function downloadRepairOrder($id)
    {
        $order = RepairOrder::findOrFail($id);
        $filePath = public_path($order->path);
        
        if (!file_exists($filePath)) {
            abort(404, '文件不存在');
        }
        
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $filename = $order->title . '.' . $extension;
        
        return response()->download($filePath, $filename);
    }
}
