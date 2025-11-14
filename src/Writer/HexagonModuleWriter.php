<?php

namespace PTool\Writer;

class HexagonModuleWriter
{
    /**
     * 建立 module 的六角資料夾架構
     */
    public static function make(string $modulesRoot, string $moduleName): string
    {
        $base = rtrim($modulesRoot, '/') . '/' . $moduleName;

        $paths = [
            "$base/config",
            "$base/domain",
            "$base/domain/model",
            "$base/domain/service",
            "$base/domain/query",
            "$base/domain/command",
            "$base/interfaces",
            "$base/interfaces/repository",
            "$base/infrastructure",
            "$base/infrastructure/repository",
        ];

        foreach ($paths as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
        }

        // 建立初始 config/common.php（若不存在）
        $common = "$base/config/common.php";
        if (!file_exists($common)) {
            file_put_contents($common, self::generateCommonFile());
        }

        return $base;
    }

    private static function generateCommonFile(): string
    {
        return <<<PHP
<?php

/**
 * 六角架構標準 DI 設定檔
 */

\$config = [
    'container' => [
        'singletons' => [
            // 由 pTool 自動同步/補齊
        ],
    ],
];

return \$config;

PHP;
    }
}
