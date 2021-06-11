<?php namespace Framework\Database\Definition\Table\Columns\Numeric;

final class IntColumn extends NumericDataType
{
	protected string $type = 'int';
	protected int $maxLength = 11;
}
