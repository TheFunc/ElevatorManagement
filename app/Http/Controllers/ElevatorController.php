<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\Files;
use App\Models\Campus;
use App\Models\Maintenance;
use App\Models\RepairOrder;
use App\Models\VideoType;
use App\Models\VideoInfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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

        // 排序处理
        if ($request->has('sort') && $request->has('order')) {
            $sort = $request->sort;
            $order = $request->order == 'desc' ? 'desc' : 'asc';
            if (in_array($sort, ['number', 'name'])) {
                $query->orderBy($sort, $order);
            }
        } else {
            $query->latest();
        }
        
        $devices = $query->get();
        
        // 获取年检信息
        $checkNumbers = \App\Models\Check::pluck('next_check_at', 'number');
        
        // 统计各类资料数量
        $fileStats = [
            'prepare'      => Files::where('type', 'prepare')->count(),
            'maintenance'  => Files::where('type', 'maintenance')->count(),
            'inspection'   => Files::where('type', 'inspection')->count(),
            'fault'        => Files::where('type', 'fault')->count(),
            'repair'       => Files::where('type', 'repair')->count(),
            'accident'     => Files::where('type', 'accident')->count(),
            'rescue'       => Files::where('type', 'rescue')->count(),
            'annual_check' => Files::where('type', 'annual_check')->count(),
        ];

        return view('elevator.ledger', compact('devices', 'fileStats', 'checkNumbers'));
    }

    public function maintenance(Request $request)
    {
        $query = Files::where('type', 'maintenance');
        
        // 排序处理
        if ($request->has('sort') && $request->has('order')) {
            $order = $request->order == 'asc' ? 'asc' : 'desc';
            $query->orderBy('created_at', $order);
        } else {
            $query->latest();
        }
        
        // 搜索功能
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('desc', 'like', "%{$keyword}%");
            });
        }
        
        $files = $query->paginate(10)->appends($request->all());
        
        return view('elevator.maintenance', compact('files'));
    }

    public function warning(Request $request)
    {
        // 获取年检记录并处理查询
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

        // 排序处理
        if ($request->has('sort') && $request->has('order')) {
            $order = $request->order == 'desc' ? 'desc' : 'asc';
            $query->orderBy('status', $order);
        } else {
            $query->orderBy('next_inspection_date', 'asc');
        }
        
        $maintenances = $query->get();
        
        // 获取电梯列表
        $devices = Device::all();
        
        // 获取用户列表
        $users = \App\Models\User::all();
        
        // 今日预警记录已禁用
        $todayWarning = collect();
        
        return view('elevator.warning', compact('maintenances', 'todayWarning', 'devices', 'users'));
    }

    /**
     * 更新年检状态
     */
    public function updateStatus(Request $request, $id)
    {
        $maintenance = Maintenance::findOrFail($id);
        
        if (Auth::user()->role != 1 && Auth::user()->name != $maintenance->responsible_person) {
            abort(403, '只有负责人或管理员才能进行此操作');
        }
        
        $maintenance->update(['status' => $request->status]);
        
        return back()->with('success', '状态更新成功！');
    }

    /**
     * 删除年检记录
     */
    public function deleteMaintenance($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员才能删除');
        }
        
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();
        
        return redirect()->route('elevator.warning')->with('success', '年检记录已删除');
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
            'contact_phone' => 'nullable|string|max:20',
            'remark' => 'nullable|string|max:500',
        ], [
            'inspection_devices.required' => '请选择电梯',
            'next_inspection_date.required' => '请选择年检日期',
            'responsible_person.required' => '请选择负责人',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 自动获取用户手机号
        $user = \App\Models\User::where('name', $request->responsible_person)->first();
        $contact_phone = $request->contact_phone ?? ($user->phone ?? '');
        
        Maintenance::create([
            'inspection_devices' => $request->inspection_devices,
            'next_inspection_date' => $request->next_inspection_date,
            'responsible_person' => $request->responsible_person,
            'contact_phone' => $contact_phone,
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
        $query = Files::query();
        
        // 排序处理 - 默认按事件时间降序（从晚到早，最新在前）
        $order = $request->get('order', 'desc') == 'desc' ? 'desc' : 'asc';
        
        // 按 desc 字段中 JSON 的 event_time 排序（与页面显示一致），无 event_time 时回退到 created_at
        $query->orderByRaw("COALESCE(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`desc`, '$.event_time')), ''), created_at) {$order}");
        
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
        
        $files = $query->paginate(10)->appends($request->all());
        
        // 文件类型配置
        $fileTypes = [
            'prepare'      => ['name' => '准用资料', 'color' => 'blue', 'icon' => 'ri-file-copy-line'],
            'maintenance'  => ['name' => '维保资料', 'color' => 'green', 'icon' => 'ri-tools-line'],
            'inspection'   => ['name' => '巡检资料', 'color' => 'yellow', 'icon' => 'ri-checkbox-circle-line'],
            'fault'        => ['name' => '故障记录', 'color' => 'red', 'icon' => 'ri-error-warning-line'],
            'repair'       => ['name' => '维修记录', 'color' => 'purple', 'icon' => 'ri-hammer-line'],
            'accident'     => ['name' => '事故记录', 'color' => 'orange', 'icon' => 'ri-alarm-warning-line'],
            'rescue'       => ['name' => '救援演练', 'color' => 'teal', 'icon' => 'ri-lifebuoy-line'],
            'annual_check' => ['name' => '年检资料', 'color' => 'cyan', 'icon' => 'ri-calendar-check-line'],
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
            'status' => 'nullable|integer|in:0,1,2',
            'Position' => 'required|string|max:255',
            'desc' => 'required|string|max:500',
            'Campus' => 'required|string|max:100',
            'building' => 'required|string|max:100',
            'next_check_at' => 'nullable|date',
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

        $device = Device::create($request->all());

        // 如果设置了年检时间，同步写入 checks 表
        if ($request->filled('next_check_at')) {
            \App\Models\Check::create([
                'number' => $device->number,
                'next_check_at' => $request->next_check_at,
            ]);
        }

        return redirect()->route('elevator.ledger')->with('success', '电梯添加成功！');
    }

    /**
     * 查看电梯详情
     */
    public function showDevice($id)
    {
        $device = Device::findOrFail($id);
        $check = \App\Models\Check::where('number', $device->number)->first();
        return view('elevator.show', compact('device', 'check'));
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
            'status' => 'nullable|integer|in:0,1,2',
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
     * 更新年检时间
     */
    public function updateCheckDate(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $request->validate([
            'next_check_at' => 'nullable|date',
        ]);

        if ($request->filled('next_check_at')) {
            // 更新或创建年检记录
            \App\Models\Check::updateOrCreate(
                ['number' => $device->number],
                ['next_check_at' => $request->next_check_at]
            );
        } else {
            // 清空年检时间
            \App\Models\Check::where('number', $device->number)->delete();
        }

        return redirect()->route('device.show', $id)->with('success', '年检时间更新成功！');
    }

    /**
     * 查看文件详情
     */
    public function showFile($id)
    {
        $file = Files::findOrFail($id);
        
        // 文件类型配置
        $fileTypes = [
            'prepare'      => ['name' => '准用资料', 'color' => 'blue', 'icon' => 'ri-file-copy-line'],
            'maintenance'  => ['name' => '维保资料', 'color' => 'green', 'icon' => 'ri-tools-line'],
            'inspection'   => ['name' => '巡检资料', 'color' => 'yellow', 'icon' => 'ri-checkbox-circle-line'],
            'fault'        => ['name' => '故障记录', 'color' => 'red', 'icon' => 'ri-error-warning-line'],
            'repair'       => ['name' => '维修记录', 'color' => 'purple', 'icon' => 'ri-hammer-line'],
            'accident'     => ['name' => '事故记录', 'color' => 'orange', 'icon' => 'ri-alarm-warning-line'],
            'rescue'       => ['name' => '救援演练', 'color' => 'teal', 'icon' => 'ri-lifebuoy-line'],
            'annual_check' => ['name' => '年检资料', 'color' => 'cyan', 'icon' => 'ri-calendar-check-line'],
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
     * 上传电梯单
     */
    public function uploadRepairOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200|unique:repair_orders,title',
            'description' => 'nullable|string|max:500',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'customUploadTime' => 'nullable|date',
        ], [
            'title.required' => '请填写标题',
            'title.unique' => '该标题已存在，请使用不同的标题',
            'images.*.required' => '请选择图片文件',
            'images.*.image' => '仅支持图片格式',
            'images.*.max' => '单张图片大小不能超过10MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $images = $request->file('images');
        $description = $request->description ?? '';
        $groupId = time();

        // 处理自定义上传时间
        $uploadTime = $request->filled('customUploadTime') ? $request->customUploadTime : now();

        foreach ($images as $index => $image) {
            $path = $image->store('repair_orders', 'public');
            
            RepairOrder::create([
                'title' => $request->title,
                'description' => $description,
                'path' => 'storage/' . $path,
                'time' => $uploadTime,
                'group_id' => $groupId,
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('repair.orders')->with('success', '电梯单上传成功！共上传 ' . count($images) . ' 张图片，已创建为分组');
    }

    /**
     * 删除电梯单
     */
    public function deleteRepairOrder($id)
    {
        $order = RepairOrder::findOrFail($id);
        
        // 如果是分组，删除整个分组所有图片
        if($order->group_id) {
            $groupOrders = RepairOrder::where('group_id', $order->group_id)->get();
            
            foreach($groupOrders as $item) {
                $filePath = public_path($item->path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $item->delete();
            }
            
            return redirect()->route('repair.orders')->with('success', "电梯单分组已删除，共删除 {$groupOrders->count()} 张图片");
        } else {
            // 单张删除
            $filePath = public_path($order->path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $order->delete();
            
            return redirect()->route('repair.orders')->with('success', '电梯单已删除');
        }
    }

    /**
     * 下载电梯单
     */
    public function downloadRepairOrder($id)
    {
        $order = RepairOrder::findOrFail($id);
        
        // 如果是分组，打包所有图片
        if($order->group_id) {
            $groupOrders = RepairOrder::where('group_id', $order->group_id)->get();
            
            $zip = new \ZipArchive();
            $zipFileName = $order->title . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);
            
            // 创建临时目录
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                foreach($groupOrders as $index => $item) {
                    $filePath = public_path($item->path);
                    if (file_exists($filePath)) {
                        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                        $zip->addFile($filePath, $order->title . '_' . ($index + 1) . '.' . $extension);
                    }
                }
                $zip->close();
                
                return response()->download($zipPath)->deleteFileAfterSend(true);
            } else {
                return back()->with('error', '创建压缩包失败，请检查Zip扩展是否开启');
            }
        } else {
            // 单张下载
            $filePath = public_path($order->path);
            
            if (!file_exists($filePath)) {
                abort(404, '文件不存在');
            }
            
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $filename = $order->title . '.' . $extension;
            
            return response()->download($filePath, $filename);
        }
    }

    /**
     * 视频管理页面
     */
    public function videoIndex()
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        $videoTypes = VideoType::latest()->get();
        return view('video.index', compact('videoTypes'));
    }

    /**
     * 视频预览页面
     */
    public function videoPreview(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }

        // 获取所有视频类型
        $videoTypes = VideoType::latest()->get();

        // 查询视频数据
        $query = VideoInfo::query();

        // 关键词搜索（按视频组名）
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where('videoGroup', 'like', "%{$keyword}%");
        }

        // 视频类型过滤
        if ($request->has('videoType') && $request->videoType != '') {
            $query->where('videoType', $request->videoType);
        }

        // 按分组聚合，每组只取第一条记录（用于显示封面和基本信息）
        $groupedVideos = $query->latest()
            ->get()
            ->groupBy('videoGroup')
            ->map(function($group) {
                return $group->first();
            })
            ->values();

        return view('video.preview', compact('groupedVideos', 'videoTypes'));
    }

    /**
     * 电梯单管理
     */
    public function repairOrders(Request $request)
    {
        $query = RepairOrder::query();
        
        // 关键词搜索
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }
        
        // 获取所有记录并按分组聚合
        $allOrders = $query->latest()
            ->get()
            ->groupBy('group_id')
            ->map(function($group) {
                $first = $group->first();
                $first->images = $group->sortBy('sort_order')->values();
                return $first;
            })
            ->values();
        
        // 手动分页：每页10条
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $allOrders->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allOrders->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        
        // 保持搜索参数
        $orders->appends($request->all());
            
        return view('repair.orders', compact('orders'));
    }
    
    /**
     * 视频组详情页面
     */
    public function videoGroupDetail($group)
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        
        $videos = VideoInfo::where('videoGroup', $group)->latest()->get();
        
        return view('video.group', compact('videos', 'group'));
    }
    

    /**
     * 增加视频页面
     */
    public function videoCreate()
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        $videoTypes = VideoType::latest()->get();
        return view('video.create', compact('videoTypes'));
    }

    /**
     * 处理视频批量上传
     */
    public function videoUpload(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $validator = Validator::make($request->all(), [
            'videoGroup' => 'required|string|max:100',
            'videoType' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'videos' => 'required',
            'videos.*' => 'required|file|mimes:mp4|max:512000',
        ], [
            'videoGroup.required' => '请输入视频文件夹名称',
            'videoType.required' => '请选择视频类型',
            'cover.required' => '请选择视频封面图片',
            'videos.required' => '请选择至少一个视频文件',
            'videos.*.mimes' => '仅支持MP4格式的视频文件',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $videoGroup = $request->videoGroup;
        $basePath = 'videos/' . $videoGroup;

        // 创建文件夹
        if (!Storage::exists($basePath)) {
            Storage::makeDirectory($basePath);
        }

        // 上传封面图片
        $coverFile = $request->file('cover');
        $coverName = time() . '_' . $coverFile->getClientOriginalName();
        $coverPath = $coverFile->storeAs($basePath, $coverName, 'public');

        // 处理每个视频文件
        $videos = $request->file('videos');
        $uploadCount = 0;

        foreach ($videos as $videoFile) {
            $videoName = time() . '_' . $videoFile->getClientOriginalName();
            $videoPath = $videoFile->storeAs($basePath, $videoName, 'public');

            VideoInfo::create([
                'coverPath' => 'storage/' . $coverPath,
                'videoPath' => 'storage/' . $videoPath,
                'videoType' => $request->videoType,
                'videoGroup' => $videoGroup,
                'description' => $request->description,
            ]);

            $uploadCount++;
        }

        return redirect()->route('video.create')->with('success', '视频上传成功！共上传 ' . $uploadCount . ' 个视频文件');
    }

    /**
     * 上传封面图片
     */
    public function uploadCover(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $request->validate([
            'videoGroup' => 'required|string|max:100',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $basePath = 'videos/' . $request->videoGroup;
        
        if (!Storage::exists($basePath)) {
            Storage::makeDirectory($basePath);
        }

        $coverFile = $request->file('cover');
        $coverName = time() . '_' . $coverFile->getClientOriginalName();
        $coverPath = $coverFile->storeAs($basePath, $coverName, 'public');

        return response()->json([
            'success' => true,
            'path' => 'storage/' . $coverPath
        ]);
    }

    /**
     * 单个上传视频
     */
    public function uploadSingleVideo(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $validator = Validator::make($request->all(), [
            'videoGroup' => 'required|string|max:100',
            'videoType' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'coverPath' => 'required|string',
            'video' => 'required|file|mimes:mp4|max:512000',
        ], [
            'video.required' => '请选择视频文件',
            'video.file' => '上传的文件无效',
            'video.mimes' => '仅支持 MP4 格式的视频文件',
            'video.max' => '视频文件大小不能超过 500MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            $basePath = 'videos/' . $request->videoGroup;
            
            // 确保目录存在
            if (!Storage::exists($basePath)) {
                Storage::makeDirectory($basePath);
            }
            
            $videoFile = $request->file('video');
            
            // 检查文件大小（额外验证）
            $maxSize = 512000 * 1024; // 512000 KB = 500 MB in bytes
            if ($videoFile->getSize() > $maxSize) {
                return response()->json([
                    'success' => false,
                    'error' => '视频文件大小超过限制（最大 500MB）'
                ], 422);
            }
            
            $videoName = time() . '_' . $videoFile->getClientOriginalName();
            $videoPath = $videoFile->storeAs($basePath, $videoName, 'public');

            VideoInfo::create([
                'coverPath' => $request->coverPath,
                'videoPath' => 'storage/' . $videoPath,
                'videoType' => $request->videoType,
                'videoGroup' => $request->videoGroup,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'path' => 'storage/' . $videoPath
            ]);
        } catch (\Exception $e) {
            Log::error('视频上传失败: ' . $e->getMessage(), [
                'file' => $request->file('video')?->getClientOriginalName(),
                'size' => $request->file('video')?->getSize(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => '视频上传失败: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 保存视频类型
     */
    public function storeVideoType(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:100|unique:video_types',
        ], [
            'type.required' => '请填写视频类型名称',
            'type.unique' => '该视频类型已存在',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        VideoType::create($request->all());

        return redirect()->route('video.index')->with('success', '视频类型添加成功！');
    }

    /**
     * 更新视频类型
     */
    public function updateVideoType(Request $request, $id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $videoType = VideoType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:100|unique:video_types,type,'.$id,
        ], [
            'type.required' => '请填写视频类型名称',
            'type.unique' => '该视频类型已存在',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $videoType->update($request->all());

        return redirect()->route('video.index')->with('success', '视频类型更新成功！');
    }

    /**
     * 删除视频组（删除该分组下所有视频）
     */
    public function deleteVideoGroup($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $video = VideoInfo::findOrFail($id);
        $videoGroup = $video->videoGroup;
        
        // 获取该分组下所有视频
        $videos = VideoInfo::where('videoGroup', $videoGroup)->get();
        
        // 删除所有物理文件
        foreach ($videos as $v) {
            // 删除视频文件
            if (Storage::exists(str_replace('storage/', 'public/', $v->videoPath))) {
                Storage::delete(str_replace('storage/', 'public/', $v->videoPath));
            }
        }
        
        // 删除封面文件（只需要删除一次）
        if (Storage::exists(str_replace('storage/', 'public/', $video->coverPath))) {
            Storage::delete(str_replace('storage/', 'public/', $video->coverPath));
        }
        
        // 批量删除数据库记录
        VideoInfo::where('videoGroup', $videoGroup)->delete();

        return redirect()->route('video.preview')->with('success', "视频组 '{$videoGroup}' 已删除，共删除 {$videos->count()} 个视频文件");
    }
    
    /**
     * 删除单个视频
     */
    public function deleteSingleVideo($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $video = VideoInfo::findOrFail($id);
        
        // 删除视频文件
        if (Storage::exists(str_replace('storage/', 'public/', $video->videoPath))) {
            Storage::delete(str_replace('storage/', 'public/', $video->videoPath));
        }
        
        // 检查是否是该分组最后一个视频，如果是同时删除封面
        $groupCount = VideoInfo::where('videoGroup', $video->videoGroup)->count();
        if ($groupCount <= 1) {
            if (Storage::exists(str_replace('storage/', 'public/', $video->coverPath))) {
                Storage::delete(str_replace('storage/', 'public/', $video->coverPath));
            }
        }
        
        $video->delete();

        return back()->with('success', '视频已删除！');
    }

    /**
     * 删除视频类型
     */
    public function deleteVideoType($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $videoType = VideoType::findOrFail($id);
        $videoType->delete();

        return redirect()->route('video.index')->with('success', '视频类型删除成功！');
    }

    /**
     * 图文类型管理页面
     */
    public function imageTextTypes()
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        
        $imageTypes = \App\Models\ImageType::latest()->get();
        return view('image-text.types', compact('imageTypes'));
    }

    /**
     * 保存图文类型
     */
    public function storeImageTextType(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:100|unique:image_types',
        ], [
            'type.required' => '请填写图文类型名称',
            'type.unique' => '该图文类型已存在',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        \App\Models\ImageType::create($request->all());

        return redirect()->route('image-text.types')->with('success', '图文类型添加成功！');
    }

    /**
     * 更新图文类型
     */
    public function updateImageTextType(Request $request, $id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $imageType = \App\Models\ImageType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:100|unique:image_types,type,'.$id,
        ], [
            'type.required' => '请填写图文类型名称',
            'type.unique' => '该图文类型已存在',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $imageType->update($request->all());

        return redirect()->route('image-text.types')->with('success', '图文类型更新成功！');
    }

    /**
     * 删除图文类型
     */
    public function deleteImageTextType($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $imageType = \App\Models\ImageType::findOrFail($id);
        $imageType->delete();

        return redirect()->route('image-text.types')->with('success', '图文类型删除成功！');
    }

    /**
     * 图文预览页面
     */
    public function imageTextPreview(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }

        // 获取所有图片类型
        $imageTypes = \App\Models\ImageType::latest()->get();

        // 查询图片数据
        $query = \App\Models\ImageInfo::query();

        // 关键词搜索（按图片组名）
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where('imageGroup', 'like', "%{$keyword}%");
        }

        // 图片类型过滤
        if ($request->has('imageType') && $request->imageType != '') {
            $query->where('imageType', $request->imageType);
        }

        // 按分组聚合，每组只取第一条记录（用于显示封面和基本信息）
        $groupedImages = $query->latest()
            ->get()
            ->groupBy('imageGroup')
            ->map(function($group) {
                return $group->first();
            })
            ->values();

        return view('image-text.preview', compact('groupedImages', 'imageTypes'));
    }

    /**
     * 图片组详情页面
     */
    public function imageGroupDetail($group)
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        
        $images = \App\Models\ImageInfo::where('imageGroup', $group)->latest()->get();
        
        return view('image-text.group', compact('images', 'group'));
    }

    /**
     * 删除图片组（删除该分组下所有图片）
     */
    public function deleteImageGroup($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $image = \App\Models\ImageInfo::findOrFail($id);
        $imageGroup = $image->imageGroup;
        
        // 获取该分组下所有图片
        $images = \App\Models\ImageInfo::where('imageGroup', $imageGroup)->get();
        
        // 删除所有物理文件
        foreach ($images as $img) {
            // 删除图片文件
            if (Storage::exists(str_replace('storage/', 'public/', $img->imagePath))) {
                Storage::delete(str_replace('storage/', 'public/', $img->imagePath));
            }
        }
        
        // 删除封面文件（只需要删除一次）
        if (Storage::exists(str_replace('storage/', 'public/', $image->coverPath))) {
            Storage::delete(str_replace('storage/', 'public/', $image->coverPath));
        }
        
        // 批量删除数据库记录
        \App\Models\ImageInfo::where('imageGroup', $imageGroup)->delete();

        return redirect()->route('image-text.preview')->with('success', "图片组 '{$imageGroup}' 已删除，共删除 {$images->count()} 个图片文件");
    }
    
    /**
     * 删除单个图片
     */
    public function deleteSingleImage($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $image = \App\Models\ImageInfo::findOrFail($id);
        
        // 删除图片文件
        if (Storage::exists(str_replace('storage/', 'public/', $image->imagePath))) {
            Storage::delete(str_replace('storage/', 'public/', $image->imagePath));
        }
        
        // 检查是否是该分组最后一个图片，如果是同时删除封面
        $groupCount = \App\Models\ImageInfo::where('imageGroup', $image->imageGroup)->count();
        if ($groupCount <= 1) {
            if (Storage::exists(str_replace('storage/', 'public/', $image->coverPath))) {
                Storage::delete(str_replace('storage/', 'public/', $image->coverPath));
            }
        }
        
        $image->delete();

        return back()->with('success', '图片已删除！');
    }

    /**
     * 增加图文页面
     */
    public function imageTextCreate()
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        
        $imageTypes = \App\Models\ImageType::latest()->get();
        return view('image-text.create', compact('imageTypes'));
    }

    /**
     * 上传图文封面
     */
    public function uploadImageCover(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $request->validate([
            'imageGroup' => 'required|string|max:100',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // 清理 imageGroup 名称，移除文件系统不允许的字符
        $safeGroupName = $this->sanitizeFileName($request->imageGroup);
        $basePath = 'images/' . $safeGroupName;
        
        if (!Storage::exists($basePath)) {
            Storage::makeDirectory($basePath);
        }

        $coverFile = $request->file('cover');
        $coverName = time() . '_' . $coverFile->getClientOriginalName();
        $coverPath = $coverFile->storeAs($basePath, $coverName, 'public');

        return response()->json([
            'success' => true,
            'path' => 'storage/' . $coverPath,
            'groupName' => $safeGroupName  // 返回清理后的组名
        ]);
    }

    /**
     * 单个上传图片
     */
    public function uploadSingleImage(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $request->validate([
            'imageGroup' => 'required|string|max:100',
            'imageType' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'coverPath' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:51200',
        ]);

        // 清理 imageGroup 名称，移除文件系统不允许的字符
        $safeGroupName = $this->sanitizeFileName($request->imageGroup);
        $basePath = 'images/' . $safeGroupName;
        
        $imageFile = $request->file('image');
        $imageName = time() . '_' . $imageFile->getClientOriginalName();
        $imagePath = $imageFile->storeAs($basePath, $imageName, 'public');

        \App\Models\ImageInfo::create([
            'coverPath' => $request->coverPath,
            'imagePath' => 'storage/' . $imagePath,
            'imageType' => $request->imageType,
            'imageGroup' => $request->imageGroup,  // 保存原始名称到数据库
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'path' => 'storage/' . $imagePath
        ]);
    }

    /**
     * 清理文件名，移除文件系统中不允许的字符
     */
    private function sanitizeFileName($name)
    {
        // Windows 不允许的字符: < > : " / \ | ? *
        // 替换为下划线或移除
        $sanitized = preg_replace('/[<>:"\/\\\\|?*]/', '_', $name);
        // 移除首尾空格和点
        $sanitized = trim($sanitized, " .");
        // 限制长度
        $sanitized = mb_substr($sanitized, 0, 100);
        // 如果为空，使用默认名称
        if (empty($sanitized)) {
            $sanitized = 'unnamed_group';
        }
        return $sanitized;
    }

    /**
     * 图文列表页面
     */
    public function imageTextIndex(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }

        $query = \App\Models\ImageText::with('creator')->orderBy('created_at', 'desc');

        // 关键词搜索
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // 模板类型过滤
        if ($request->has('is_template') && $request->is_template !== '') {
            $query->where('is_template', $request->is_template);
        }

        $imageTexts = $query->paginate(12);

        return view('image-text.index', compact('imageTexts'));
    }

    /**
     * 存储新图文
     */
    public function imageTextStore(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'layout_data' => 'required|array',
            'thumbnail' => 'nullable|string|max:500',
            'is_template' => 'boolean',
            'template_name' => 'nullable|string|max:255',
        ]);

        \App\Models\ImageText::create([
            'title' => $request->title,
            'description' => $request->description,
            'layout_data' => $request->layout_data,
            'thumbnail' => $request->thumbnail,
            'is_template' => $request->is_template ?? false,
            'template_name' => $request->template_name,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('image-text.index')
            ->with('success', '图文创建成功！');
    }

    /**
     * 编辑图文页面
     */
    public function imageTextEdit($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }

        $imageText = \App\Models\ImageText::findOrFail($id);
        
        return view('image-text.editor', compact('imageText'));
    }

    /**
     * 更新图文
     */
    public function imageTextUpdate(Request $request, $id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $imageText = \App\Models\ImageText::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'layout_data' => 'required|array',
            'thumbnail' => 'nullable|string|max:500',
            'is_template' => 'boolean',
            'template_name' => 'nullable|string|max:255',
        ]);

        $imageText->update([
            'title' => $request->title,
            'description' => $request->description,
            'layout_data' => $request->layout_data,
            'thumbnail' => $request->thumbnail,
            'is_template' => $request->is_template ?? false,
            'template_name' => $request->template_name,
        ]);

        return redirect()->route('image-text.index')
            ->with('success', '图文更新成功！');
    }

    /**
     * 删除图文
     */
    public function imageTextDelete($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $imageText = \App\Models\ImageText::findOrFail($id);
        $imageText->delete();

        return redirect()->route('image-text.index')
            ->with('success', '图文已删除！');
    }

    /**
     * 查看图文详情
     */
    public function imageTextShow($id)
    {
        $imageText = \App\Models\ImageText::with('creator')->findOrFail($id);
        
        return view('image-text.show', compact('imageText'));
    }

    /**
     * 获取图文API数据（用于编辑器）
     */
    public function imageTextApi($id)
    {
        $imageText = \App\Models\ImageText::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $imageText
        ]);
    }

    /**
     * 文本类型管理页面
     */
    public function textManagementTypes()
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        
        // TODO: 实现文本类型管理功能
        return view('text-management.types');
    }

    /**
     * 文本预览页面
     */
    public function textManagementPreview(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }

        // 获取所有文本类型
        $textTypes = \App\Models\TextType::latest()->get();

        // 查询文本数据
        $query = \App\Models\TextInfo::query();

        // 关键词搜索（按文本内容）
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('TextContent', 'like', "%{$keyword}%")
                  ->orWhere('TextType', 'like', "%{$keyword}%");
            });
        }

        // 文本类型过滤
        if ($request->has('textType') && $request->textType != '') {
            $query->where('TextType', $request->textType);
        }

        // 获取所有文本数据，按创建时间倒序排列
        $texts = $query->latest()->paginate(20);

        return view('text-management.preview', compact('texts', 'textTypes'));
    }

    /**
     * 添加文本页面
     */
    public function textManagementCreate()
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        
        // 获取所有文本类型供选择
        $textTypes = \App\Models\TextType::latest()->get();
        return view('text-management.create', compact('textTypes'));
    }

    /**
     * 保存文本
     */
    public function storeTextInfo(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $validator = Validator::make($request->all(), [
            'TextType' => 'required|string|max:100',
            'TextGroup' => 'nullable|string|max:200',
            'TextContent' => 'nullable|string',
        ], [
            'TextType.required' => '请选择文本类型',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        \App\Models\TextInfo::create($request->all());

        return redirect()->route('text-management.preview')->with('success', '文本添加成功！');
    }

    /**
     * 删除文本
     */
    public function deleteTextInfo($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $textInfo = \App\Models\TextInfo::findOrFail($id);
        $textInfo->delete();

        return redirect()->route('text-management.preview')->with('success', '文本删除成功！');
    }

    /**
     * 编辑文本页面
     */
    public function editTextInfo($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        
        $textInfo = \App\Models\TextInfo::findOrFail($id);
        $textTypes = \App\Models\TextType::latest()->get();
        return view('text-management.edit', compact('textInfo', 'textTypes'));
    }

    /**
     * 更新文本
     */
    public function updateTextInfo(Request $request, $id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $textInfo = \App\Models\TextInfo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'TextType' => 'required|string|max:100',
            'TextGroup' => 'nullable|string|max:200',
            'TextContent' => 'nullable|string',
        ], [
            'TextType.required' => '请选择文本类型',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $textInfo->update($request->all());

        return redirect()->route('text-management.preview')->with('success', '文本更新成功！');
    }

    /**
     * 文本类型管理页面
     */
    public function textTypeIndex()
    {
        if (Auth::user()->role != 1) {
            abort(403, '只有管理员可以访问');
        }
        
        $textTypes = \App\Models\TextType::latest()->get();
        return view('text-management.types', compact('textTypes'));
    }

    /**
     * 保存文本类型
     */
    public function storeTextType(Request $request)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:100|unique:text_types',
        ], [
            'type.required' => '请填写文本类型名称',
            'type.unique' => '该文本类型已存在',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        \App\Models\TextType::create($request->all());

        return redirect()->route('text-management.types')->with('success', '文本类型添加成功！');
    }

    /**
     * 更新文本类型
     */
    public function updateTextType(Request $request, $id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $textType = \App\Models\TextType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:100|unique:text_types,type,'.$id,
        ], [
            'type.required' => '请填写文本类型名称',
            'type.unique' => '该文本类型已存在',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $textType->update($request->all());

        return redirect()->route('text-management.types')->with('success', '文本类型更新成功！');
    }

    /**
     * 删除文本类型
     */
    public function deleteTextType($id)
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限进行此操作');
        }

        $textType = \App\Models\TextType::findOrFail($id);
        $textType->delete();

        return redirect()->route('text-management.types')->with('success', '文本类型删除成功！');
    }

}
