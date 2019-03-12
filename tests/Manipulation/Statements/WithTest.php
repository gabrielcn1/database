<?php namespace Tests\Database\Manipulation\Statements;

use Framework\Database\Manipulation\Statements\Select;
use Framework\Database\Manipulation\Statements\With;
use PHPUnit\Framework\TestCase;
use Tests\Database\DatabaseMock;

class WithTest extends TestCase
{
	/**
	 * @var With
	 */
	protected $with;

	public function setup()
	{
		$this->with = new With(new DatabaseMock());
	}

	protected function prepareWith()
	{
		$this->with->reference('t1', function (Select $select) {
			return $select->columns('*')->from('folks')->sql();
		})->select(function (Select $select) {
			return $select->columns('*')->from('ancestors')->sql();
		});
	}

	public function testWith()
	{
		$this->prepareWith();
		$this->assertEquals(
			"WITH\n`t1` AS (SELECT\n*\n FROM `folks`\n)\nSELECT\n*\n FROM `ancestors`\n",
			$this->with->sql()
		);
	}

	public function testOptions()
	{
		$this->prepareWith();
		$this->with->options($this->with::OPT_RECURSIVE);
		$this->assertEquals(
			"WITH\nRECURSIVE\n`t1` AS (SELECT\n*\n FROM `folks`\n)\nSELECT\n*\n FROM `ancestors`\n",
			$this->with->sql()
		);
	}

	public function testManyReferences()
	{
		$this->prepareWith();
		$this->with->reference('t2', function () {
			return 'select * from foo';
		});
		$this->assertEquals(
			"WITH\n`t1` AS (SELECT\n*\n FROM `folks`\n), `t2` AS (select * from foo)\nSELECT\n*\n FROM `ancestors`\n",
			$this->with->sql()
		);
	}

	public function testInvalidOption()
	{
		$this->with->options('foo');
		$this->expectException(\LogicException::class);
		$this->expectExceptionMessage('Invalid option: foo');
		$this->with->sql();
	}

	public function testWithoutReference()
	{
		$this->expectException(\LogicException::class);
		$this->expectExceptionMessage('References must be set');
		$this->with->sql();
	}

	public function testWithoutSelect()
	{
		$this->with->reference('t1', function (Select $select) {
			return $select->columns('*')->from('folks')->sql();
		});
		$this->expectException(\LogicException::class);
		$this->expectExceptionMessage('SELECT must be set');
		$this->with->sql();
	}
}