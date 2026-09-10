<?php
// Temporary schema inspector — delete after use.
$pdo = new PDO('sqlite:C:/Users/user/project/web/tsaqib/database/database.sqlite', null, null, [PDO::SQLITE_ATTR_OPEN_FLAGS => PDO::SQLITE_OPEN_READONLY]);
echo "== tables ==\n";
foreach ($pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name") as $r) echo $r['name'], "\n";
echo "\n== guru_profiles columns ==\n";
foreach ($pdo->query("PRAGMA table_info(guru_profiles)") as $r) echo $r['name'], "\n";
echo "\n== migrations rows: ", $pdo->query("SELECT COUNT(*) FROM migrations")->fetchColumn(), " ==\n";
echo "\n== sessions users (db busy check): ";
echo $pdo->query("SELECT COUNT(*) FROM sessions")->fetchColumn(), " ==\n";
