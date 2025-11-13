<?php

namespace PTool\Commands;

class CheckDiCommand
{
    public function handle(string $modulePath)
    {
        $modulePath = rtrim($modulePath, '/');

        $common = $modulePath . '/config/common.php';
        if (!file_exists($common)) {
            echo "[ERROR] 找不到 config/common.php: $common\n";
            return;
        }

        // 載入 config
        $config = include $common;

        $definitions = $config['container']['definitions'] ?? [];
        $singletons = $config['container']['singletons'] ?? [];

        // 掃描 interface 檔
        $interfaceFiles = $this->scanInterfaces($modulePath);

        foreach ($interfaceFiles as $file) {
            $fullClass = $this->extractClass($file);

            if (!$fullClass) {
                echo "[WARN] 無法解析 namespace: $file\n";
                continue;
            }

            $registered =
                array_key_exists($fullClass, $definitions) ||
                array_key_exists($fullClass, $singletons);

            if ($registered) {
                echo "[OK] $fullClass 已註冊\n";
            } else {
                echo "[MISSING] $fullClass 未註冊\n";
            }
        }
    }

    private function scanInterfaces(string $modulePath): array
    {
        return glob($modulePath . '/interfaces/**/*.php', GLOB_BRACE);
    }

    private function extractClass(string $file): ?string
    {
        $code = file_get_contents($file);

        if (!preg_match('/namespace\s+(.+?);/', $code, $m1)) {
            return null;
        }
        $namespace = trim($m1[1]);

        preg_match('/interface\s+(\w+)/', $code, $m2);
        $className = $m2[1] ?? null;

        if (!$className) return null;

        return $namespace . '\\' . $className;
    }
}
