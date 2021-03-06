<?php
/**
 * @package     Petrinet
 * @subpackage  Arc
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Base class for PHP expressions associated with an arc.
 * Arcs expressions are used to manipulate Token colors (ie. operating on its values)
 * when they transit through the arc.
 *
 * The expression arguments property contains an (ordered) array of type names
 * that must be a subset of the input place/transition color set.
 *
 * @package     Petrinet
 * @subpackage  Arc
 * @since       1.0
 */
abstract class PNArcExpression
{
	/**
	 * @var    array  The expression arguments.
	 * @since  1.0
	 */
	protected $arguments;

	/**
	 * @var    PNTypeManager  The type Manager.
	 * @since  1.0
	 */
	protected $typeManager;

	/**
	 * Constructor.
	 *
	 * @param   array          $arguments  The expression arguments.
	 * @param   PNTypeManager  $manager    The type Manager.
	 *
	 * @throws  InvalidArgumentException
	 *
	 * @since   1.0
	 */
	public function __construct(array $arguments = array(), PNTypeManager $manager = null)
	{
		// Use the given type manager, or create a new one.
		$this->typeManager = $manager ? $manager : new PNTypeManager;

		// Set the expression arguments.
		$this->setArguments($arguments);
	}

	/**
	 * Execute the expression.
	 * The method must return an array of values.
	 * The value types must be a subset of the output place/transition color set.
	 *
	 * @param   array  $arguments  The expression arguments.
	 *
	 * @return  array  The return values.
	 *
	 * @since   1.0
	 */
	abstract public function execute(array $arguments);

	/**
	 * Set the expression arguments.
	 *
	 * @param   array  $arguments  The expression arguments.
	 *
	 * @return  void
	 *
	 * @throws  InvalidArgumentException
	 *
	 * @since   1.0
	 */
	protected function setArguments(array $arguments)
	{
		// Verify all arguments are allowed.
		foreach ($arguments as $argument)
		{
			if (!$this->typeManager->isAllowed($argument))
			{
				throw new InvalidArgumentException('Argument : ' . $argument . ' is not allowed');
			}
		}

		// Store them.
		$this->arguments = $arguments;
	}

	/**
	 * Set the type Manager.
	 *
	 * @param   PNTypeManager  $manager  The type Manager.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setTypeManager(PNTypeManager $manager)
	{
		$this->typeManager = $manager;
	}

	/**
	 * Get the expression arguments.
	 *
	 * @return  array  The expression arguments.
	 *
	 * @since   1.0
	 */
	public function getArguments()
	{
		return $this->arguments;
	}
}
