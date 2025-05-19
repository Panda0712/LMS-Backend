<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * Lấy tiến độ học tập của người dùng cho một khóa học cụ thể
     */
    public function getProgress(Request $request, $courseId)
    {
        $userId = $request->input('userId');
        if (!$userId) {
            return response()->json(['message' => 'Thiếu userId'], 400);
        }
        $progress = Progress::where('userId', $userId)
            ->where('courseId', $courseId)
            ->first();

        if (!$progress) {
            return response()->json(['message' => 'Không tìm thấy tiến độ học tập'], 404);
        }

        return response()->json($progress);
    }

    /**
     * Lấy tất cả tiến độ học tập của người dùng
     */
    public function getAllProgress(Request $request)
    {
        $userId = $request->input('userId');
        if (!$userId) {
            return response()->json(['message' => 'Thiếu userId'], 400);
        }
        $progresses = Progress::where('userId', $userId)->get();

        return response()->json($progresses);
    }

    /**
     * Cập nhật tiến độ học tập khi hoàn thành một bài học
     */
    public function updateLessonProgress(Request $request)
    {
        $request->validate([
            'courseId' => 'required|string',
            'lessonId' => 'required|string',
            'userId' => 'required|string',
        ]);

        $userId = $request->userId;
        $courseId = $request->courseId;
        $lessonId = $request->lessonId;

        // Tìm khóa học để lấy tổng số bài học
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'Không tìm thấy khóa học'], 404);
        }

        // Đếm tổng số bài học trong khóa học
        $totalLessons = 0;
        foreach ($course->courseModules as $module) {
            $totalLessons += count($module['lessons']);
        }

        // Tìm hoặc tạo mới tiến độ học tập
        $progress = Progress::firstOrNew([
            'userId' => $userId,
            'courseId' => $courseId,
        ]);

        // Khởi tạo mảng completedLessons nếu chưa có
        if (!isset($progress->completedLessons)) {
            $progress->completedLessons = [];
        }

        // Thêm bài học vào danh sách đã hoàn thành nếu chưa có
        // Sửa lỗi: Indirect modification of overloaded property
        $completedLessons = $progress->completedLessons;
        if (!in_array($lessonId, $completedLessons)) {
            $completedLessons[] = $lessonId;
            $progress->completedLessons = $completedLessons;
        }

        // Cập nhật số lượng bài học đã hoàn thành và phần trăm hoàn thành
        $progress->totalLessons = $totalLessons;
        $progress->percentComplete = ($totalLessons > 0) 
            ? (count($progress->completedLessons) / $totalLessons) * 100 
            : 0;
        $progress->lastAccessedAt = now();

        $progress->save();

        return response()->json($progress);
    }

    /**
     * Khởi tạo tiến độ học tập cho một khóa học mới
     */
    public function initProgress(Request $request)
    {
        $request->validate([
            'courseId' => 'required|string',
            'userId' => 'required|string',
        ]);

        $userId = $request->userId;
        $courseId = $request->courseId;

        // Kiểm tra xem tiến độ đã tồn tại chưa
        $existingProgress = Progress::where('userId', $userId)
            ->where('courseId', $courseId)
            ->first();

        if ($existingProgress) {
            return response()->json($existingProgress);
        }

        // Tìm khóa học để lấy tổng số bài học
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'Không tìm thấy khóa học'], 404);
        }

        // Đếm tổng số bài học trong khóa học
        $totalLessons = 0;
        foreach ($course->courseModules as $module) {
            $totalLessons += count($module['lessons']);
        }

        // Tạo mới tiến độ học tập
        $progress = new Progress([
            'userId' => $userId,
            'courseId' => $courseId,
            'completedLessons' => [],
            'totalLessons' => $totalLessons,
            'percentComplete' => 0,
            'lastAccessedAt' => now(),
        ]);

        $progress->save();

        return response()->json($progress);
    }
}