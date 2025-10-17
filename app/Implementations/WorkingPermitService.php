<?php

namespace App\Implementations;

use App\Models\User;
use App\Models\FileInput;
use App\Models\Inspection;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Models\Notification;
use App\Models\WorkingPermit;
use App\Events\SendAnnouncement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Contracts\WorkingPermitServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class WorkingPermitService implements WorkingPermitServiceInterface
{
  public function requestWorkingPermit(User $user, string $inspectionId, array $data)
  {
    try {
      $result = DB::transaction(function () use ($user, $inspectionId, $data) {
        $workingPermit = WorkingPermit::where('InspectionId', $inspectionId)->firstOrFail();
        $workingPermit->update([
          'WPDate'     => now(),
          'SendTo'     => $data['SendTo'],
        ]);

        Notification::create([
          'NotificationId'  => Str::uuid(),
          'UserId'          => $data['SendTo'],
          'Title'           => 'Request Working Permit',
          'Message'         => 'Inspection has been created',
          'Category'        => 'Inspection',
          'CreatedAt'       => now(),
          'CreatedBy'       => $user->UserId
        ]);

        DB::afterCommit(function () use ($data) {
          SendAnnouncement::dispatch($data['SendTo']);
        });

        return $workingPermit;
      });
      return ApiResponse::success($result);
    } catch (ModelNotFoundException $e) {
      return ApiResponse::notFound('Working Permit not found');
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function uploadWorkingPermit(UploadedFile $file, User $user,array $data, string $wpId)
  {
    try {
      if (!$file->isValid()) {
        throw new \Exception('File tidak valid atau gagal diunggah.');
      }
      $uuid = (string) Str::uuid();
      $extension = $file->getClientOriginalExtension();
      $filename = $uuid . '.' . $extension;
      $path = $file->storeAs('uploads', $filename, 'public');
      if (!Storage::disk('public')->exists('uploads/' . $filename)) {
        throw new \Exception('File gagal disimpan di server.');
      }
      $result = DB::transaction(function () use ($data, $wpId, $uuid, $user) {
        $fileInput = FileInput::create([
          'FileId' => $uuid,
          'WPId' => $wpId,
          'Status' => true,
          'UploadAt' => now(),
          'UploadedBy' => $user->UserId,
        ]);
        $wp = WorkingPermit::where('WPId', $wpId)->firstOrFail();
        $wp->update([
          'Status'          => true,
          'VerificationBy'  => $user->UserId,
        ]);

        Notification::create([
          'NotificationId'  => Str::uuid(),
          'UserId'          => $wp->RequestBy,
          'Title'           => 'Upload Working Permit',
          'Message'         => 'PDF Working Permit has been uploaded',
          'Category'        => 'Working Permit',
          'CreatedAt'       => now(),
          'CreatedBy'       => $user->UserId
        ]);

        DB::afterCommit(function () use ($wp) {
          SendAnnouncement::dispatch($wp->RequestBy);
        });

        return $fileInput;
    });
    return ApiResponse::success($result);

    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function viewWorkingPermit(User $user, $perPage = 10)
  {
    try {
      $result = null;
      if ($user->UserGroup === 'Safety Advisor') {
        $query = Inspection::select('InspectionId', 'SWAPIC', 'Location', 'WorkType', 'InspectionForm', 'IsDraft')
        ->with(['working_permits' => function ($q) use ($user) {
          $q->with(['user_sendto:UserId,Name']);
        }])
        ->where('IsDraft', false)
        ->where('CreatedBy', $user->UserId)
        ->orderByDesc('CreatedAt');
        $result = $query->paginate($perPage);

      } elseif ($user->UserGroup === 'Pengawas K3') {
        $query = Inspection::select('InspectionId','SWAPIC', 'Location', 'WorkType', 'InspectionForm', 'CreatedBy')
          ->with(['user_detail:UserId,Name'])
          ->with(['working_permits' => function ($q) use ($user) {
              $q->where('SendTo', $user->UserId);
          }])
          ->whereHas('working_permits', function ($q) use ($user) {
              $q->where('SendTo', $user->UserId);
          })
          ->where('IsDraft', false)
          ->orderByDesc('CreatedAt');

        $result = $query->paginate($perPage);
      } else {
        return ApiResponse::notFound('User group not authorized to view inspections');
      }

      return ApiResponse::success($result);
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function getFileIdByWPId(string $wpId)
  {
    try {
      $result = FileInput::select('FileId')
        ->where('WPId', $wpId)
        ->where('Status', true)
        ->firstOrFail();

      return ApiResponse::success($result);
    } catch (ModelNotFoundException $e) {
      return ApiResponse::notFound('File for Working Permit not found');
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }
}
