<?php

namespace App\Contracts;

use App\Models\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface WorkingPermitServiceInterface
{
  public function requestWorkingPermit(User $user, string $inspectionId, array $data);
  public function uploadWorkingPermit(UploadedFile $file, User $user,array $data, string $wpId);
  public function viewWorkingPermit(User $user, $perPage = 10);
  public function getFileIdByWPId(string $wpId);
}
