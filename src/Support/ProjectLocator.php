<?php

namespace PTool\Support;

class ProjectLocator
{
    /**
     * 專為你當前工作流程設計：
     * 偵測當前目錄下是否有 ./modules/
     *
     * 你會永遠在 basic/ 底下開發，所以只要看到 modules/ 即可視為目標。
     */
    public static function detectModulesRoot(): ?string
    {
        $cwd = getcwd();

        // 你永遠在 basic/ 底下，所以這裡一定是正確的
        if (is_dir($cwd . '/modules')) {
            return $cwd . '/modules';
        }

        // 如果使用者站到 modules/ 裡面不小心執行，也能 fallback
        if (basename($cwd) === 'modules') {
            return $cwd;
        }

        return null;
    }
}
