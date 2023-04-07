<?php
if (! function_exists('number_to_time'))
{
	/**
	 * Convert a min to Time
	 *
	 * @param Int $num it will convert to int
	 *
	 * @return Array|null
	 */
	function number_to_time(int $time): ?Array
	{
        $hour = floor($time/60);
		$min = $time%60;
		return ['hour'=>$hour,'min'=>$min];
	}
}
