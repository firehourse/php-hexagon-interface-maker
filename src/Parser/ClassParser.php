<?php

namespace PTool\Parser;

use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

class ClassParser
{
    /**
     * 解析 class，回傳結構化資訊：
     * [
     *   'namespace' => 'App\Modules\Order\infrastructure\repository',
     *   'class' => 'OrderPgsqlRepository',
     *   'implements' => ['OrderRepositoryInterface'],
     *   'methods' => [
     *       'findById' => [
     *           'name' => 'findById',
     *           'params' => [
     *                ['name' => 'id', 'type' => 'int'],
     *           ],
     *           'returnType' => 'Order',
     *       ],
     *   ]
     * ]
     */
    public static function parse(string $path): array
    {
        if (!file_exists($path)) {
            throw new \Exception("Class file not found: $path");
        }

        $code = file_get_contents($path);

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $ast = $parser->parse($code);

        $result = [
            'namespace'   => null,
            'class'       => null,
            'implements'  => [],
            'methods'     => [],
        ];

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new class($result) extends NodeVisitorAbstract {

            public $result;

            public function __construct(&$result)
            {
                $this->result = &$result;
            }

            public function enterNode(Node $node)
            {
                // namespace
                if ($node instanceof Node\Stmt\Namespace_) {
                    $this->result['namespace'] = $node->name ? $node->name->toString() : null;
                }

                // class
                if ($node instanceof Node\Stmt\Class_) {
                    $this->result['class'] = $node->name->toString();

                    // implements
                    foreach ($node->implements as $impl) {
                        $this->result['implements'][] = $impl->toString();
                    }
                }

                // methods
                if ($node instanceof Node\Stmt\ClassMethod) {
                    $methodName = $node->name->toString();

                    $params = [];
                    foreach ($node->params as $param) {
                        $params[] = [
                            'name' => $param->var->name,
                            'type' => $param->type ? $param->type->toString() : null,
                        ];
                    }

                    $this->result['methods'][$methodName] = [
                        'name'       => $methodName,
                        'params'     => $params,
                        'returnType' => $node->getReturnType() ? $node->getReturnType()->toString() : null,
                    ];
                }
            }
        });

        $traverser->traverse($ast);

        return $result;
    }
}
