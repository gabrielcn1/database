<?php namespace Framework\Database\Manipulation\Traits;

use Closure;

/**
 * Trait Having.
 */
trait Having
{
	use Where;

	/**
	 * Appends a "AND $column $operator ...$values" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param string $operator
	 * @param Closure|float|int|string|null ...$values
	 *
	 * @return $this
	 */
	public function having(
		Closure | string $column,
		string $operator,
		Closure | float | int | string | null ...$values
	) {
		return $this->addHaving('AND', $column, $operator, $values);
	}

	/**
	 * Appends a "OR $column $operator ...$values" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param string $operator
	 * @param Closure|float|int|string|null ...$values
	 *
	 * @return $this
	 */
	public function orHaving(
		Closure | string $column,
		string $operator,
		Closure | float | int | string | null ...$values
	) {
		return $this->addHaving('OR', $column, $operator, $values);
	}

	/**
	 * Appends a "AND $column = $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/equal/
	 *
	 * @return $this
	 */
	public function havingEqual(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->having($column, '=', $value);
	}

	/**
	 * Appends a "OR $column = $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/equal/
	 *
	 * @return $this
	 */
	public function orHavingEqual(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->orHaving($column, '=', $value);
	}

	/**
	 * Appends a "AND $column != $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/not-equal/
	 *
	 * @return $this
	 */
	public function havingNotEqual(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->having($column, '!=', $value);
	}

	/**
	 * Appends a "OR $column != $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/not-equal/
	 *
	 * @return $this
	 */
	public function orHavingNotEqual(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->orHaving($column, '!=', $value);
	}

	/**
	 * Appends a "AND $column <=> $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/null-safe-equal/
	 *
	 * @return $this
	 */
	public function havingNullSafeEqual(
		Closure | string $column,
		Closure | float | int | string | null $value
	) {
		return $this->having($column, '<=>', $value);
	}

	/**
	 * Appends a "OR $column <=> $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/null-safe-equal/
	 *
	 * @return $this
	 */
	public function orHavingNullSafeEqual(
		Closure | string $column,
		Closure | float | int | string | null $value
	) {
		return $this->orHaving($column, '<=>', $value);
	}

	/**
	 * Appends a "AND $column < $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/less-than/
	 *
	 * @return $this
	 */
	public function havingLessThan(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->having($column, '<', $value);
	}

	/**
	 * Appends a "OR $column < $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/less-than/
	 *
	 * @return $this
	 */
	public function orHavingLessThan(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->orHaving($column, '<', $value);
	}

	/**
	 * Appends a "AND $column <= $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/less-than-or-equal/
	 *
	 * @return $this
	 */
	public function havingLessThanOrEqual(
		Closure | string $column,
		Closure | float | int | string | null $value
	) {
		return $this->having($column, '<=', $value);
	}

	/**
	 * Appends a "OR $column <= $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/less-than-or-equal/
	 *
	 * @return $this
	 */
	public function orHavingLessThanOrEqual(
		Closure | string $column,
		Closure | float | int | string | null $value
	) {
		return $this->orHaving($column, '<=', $value);
	}

	/**
	 * Appends a "AND $column > $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/greater-than/
	 *
	 * @return $this
	 */
	public function havingGreaterThan(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->having($column, '>', $value);
	}

	/**
	 * Appends a "OR $column > $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/greater-than/
	 *
	 * @return $this
	 */
	public function orHavingGreaterThan(
		Closure | string $column,
		Closure | float | int | string | null $value
	) {
		return $this->orHaving($column, '>', $value);
	}

	/**
	 * Appends a "AND $column >= $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/greater-than-or-equal/
	 *
	 * @return $this
	 */
	public function havingGreaterThanOrEqual(
		Closure | string $column,
		Closure | float | int | string | null $value
	) {
		return $this->having($column, '>=', $value);
	}

	/**
	 * Appends a "OR $column >= $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/greater-than-or-equal/
	 *
	 * @return $this
	 */
	public function orHavingGreaterThanOrEqual(
		Closure | string $column,
		Closure | float | int | string | null $value
	) {
		return $this->orHaving($column, '>=', $value);
	}

	/**
	 * Appends a "AND $column LIKE $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/like/
	 *
	 * @return $this
	 */
	public function havingLike(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->having($column, 'LIKE', $value);
	}

	/**
	 * Appends a "OR $column LIKE $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/like/
	 *
	 * @return $this
	 */
	public function orHavingLike(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->orHaving($column, 'LIKE', $value);
	}

	/**
	 * Appends a "AND $column NOT LIKE" $value condition.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/not-like/
	 *
	 * @return $this
	 */
	public function havingNotLike(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->having($column, 'NOT LIKE', $value);
	}

	/**
	 * Appends a "OR $column NOT LIKE $value" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 *
	 * @see https://mariadb.com/kb/en/library/not-like/
	 *
	 * @return $this
	 */
	public function orHavingNotLike(Closure | string $column, Closure | float | int | string | null $value)
	{
		return $this->orHaving($column, 'NOT LIKE', $value);
	}

	/**
	 * Appends a "AND $column IN (...$values)" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 * @param Closure|float|int|string|null ...$values
	 *
	 * @see https://mariadb.com/kb/en/library/in/
	 *
	 * @return $this
	 */
	public function havingIn(
		Closure | string $column,
		Closure | float | int | string | null $value,
		Closure | float | int | string | null ...$values
	) {
		$values = $this->mergeExpressions($value, $values);
		return $this->having($column, 'IN', ...$values);
	}

	/**
	 * Appends a "OR $column IN (...$values)" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 * @param Closure|float|int|string|null ...$values
	 *
	 * @see https://mariadb.com/kb/en/library/in/
	 *
	 * @return $this
	 */
	public function orHavingIn(
		Closure | string $column,
		Closure | float | int | string | null $value,
		Closure | float | int | string | null ...$values
	) {
		$values = $this->mergeExpressions($value, $values);
		return $this->orHaving($column, 'IN', ...$values);
	}

	/**
	 * Appends a "AND $column NOT IN (...$values)" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 * @param Closure|float|int|string|null ...$values
	 *
	 * @see https://mariadb.com/kb/en/library/not-in/
	 *
	 * @return $this
	 */
	public function havingNotIn(
		Closure | string $column,
		Closure | float | int | string | null $value,
		Closure | float | int | string | null ...$values
	) {
		$values = $this->mergeExpressions($value, $values);
		return $this->having($column, 'NOT IN', ...$values);
	}

	/**
	 * Appends a "OR $column NOT IN (...$values)" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $value
	 * @param Closure|float|int|string|null ...$values
	 *
	 * @see https://mariadb.com/kb/en/library/not-in/
	 *
	 * @return $this
	 */
	public function orHavingNotIn(
		Closure | string $column,
		Closure | float | int | string | null $value,
		Closure | float | int | string | null ...$values
	) {
		$values = $this->mergeExpressions($value, $values);
		return $this->orHaving($column, 'NOT IN', ...$values);
	}

	/**
	 * Appends a "AND $column BETWEEN $min AND $max" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $min
	 * @param Closure|float|int|string|null $max
	 *
	 * @see https://mariadb.com/kb/en/library/between-and/
	 *
	 * @return $this
	 */
	public function havingBetween(
		Closure | string $column,
		Closure | float | int | string | null $min,
		Closure | float | int | string | null $max
	) {
		return $this->having($column, 'BETWEEN', $min, $max);
	}

	/**
	 * Appends a "OR $column BETWEEN $min AND $max" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $min
	 * @param Closure|float|int|string|null $max
	 *
	 * @see https://mariadb.com/kb/en/library/between-and/
	 *
	 * @return $this
	 */
	public function orHavingBetween(
		Closure | string $column,
		Closure | float | int | string | null $min,
		Closure | float | int | string | null $max
	) {
		return $this->orHaving($column, 'BETWEEN', $min, $max);
	}

	/**
	 * Appends a "AND $column NOT BETWEEN $min AND $max" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $min
	 * @param Closure|float|int|string|null $max
	 *
	 * @see https://mariadb.com/kb/en/library/not-between/
	 *
	 * @return $this
	 */
	public function havingNotBetween(
		Closure | string $column,
		Closure | float | int | string | null $min,
		Closure | float | int | string | null $max
	) {
		return $this->having($column, 'NOT BETWEEN', $min, $max);
	}

	/**
	 * Appends a "OR $column NOT BETWEEN $min AND $max" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 * @param Closure|float|int|string|null $min
	 * @param Closure|float|int|string|null $max
	 *
	 * @see https://mariadb.com/kb/en/library/not-between/
	 *
	 * @return $this
	 */
	public function orHavingNotBetween(
		Closure | string $column,
		Closure | float | int | string | null $min,
		Closure | float | int | string | null $max
	) {
		return $this->orHaving($column, 'NOT BETWEEN', $min, $max);
	}

	/**
	 * Appends a "AND $column IS NULL" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 *
	 * @see https://mariadb.com/kb/en/library/is-null/
	 *
	 * @return $this
	 */
	public function havingIsNull(Closure | string $column)
	{
		return $this->having($column, 'IS NULL');
	}

	/**
	 * Appends a "OR $column IS NULL" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 *
	 * @see https://mariadb.com/kb/en/library/is-null/
	 *
	 * @return $this
	 */
	public function orHavingIsNull(Closure | string $column)
	{
		return $this->orHaving($column, 'IS NULL');
	}

	/**
	 * Appends a "AND $column IS NOT NULL" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 *
	 * @see https://mariadb.com/kb/en/library/is-not-null/
	 *
	 * @return $this
	 */
	public function havingIsNotNull(Closure | string $column)
	{
		return $this->having($column, 'IS NOT NULL');
	}

	/**
	 * Appends a "OR $column IS NOT NULL" condition in the HAVING clause.
	 *
	 * @param Closure|string $column Closure for a subquery or a string with the column name
	 *
	 * @see https://mariadb.com/kb/en/library/is-not-null/
	 *
	 * @return $this
	 */
	public function orHavingIsNotNull(Closure | string $column)
	{
		return $this->orHaving($column, 'IS NOT NULL');
	}

	/**
	 * Adds a HAVING part.
	 *
	 * @param string $glue `AND` or `OR`
	 * @param array<int,array|Closure|string>|Closure|string $column
	 * @param string $operator `=`, `<=>`, `!=`, `<>`, `>`, `>=`, `<`, `<=`,
	 * `LIKE`, `NOT LIKE`, `IN`, `NOT IN`, `BETWEEN`, `NOT BETWEEN`, `IS NULL`,
	 * `IS NOT NULL` or `MATCH`
	 * @param array<int,Closure|float|int|string|null> $values Values used by the operator
	 *
	 * @return $this
	 */
	private function addHaving(
		string $glue,
		array | Closure | string $column,
		string $operator,
		array $values
	) {
		return $this->addWhere($glue, $column, $operator, $values, 'having');
	}

	/**
	 * Renders the full HAVING clause.
	 *
	 * @return string|null The full clause or null if has not a clause
	 */
	protected function renderHaving() : ?string
	{
		return $this->renderWhere('having');
	}
}
