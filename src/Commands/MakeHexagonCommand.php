<?php

namespace PTool\Commands;

use PTool\Support\ProjectLocator;
use PTool\Writer\HexagonModuleWriter;

class MakeHexagonCommand
{
    public function handle(?string $moduleName)
    {
        if (!$moduleName) {
            echo "[ERROR] Missing module name.\n";
            echo "Usage: pTool make:hexagon {moduleName}\n";
            return;
        }

        // 偵測 modules 位置（只從當前工作目錄）
        $modulesRoot = ProjectLocator::detectModulesRoot();

        if (!$modulesRoot) {
            echo "[ERROR] 無法找到 modules 目錄。\n";
            echo "請記得要在 `basic/` 目錄內執行 pTool。\n";
            return;
        }

        $path = HexagonModuleWriter::make($modulesRoot, $moduleName);

        echo "[CREATED] Hexagonal module created at: $path\n";
    }
}
