<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoType;
use App\Models\VideoInfo;
use App\Models\ImageText;
use App\Models\ImageType;
use App\Models\ImageInfo;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class FrontendAPI extends Controller
{
    /**
     * 成功响应统一封装
     */
    private function successResponse($data, string $message = 'success', int $code = 200): JsonResponse
    {
        return response()->json([
            'code'    => $code,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    /**
     * 错误响应统一封装
     */
    private function errorResponse(string $message, int $code = 500, $errors = null): JsonResponse
    {
        $response = [
            'code'    => $code,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * 获取所有视频分类
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function videoType(Request $request): JsonResponse
    {
        try {
            $videoTypes = VideoType::all();

            return $this->successResponse($videoTypes);

        } catch (QueryException $e) {
            report($e);
            return $this->errorResponse('获取视频分类失败', 500);
        } catch (\Exception $e) {
            report($e);
            return $this->errorResponse('服务器内部错误', 500);
        }
    }

    /**
     * 获取所有视频信息
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function videoInfo(Request $request): JsonResponse
    {
        try {
            $videoInfos = VideoInfo::orderBy('created_at', 'desc')->get();

            return $this->successResponse($videoInfos);

        } catch (QueryException $e) {
            report($e);
            return $this->errorResponse('获取视频信息失败', 500);
        } catch (\Exception $e) {
            report($e);
            return $this->errorResponse('服务器内部错误', 500);
        }
    }

    /**
     * 获取所有图片类型
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function imageTextType(Request $request): JsonResponse
    {
        try {
            $imageTypes = ImageType::all();

            return $this->successResponse($imageTypes);

        } catch (QueryException $e) {
            report($e);
            return $this->errorResponse('获取图片分类失败', 500);
        } catch (\Exception $e) {
            report($e);
            return $this->errorResponse('服务器内部错误', 500);
        }
    }

    /**
     * 获取所有图片信息
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function imageTextList(Request $request): JsonResponse
    {
        try {
            $query = ImageInfo::query();

            // 关键词搜索（按图片组名）
            if ($request->has('keyword') && $request->keyword != '') {
                $keyword = $request->keyword;
                $query->where('imageGroup', 'like', "%{$keyword}%");
            }

            // 图片类型过滤
            if ($request->has('imageType') && $request->imageType != '') {
                $query->where('imageType', $request->imageType);
            }

            $imageInfos = $query->orderBy('created_at', 'desc')->get();

            return $this->successResponse($imageInfos);

        } catch (QueryException $e) {
            report($e);
            return $this->errorResponse('获取图片信息失败', 500);
        } catch (\Exception $e) {
            report($e);
            return $this->errorResponse('服务器内部错误', 500);
        }
    }

    /**
     * 获取图片详情
     *
     * @param int $id
     * @return JsonResponse
     */
    public function imageTextDetail($id): JsonResponse
    {
        try {
            $imageInfo = ImageInfo::findOrFail($id);

            return $this->successResponse($imageInfo);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('图片不存在', 404);
        } catch (QueryException $e) {
            report($e);
            return $this->errorResponse('获取图片详情失败', 500);
        } catch (\Exception $e) {
            report($e);
            return $this->errorResponse('服务器内部错误', 500);
        }
    }
}