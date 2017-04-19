<?php

namespace Continuous\MicroServiceDemo\Auth\Api;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Crudy\Server\Dispatcher;
use Crudy\Server\Kit;
use Crudy\Server\JsonApi\View;
use Hoa\Router;

class Main extends Kit
{
    protected $dynamoClient;
    protected $prefixTable;

    public function __construct(Router $router, Dispatcher $dispatcher, View $view)
    {
        parent::__construct($router, $dispatcher, $view);

        $appConfig = appConfig();
        $config = [
            'version' => 'latest',
            'region' => $appConfig->aws->region,
        ];

        if ('testing' === $appConfig->general->env && !empty($appConfig->aws->key) && !empty($appConfig->aws->secret)) {
            $config['credentials'] = [
                'key' => $appConfig->aws->key,
                'secret' => $appConfig->aws->secret,
            ];
        }

        $this->dynamoClient = new DynamoDbClient($config);
        $this->prefixTable = $appConfig->aws->dynamo_prefix_table;
    }

    protected function query($table, $key, $hash)
    {
        $result = $this->dynamoClient->getItem([
            'TableName' => $this->prefixTable . '.' . $table,
            'Key'       => [
                $key => ['S' => $hash]
            ]
        ]);

        if (false === $result->offsetExists('Item')) {
            return null;
        }

        $M = new Marshaler();
        return $M->unmarshalItem($result->offsetGet('Item'));
    }

    protected function scan($table, $key, $value)
    {
        $result = $this->dynamoClient->scan([
            'TableName' => $this->prefixTable . '.' . $table,
            'Select' => 'ALL_ATTRIBUTES',
            'ScanFilter' => [
                $key => [
                    'AttributeValueList' => [
                        ['S' => $value],
                    ],
                    'ComparisonOperator' => 'EQ',
                ]
            ],
        ]);

        if (false === $result->offsetExists('Items')) {
            return null;
        }

        $M = new Marshaler();

        foreach ($result->offsetGet('Items') as $item) {
            yield $M->unmarshalItem($item);
        }
    }

    protected function insert($table, $attributes)
    {
        $M = new Marshaler();

        $item = [];

        foreach ($attributes as $k => $v) {
            $item[$k] = $M->marshalValue($v);
        }

        $this->dynamoClient->putItem([
            'TableName' => $this->prefixTable . '.' . $table,
            'Item'      => $item
        ]);
    }

    protected function patch($table, $hashKey, $hashValue, $attributes)
    {
        $M = new Marshaler();

        $dynamoAttributes = [
            ':updated_at'  => $M->marshalValue(time()),
        ];

        foreach ($attributes as $key => $value) {

            if ('' === $value) {
                $value = null;
            }

            if ($value instanceof \DateTimeInterface) {
                $value = $value->format(\DateTime::ATOM);
            }

            $dynamoAttributes[":$key"] = $M->marshalValue($value);
        }

        $updateExpression = array_keys($dynamoAttributes);
        $updateExpressionString = 'set';
        $expressionAttributeNames = [];

        foreach ($updateExpression as $column) {

            $key = '#' . substr($column, 1);
            $updateExpressionString .= " $key = $column,";
            $expressionAttributeNames[$key] = substr($column, 1);
        }

        $updateExpressionString = substr($updateExpressionString, 0, -1);

        $this->dynamoClient->updateItem([
            'TableName' => $this->prefixTable . '.' . $table,
            'Key' => [
                $hashKey => ['S' => $hashValue]
            ],
            'ExpressionAttributeNames' => $expressionAttributeNames,
            'ExpressionAttributeValues' => $dynamoAttributes,
            'UpdateExpression' => $updateExpressionString,
        ]);
    }
}