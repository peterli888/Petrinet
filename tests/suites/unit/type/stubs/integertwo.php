<?php
/**
 * @package     Tests.Unit
 * @subpackage  Type.stubs
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * A Type of integers equal or greater than two.
 *
 * @package     Tests.Unit
 * @subpackage  Type.stubs
 * @since       1.0
 */
class IntegerTwo implements PNType
{
	/**
	 * Check the given variable matches the type.
	 *
	 * @param   mixed  $var  A PHP variable.
	 *
	 * @return  boolean  True if the variable matches, false otherwise.
	 *
	 * @since   1.0
	 */
	public function check($var)
	{
		return $var > 2;
	}

	/**
	 * Return a value compatible with this type.
	 *
	 * @return  mixed  A variable value.
	 *
	 * @since   1.0
	 */
	public function test()
	{
		return 2.2;
	}
}
