<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//对应数据库
use App\Models\files;


class FileUploadController extends Controller
{
    /**
     * 文件上传处理
     */
    /**
     * 文件下载
     */
    public function download($id)
    {
        $file = files::findOrFail($id);
        
        $filePath = storage_path('app/public/' . $file->path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', '文件不存在');
        }
        
        return response()->download($filePath, basename($file->path));
    }

    public function upload(Request $request)
    {
        // 文件类型定义
        $fileTypes = [
            'prepare'      => '准用资料',
            'maintenance'  => '维保资料',
            'inspection'   => '日常巡检资料',
            'fault'        => '故障记录资料',
            'repair'       => '维修记录资料',
            'accident'     => '事故记录资料',
            'rescue'       => '救援演练资料',
        ];

        // 文件验证规则
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:doc,docx,pdf,xls,xlsx,ppt,pptx,txt|max:20480', // 最大20MB
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:prepare,maintenance,inspection,fault,repair,accident,rescue',
        ], [
            'file.required' => '请选择要上传的文件',
            'file.mimes' => '仅支持 Word、PDF、Excel、PPT、TXT 格式文件',
            'file.max' => '文件大小不能超过20MB',
            'title.required' => '请填写文件标题',
            'type.required' => '文件类型参数缺失',
            'type.in' => '无效的文件类型',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 处理文件上传
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // 生成文件名
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/'.$request->input("type"), $fileName, 'public');
            // dd($filePath);
            // dd($request->input("type"));
            // TODO: 数据库操作 - 保存文件信息
            /*
            */

            files::create([
                'title' => $request->input("title"),
                'desc' => $request->input("description"),
                'type' => $request->input("type"),
                'path' => $filePath,
            ]);

            return back()->with('success', '文件上传成功！');
        }

        return back()->with('error', '文件上传失败，请重试。');
    }

    /**
     * 删除文件
     */
    public function delete($id)
    {
        $file = files::findOrFail($id);
        
        // 删除物理文件
        $filePath = storage_path('app/public/' . $file->path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // 删除数据库记录
        $file->delete();
        
        return redirect()->route('data.query')->with('success', '文件删除成功！');
    }
}
