<?php

namespace App\Implementations;

use App\Models\User;
use App\Models\Inspection;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Models\Notification;
use App\Models\WorkingPermit;
use App\Models\WorkInProgress;
use App\Events\SendAnnouncement;
use App\Models\InspectionTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Contracts\InspectionServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InspectionService implements InspectionServiceInterface
{

  public function viewInspectionTemplate(User $user, ?bool $isDraft)
  {
    try {
      $query = InspectionTemplate::select('TemplateId', 'TemplateDate', 'Description')
        ->where('CreatedBy', $user->UserId)
        ->where('IsActivated', true);

      if (!is_null($isDraft)) {
          $query->where('IsActivated', $isDraft);
      }

      $result = $query->get();

      return ApiResponse::success($result);

    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function inspectionTemplateById(string $templateId)
  {
    try {
      $query = InspectionTemplate::select('Content')
        ->where('TemplateId', $templateId)
        ->first();

      return ApiResponse::success($query);
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function createInspectionTemplate(User $user, array $data)
  {
    try {
      $result = InspectionTemplate::create([
        'TemplateId'    => Str::uuid(),
        'TemplateDate'  => now(),
        'Content'       => $data['Content'],
        'Description'   => $data['Description'],
        'IsActivated'   => $data['IsActivated'],
        'CreatedBy'     => $user->UserId,
      ]);
      return ApiResponse::success($result);
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function updateInspectionTemplate(User $user,  string $templateId, array $data)
  {
    try {
      $inspectionTemplate = InspectionTemplate::findOrFail($templateId);
      if ($user->UserId !== $inspectionTemplate->CreatedBy) {
        return ApiResponse::unauthorized('You are not authorized to update this template');
      }

      $result = DB::transaction(function () use ($inspectionTemplate, $data) {
        $inspectionTemplate->update([
          'Content'       => $data['Content'] ?? $inspectionTemplate->Content,
          'Description'   => $data['Description'] ?? $inspectionTemplate->Description,
          'IsActivated'   => $data['IsActivated'] ?? $inspectionTemplate->IsActivated,
        ]);
        return $inspectionTemplate;
      });
      return ApiResponse::success($result);
    } catch (ModelNotFoundException $e) {
      return ApiResponse::notFound('Template not found');
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function deleteInspectionTemplate(User $user,string $templateId)
  {
    try {
      $inspectionTemplate = InspectionTemplate::findOrFail($templateId);
      if ($user->UserId !== $inspectionTemplate->CreatedBy) {
        return ApiResponse::unauthorized('You are not authorized to delete this template');
      }

      $result = DB::transaction(function () use ($inspectionTemplate) {
        $inspectionTemplate->delete();
        return $inspectionTemplate;
      });

      return ApiResponse::success('Template deleted successfully');
    } catch (ModelNotFoundException $e) {
      return ApiResponse::notFound('Template not found');
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function viewInspectionTemplateAll(User $user, $perPage = 10)
  {
    try {
      $result = InspectionTemplate::select('TemplateId','TemplateDate', 'Description','IsActivated')
        ->where('CreatedBy', $user->UserId)
        ->paginate($perPage);
      return ApiResponse::success($result);
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function enabledInspectionTemplate(User $user, bool $isActivated , string $templateId)
  {
    try {
      $inspectionTemplate = InspectionTemplate::findOrFail($templateId);
      if ($user->UserId !== $inspectionTemplate->CreatedBy) {
        return ApiResponse::unauthorized('You are not authorized to update this template');
      }
      $result = DB::transaction(function () use ($isActivated, $inspectionTemplate) {
        $inspectionTemplate->update([
          'IsActivated'   => $isActivated ?? $inspectionTemplate->IsActivated,
        ]);
        return $inspectionTemplate;
      });
      return ApiResponse::success($result);
    } catch (ModelNotFoundException $e) {
      return ApiResponse::error('Template not found');
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function createInspection(User $user, array $data)
  {
    try {
      $uuid = (string) Str::uuid();
      $result = DB::transaction(function () use ($data, $uuid, $user) {
        $inspection = Inspection::create([
          'InspectionId'    => $uuid,
          'SWAPIC'          => $data['SWAPIC'],
          'Location'        => $data['Location'],
          'WorkType'        => $data['WorkType'],
          'InspectionForm'  => $data['InspectionForm'],
          'IsDraft'         => $data['IsDraft'],
          'CreatedBy'       => $user->UserId,
          'CreatedAt'       => now(),
        ]);

        WorkingPermit::create([
          'WPId'            => Str::uuid(),
          'InspectionId'    => $uuid,
          'Status'          => false,
          'RequestBy'       => $user->UserId
        ]);

        WorkInProgress::create([
          'WIPId'           => Str::uuid(),
          'InspectionId'    => $uuid,
          'Status'         => false
        ]);

        if ($data['IsDraft'] === false) {
          Notification::create([
            'NotificationId'  => Str::uuid(),
            'Title'           => 'Created Inspection',
            'Message'         => 'Inspection has been created',
            'Category'        => 'Inspection',
            'CreatedAt'       => now(),
            'CreatedBy'       => $user->UserId
          ]);
          DB::afterCommit(function () {
            SendAnnouncement::dispatch("All");
          });
        }

        return $inspection;
      });

      return ApiResponse::success($result);
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function updateInspection(User $user, string $inspectionId, array $data)
  {
    try {
      $inspection = Inspection::findOrFail($inspectionId);
      if ($user->UserId !== $inspection->CreatedBy) {
        return ApiResponse::unauthorized('You are not authorized to update this inspection');
      }
      $result = DB::transaction(function () use ($user, $inspection, $data) {
        $inspection->update([
          'SWAPIC'          => $data['SWAPIC'] ?? $inspection->SWAPIC,
          'Location'        => $data['Location'] ?? $inspection->Location,
          'WorkType'        => $data['WorkType'] ?? $inspection->WorkType,
          'InspectionForm'  => $data['InspectionForm'] ?? $inspection->InspectionForm,
          'IsDraft'         => $data['IsDraft'] ?? $inspection->IsDraft
        ]);
        if ($data['IsDraft'] === false) {
          Notification::create([
            'NotificationId'  => Str::uuid(),
            'Title'           => 'Created Inspection',
            'Message'         => 'Inspection has been created',
            'Category'        => 'Inspection',
            'CreatedAt'       => now(),
            'CreatedBy'       => $user->UserId
          ]);
          DB::afterCommit(function () {
            SendAnnouncement::dispatch("All");
          });
        }
        return $inspection;
      });
      return ApiResponse::success($result);
    } catch (ModelNotFoundException $e) {
      return ApiResponse::notFound('Inspection not found');
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function viewInspection(User $user, $perPage = 10, ?bool $isDraft)
  {
    try {
      $result = null;
      if ($user->UserGroup === 'Safety Advisor') {
        $query = Inspection::select('InspectionId', 'SWAPIC', 'Location', 'WorkType', 'InspectionForm', 'IsDraft')
        ->where('CreatedBy', $user->UserId)
        ->orderByDesc('CreatedAt');
        if (!is_null($isDraft)) {
          $query->where('IsDraft', $isDraft);
        }
        $result = $query->paginate($perPage);

      } elseif ($user->UserGroup === 'Pengawas K3') {
        $query = Inspection::select('InspectionId','SWAPIC', 'Location', 'WorkType', 'InspectionForm', 'CreatedBy')
        ->with(['user_detail:UserId,Name'])
        ->orderByDesc('CreatedAt');
        if (!is_null($isDraft)) {
          $query->where('IsDraft', $isDraft);
        }
        $result = $query->paginate($perPage);
      } else {
        return ApiResponse::notFound('User group not authorized to view inspections');
      }
      return ApiResponse::success($result);
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function viewInspectionById(string $inspectionId)
  {
    try {
      $result = Inspection::select('InspectionId', 'SWAPIC', 'Location', 'WorkType', 'InspectionForm', 'IsDraft')
      ->where('InspectionId', $inspectionId)
      ->first();

      return ApiResponse::success($result);
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }

  public function deleteInspection(User $user, string $inspectionId)
  {
    try {
      $inspection = Inspection::findOrFail($inspectionId);
      if ($user->UserId !== $inspection->CreatedBy) {
        return ApiResponse::unauthorized('You are not authorized to delete this inspection');
      }

      $result = DB::transaction(function () use ($inspection) {
        $inspection->delete();
        return $inspection;
      });
      return ApiResponse::success($result);
    } catch (ModelNotFoundException $e) {
      return ApiResponse::notFound('Inspection not found');
    } catch (\Exception $e) {
      return ApiResponse::error($e);
    }
  }



}
