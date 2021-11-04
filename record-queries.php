<?php
/**
 * Plugin Name:  Record Queries
 * Description:  Provides an easy way to get the raw database queries executed by wpdb.
 * Version:      1.0
 * Author:       Ashley Gibson
 * Text Domain:  record-queries
 * Requires PHP: 7.4
 *
 * Sample usage:
 *
$queries = RecordQueries\Record::getQueries(function () {
    get_posts();
});

RecordQueries\Record::display(function () {
    get_posts();
});
 */

namespace RecordQueries;

class Record {
	protected static array $queries = [];

	public static function getQueries(\closure $closure): array
	{
		self::$queries = [];

		add_filter( 'query', [ Record::class, 'logQuery' ] );

		call_user_func( $closure );

		remove_filter( 'query', [ Record::class, 'logQuery' ] );

		return self::$queries;
	}

	public static function display(\closure $closure): void
	{
		?>
		<ol>
			<?php foreach( self::getQueries($closure) as $query ) : ?>
				<li style="margin: 1rem">
					<pre><?php echo esc_html($query); ?></pre>
				</li>
			<?php endforeach; ?>
		</ol>
		<?php
	}

	public static function logQuery(string $query): void
	{
		self::$queries[] = $query;
	}
}
