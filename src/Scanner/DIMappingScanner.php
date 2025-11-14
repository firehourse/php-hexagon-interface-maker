<?php

namespace PTool\Scanner;

class DIMappingScanner
{
    /**
     * 掃描 modules 目錄，取得全部 interface → class 綁定
     */
    public static function scanModules(string $modulesRoot): array
    {
        $mappings = [];

        // 尋找 /modules/*/config/common.php
        foreach (scandir($modulesRoot) as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $common = $modulesRoot . '/' . $module . '/config/common.php';

            if (!file_exists($common)) {
                continue;
            }

            $config = include $common;

            $singletons = $config['container']['singletons'] ?? [];

            foreach ($singletons as $interface => $impl) {
                $realImpl = self::normalizeImplementation($impl);

                if ($realImpl !== null) {
                    $mappings[$interface] = $realImpl;
                }
            }
        }

        return $mappings;
    }

    /**
     * 支援不同格式：
     * - Interface::class => Impl::class
     * - Interface::class => ['class' => Impl::class]
     * - Interface::class => new Impl()（跳過）
     */
    private static function normalizeImplementation($impl): ?string
    {
        // case: FooRepositoryInterface::class => FooRepository::class
        if (is_string($impl)) {
            return $impl;
        }

        // case: FooRepositoryInterface::class => ['class' => FooRepository::class]
        if (is_array($impl) && isset($impl['class']) && is_string($impl['class'])) {
            return $impl['class'];
        }

        // 其他格式（例如 closure、object）跳過
        return null;
    }
}
