<?php

use App\Models\Admin_Action_Log;
use App\Models\Cast_Action_Log;

function createLog($type = 0,$id = 0,$categoryId = 2,$content = null)
{
  //ログ
  try {
    $logParameter = [
      'created_at' => time(), 
      'category_id' => $categoryId,
      'date' => date('Ymd'),
      'time' => date('His'),
      'content' => $content,
    ];
    if($type == 0) {
      $logParameter['admin_id'] = $id;
      Admin_Action_Log::insert($logParameter);
    } else {
      $logParameter['cast_id'] = $id;
      Cast_Action_Log::insert($logParameter);
    }
  } catch (Exception $e) {
    return false;
  }
  return true;
}