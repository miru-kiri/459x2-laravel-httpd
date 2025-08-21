<?php
/**
 * 生年月日から年齢を出力
 *
 * @param [type] $birthdate
 * @return
 */
function calculateAge($birthdate) {
  // 生年月日が正しい形式でない場合はエラーを表示
  if (!isValidDate($birthdate)) {
    return false;
  }
  // 現在の日付を取得
  $currentDate = new DateTime();
  // 生年月日をDateTimeオブジェクトに変換
  $birthDate = new DateTime($birthdate);
  
  // 年齢を計算
  $age = $currentDate->diff($birthDate)->y;
  
  return $age;
}
function isValidDate($dateString) {
  // Y-m-d形式を試す
  $date1 = DateTime::createFromFormat('Y-m-d', $dateString);

  // Y/m/d形式を試す
  $date2 = DateTime::createFromFormat('Y/m/d', $dateString);

  return ($date1 && $date1->format('Y-m-d') === $dateString) || ($date2 && $date2->format('Y/m/d') === $dateString);
}
