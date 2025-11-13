<?php

namespace PTool\Parser;

use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

class InterfaceParser
{
    public static function parse(string $path): array
    {
        $code = file_get_contents($path);

        $factory = new ParserFactory();

        // ★★★ 你的版本需要用這個 ★★★
        $parser = $factory->createForHostVersion();

        $ast = $parser->parse($code);

        $methods = [];

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new class($methods) extends NodeVisitorAbstract {
            public $methods;

            public function __construct(&$methods)
            {
                $this->methods = &$methods;
            }

            public function enterNode(Node $node)
            {
                if ($node instanceof Node\Stmt\ClassMethod) {
                    $params = [];

                    foreach ($node->params as $param) {
                        $params[] = [
                            'name' => $param->var->name,
                            'type' => $param->type ? $param->type->toString() : null,
                        ];
                    }

                    $this->methods[] = [
                        'name' => $node->name->toString(),
                        'params' => $params,
                        'returnType' => $node->getReturnType() ? $node->getReturnType()->toString() : null,
                    ];
                }
            }
        });

        $traverser->traverse($ast);

        return $methods;
    }
}
