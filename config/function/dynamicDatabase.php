<?php
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

function getDynamicConnection($connectionName,$host = 'localhost',$userName = 'root',$password = 'root')
{
  $connectionConfig = [
      'driver' => 'mysql',
      // 'host' => $host,
      'host' => $host,
      // 'host' => 'api459x.com',
      'database' => $connectionName,
      // 'username' => $userName,
      // 'password' => $password,
      'username' => $userName,
      'password' => $password,
      'charset' => 'utf8',
      // 'collation' => 'utf8mb4_unicode_ci',
      'collation' => 'utf8_general_ci',
      'prefix' => '',
      'strict' => true,
      'engine' => null,
    ];
  // デフォルトの接続情報を退避
  $defaultConnection = Config::get('database.default');
  $defaultConfig = Config::get("database.connections.$defaultConnection");
  // 動的な接続情報を設定
  Config::set("database.connections.$connectionName", $connectionConfig);

  return [$defaultConnection,$defaultConfig];
}
