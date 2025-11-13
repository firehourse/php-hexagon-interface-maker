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

        // 支援 PHP 7.3~8.x 的寫法
        $factory = new ParserFactory();
        $parser = $factory->create(ParserFactory::PREFER_PHP7);

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
