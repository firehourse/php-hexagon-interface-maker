<?php

namespace PTool\Commands;

use PTool\Parser\InterfaceParser;

class SyncRepositoryCommand
{
    public function handle(?string $path)
    {
        if (!$path || !file_exists($path)) {
            echo "[ERROR] Interface file not found: $path\n";
            return;
        }

        echo "Parsing Interface: $path\n\n";

        // 解析介面方法列表
        $methods = InterfaceParser::parse($path);

        echo "Detected Methods:\n";
        foreach ($methods as $m) {
            $sig = $m['name'] . '(' . implode(', ', array_map(fn($p) => $p['name'], $m['params'])) . ')';
            $sig .= $m['returnType'] ? ": {$m['returnType']}" : "";
            echo "- $sig\n";
        }

        echo "\nBuilding Repository...\n";

        // 推算 repository path
        $repoPath = $this->inferRepositoryPath($path);
        $repoNamespace = $repoPath['namespace'];
        $repoFile = $repoPath['file'];
        $interfaceNamespace = $repoPath['interfaceNamespace'];
        $repositoryClassName = $repoPath['className'];
        $interfaceClassName = $repoPath['interfaceClassName'];

        if (!file_exists(dirname($repoFile))) {
            mkdir(dirname($repoFile), 0777, true);
        }

        if (!file_exists($repoFile)) {
            // 新建 Repository
            file_put_contents($repoFile, $this->generateRepositoryClass(
                $repoNamespace,
                $repositoryClassName,
                $interfaceNamespace,
                $interfaceClassName,
                $methods
            ));
            echo "[CREATED] Repository created: $repoFile\n";
        } else {
            // 存在 → 要補齊方法
            $content = file_get_contents($repoFile);

            $missing = [];
            foreach ($methods as $m) {
                if (!str_contains($content, "function {$m['name']}(")) {
                    $missing[] = $m;
                }
            }

            if (empty($missing)) {
                echo "[OK] Repository already up-to-date.\n";
                return;
            }

            $newMethodsCode = "\n";
            foreach ($missing as $m) {
                $newMethodsCode .= $this->generateMethodStub($m) . "\n";
            }
            $newMethodsCode .= "}\n";

            // 插到 class 結尾前的 }
            $content = preg_replace('/}\s*$/', $newMethodsCode, $content);

            file_put_contents($repoFile, $content);

            echo "[UPDATED] Missing methods added: " . count($missing) . "\n";
        }
    }

    /**
     * 推算 repository 檔案位置與 namespace
     */
    private function inferRepositoryPath(string $interfacePath): array
    {
        // interface:
        // /home/.../{moduleName}/basic/modules/{domainModule}/interfaces/repository/FooRepositoryInterface.php

        $parts = explode('/', $interfacePath);
        $count = count($parts);

        $interfaceFile = $parts[$count - 1];
        $interfaceClassName = str_replace('.php', '', $interfaceFile);
        $repositoryClassName = str_replace('Interface', '', $interfaceClassName);

        $domainModule = $parts[$count - 4];    // contractRenewal
        $interfaceNamespace = $domainModule . "\\interfaces\\repository";

        $repoNamespace = $domainModule . "\\infrastructure\\repository";

        // build new repository file path
        $repoPath = str_replace(
            ['interfaces/repository', $interfaceClassName . '.php'],
            ['infrastructure/repository', $repositoryClassName . '.php'],
            $interfacePath
        );

        return [
            'file' => $repoPath,
            'namespace' => $repoNamespace,
            'interfaceNamespace' => $interfaceNamespace,
            'className' => $repositoryClassName,
            'interfaceClassName' => $interfaceClassName
        ];
    }

    /**
     * 新建 Repository class
     */
    private function generateRepositoryClass(
        string $namespace,
        string $className,
        string $interfaceNamespace,
        string $interfaceClassName,
        array $methods
    ): string {
        $code = "<?php\n\n";
        $code .= "namespace $namespace;\n\n";
        $code .= "use $interfaceNamespace\\$interfaceClassName;\n\n";
        $code .= "class $className implements $interfaceClassName\n";
        $code .= "{\n";

        foreach ($methods as $m) {
            $code .= $this->generateMethodStub($m) . "\n";
        }

        $code .= "}\n";
        return $code;
    }

    /**
     * 產生方法 stub
     */
    private function generateMethodStub(array $m): string
    {
        $params = [];
        foreach ($m['params'] as $p) {
            $params[] = ($p['type'] ? $p['type'] . ' ' : '') . '$' . $p['name'];
        }
        $paramsStr = implode(', ', $params);

        $return = $m['returnType'] ? ": {$m['returnType']}" : "";

        return <<<CODE
    public function {$m['name']}($paramsStr)$return
    {
        // TODO: Implement {$m['name']}() method.
    }

CODE;
    }
}
