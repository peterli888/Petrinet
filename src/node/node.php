<?php
/**
 * @package     Petrinet
 * @subpackage  Petrinet
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Base class for Petri Net Nodes (Places and Transitions).
 *
 * @package     Petrinet
 * @subpackage  Petrinet
 * @since       1.0
 */
abstract class PNNode implements PNNodeBase
{
	/**
	 * @var    array  The input Arcs of this Node.
	 * @since  1.0
	 */
	protected $inputs;

	/**
	 * @var    array  The output Arcs of this Node.
	 * @since  1.0
	 */
	protected $outputs;

	/**
	 * @var    PNColorSet  The Node Color Set.
	 * @since  1.0
	 */
	protected $colorSet;

	/**
	 * Constructor.
	 *
	 * @param   PNColorSet  $colorSet  The Node Color Set.
	 * @param   array       $inputs    The input arcs of this Node (PNArcInput|PNArcOutput).
	 * @param   array       $outputs   The output arcs of this Node (PNArcInput|PNArcOutput).
	 *
	 * @since   1.0
	 */
	public function __construct(PNColorSet $colorSet = null, array $inputs = array(), array $outputs = array())
	{
		$this->colorSet = $colorSet;

		// If no inputs are given.
		if (empty($inputs))
		{
			$this->inputs = array();
		}

		// Try to add them.
		else
		{
			$this->setInputs($inputs);
		}

		// If no outputs are given.
		if (empty($outputs))
		{
			$this->outputs = array();
		}

		// Try to add them.
		else
		{
			$this->setOutputs($outputs);
		}
	}

	/**
	 * Add an input Arc to this Node.
	 *
	 * @param   PNArc  $arc  The Arc.
	 *
	 * @return  PNNode  This method is chainable.
	 *
	 * @since   1.0
	 */
	public function addInput(PNArc $arc)
	{
		$this->inputs[] = $arc;

		return $this;
	}

	/**
	 * Get the input Arcs of this Node.
	 *
	 * @return  array  An array of Arcs.
	 *
	 * @since   1.0
	 */
	public function getInputs()
	{
		return $this->inputs;
	}

	/**
	 * Set the input Arcs of this Node.
	 *
	 * @param   array  $arcs  An array of Arcs.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setInputs(array $arcs)
	{
		foreach ($arcs as $arc)
		{
			$this->addInput($arc);
		}
	}

	/**
	 * Check if the Node has at least one input arc.
	 *
	 * @return  boolean  True if it's the case, false otherwise.
	 *
	 * @since   1.0
	 */
	public function hasInput()
	{
		return empty($this->inputs) ? false : true;
	}

	/**
	 * Add an output Arc to this Node.
	 *
	 * @param   PNArc  $arc  Arc.
	 *
	 * @return  PNNode  This method is chainable.
	 *
	 * @since   1.0
	 */
	public function addOutput(PNArc $arc)
	{
		$this->outputs[] = $arc;

		return $this;
	}

	/**
	 * Get the output Arcs of this Node.
	 *
	 * @return  array  An array of Arcs.
	 *
	 * @since   1.0
	 */
	public function getOutputs()
	{
		return $this->outputs;
	}

	/**
	 * Set the output Arcs of this Node.
	 *
	 * @param   array  $arcs  An array of Arcs.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setOutputs(array $arcs)
	{
		foreach ($arcs as $arc)
		{
			$this->addOutput($arc);
		}
	}

	/**
	 * Check if the Node has at least one output arc.
	 *
	 * @return  boolean  True if it's the case, false otherwise.
	 *
	 * @since   1.0
	 */
	public function hasOutput()
	{
		return empty($this->outputs) ? false : true;
	}

	/**
	 * Assert the Node is loaded.
	 *
	 * @return  void
	 *
	 * @throws  RuntimeException
	 *
	 * @since   1.0
	 */
	public function assertIsLoaded()
	{
		if (!$this->isLoaded())
		{
			throw new RuntimeException('Node not loaded.');
		}
	}

	/**
	 * Set the color set of this Node.
	 *
	 * @param   PNColorSet  $set  The color set.
	 *
	 * @return  PNNode  This method is chainable.
	 *
	 * @since   1.0
	 */
	public function setColorSet(PNColorSet $set)
	{
		$this->colorSet = $set;

		return $this;
	}

	/**
	 * Get the color set of this Node.
	 *
	 * @return  PNColorSet  The color set.
	 *
	 * @since   1.0
	 */
	public function getColorSet()
	{
		return $this->colorSet;
	}

	/**
	 * Check if we are in colored mode.
	 *
	 * @return  boolean  The color Mode.
	 *
	 * @since   1.0
	 */
	public function isColoredMode()
	{
		return $this->colorSet ? true : false;
	}
}
