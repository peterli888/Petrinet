<?php
/**
 * @package     Tests.Unit
 * @subpackage  Arc
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Test Class for PNArc.
 *
 * @package     Tests.Unit
 * @subpackage  Arc
 * @since       1.0
 */
class PNArcTest extends TestCase
{
	/**
	 * @var    PNArc  A PNArc instance.
	 * @since  1.0
	 */
	protected $object;

	/**
	 * Setup.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new PNArc;
	}

	/**
	 * Constructor.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::getInput
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertNull(TestReflection::getValue($this->object, 'input'));
		$this->assertNull(TestReflection::getValue($this->object, 'output'));
		$this->assertNull(TestReflection::getValue($this->object, 'expression'));
		$this->assertInstanceOf('PNTypeManager', TestReflection::getValue($this->object, 'typeManager'));

		$expression = $this->getMockForAbstractClass('PNArcExpression');
		$arc = new PNArc(new PNPlace, new PNTransition, $expression);

		$this->assertInstanceOf('PNPlace', TestReflection::getValue($arc, 'input'));
		$this->assertInstanceOf('PNTransition', TestReflection::getValue($arc, 'output'));
		$this->assertEquals(TestReflection::getValue($arc, 'expression'), $expression);
	}

	/**
	 * Set the input Node of this Arc.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::setInput
	 * @since   1.0
	 */
	public function testSetInput()
	{
		// Set an input place.
		$input = new PNPlace;
		$this->object->setInput($input);
		$in = TestReflection::getValue($this->object, 'input');

		$this->assertEquals($in, $input);
	}

	/**
	 * Get the input Place or Transition of this Arc.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::getInput
	 * @since   1.0
	 */
	public function testGetInput()
	{
		// Assert the input is empty.
		$input = $this->object->getInput();
		$this->assertEmpty($input);

		// Add an input.
		TestReflection::setValue($this->object, 'input', true);
		$this->assertTrue($this->object->getInput());
	}

	/**
	 * Check if the arc has an input Place or Transition.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::hasInput
	 * @since   1.0
	 */
	public function testHasInput()
	{
		$this->assertFalse($this->object->hasInput());

		TestReflection::setValue($this->object, 'input', new PNTransition);

		$this->assertTrue($this->object->hasInput());
	}

	/**
	 * Set the output Node of this Arc.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::setOutput
	 * @since   1.0
	 */
	public function testSetOutput()
	{
		// Set an output transition.
		$output = new PNTransition;
		$this->object->setOutput($output);
		$out = TestReflection::getValue($this->object, 'output');

		$this->assertEquals($out, $output);
	}

	/**
	 * Get the output Place or Transition of this Arc.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::getOutput
	 * @since   1.0
	 */
	public function testGetOutput()
	{
		// Assert the output is empty.
		$output = $this->object->getOutput();
		$this->assertEmpty($output);

		// Add an output.
		TestReflection::setValue($this->object, 'output', true);
		$this->assertTrue($this->object->getOutput());
	}

	/**
	 * Check if the arc has an output Place or Transition.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::hasOutput
	 * @since   1.0
	 */
	public function testHasOutput()
	{
		$this->assertFalse($this->object->hasOutput());

		TestReflection::setValue($this->object, 'output', new PNTransition);

		$this->assertTrue($this->object->hasOutput());
	}

	/**
	 * Check if the arc is loaded.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::isLoaded
	 * @since   1.0
	 */
	public function testIsLoaded()
	{
		$this->assertFalse($this->object->isLoaded());

		// Add an input.
		TestReflection::setValue($this->object, 'input', new PNTransition);

		$this->assertFalse($this->object->isLoaded());

		// Add an output.
		TestReflection::setValue($this->object, 'output', new PNTransition);

		$this->assertTrue($this->object->isLoaded());
	}

	/**
	 * Assert the Arc is loaded.
	 * Note : mock isLoaded didn't work...
	 *
	 * @return  void
	 *
	 * @expectedException  RuntimeException
	 *
	 * @covers  PNArc::assertIsLoaded
	 * @since   1.0
	 */
	public function testAssertIsLoadedException()
	{
		$this->object->assertIsLoaded();
	}

	/**
	 * Assert the Arc is loaded.
	 * Note : mock isLoaded didn't work...
	 *
	 * @return  void
	 *
	 * @covers  PNArc::assertIsLoaded
	 * @since   1.0
	 */
	public function testAssertIsLoaded()
	{
		TestReflection::setValue($this->object, 'input', true);
		TestReflection::setValue($this->object, 'output', true);

		$this->assertNull($this->object->assertIsLoaded());
	}

	/**
	 * Check if the arc expression is valid.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::validateExpression
	 * @since   1.0
	 */
	public function testValidateExpression()
	{
		// From a a place to a transition.
		// Create a place.
		$colorSet = new PNColorSet(array('integer', 'double', 'array'));
		$place = new PNPlace($colorSet);
		TestReflection::setValue($this->object, 'input', $place);

		// Create a transition.
		$colorSet = new PNColorSet(array('integer', 'double', 'array'));
		$transition = new PNTransition($colorSet);
		TestReflection::setValue($this->object, 'output', $transition);

		// Mock the expression.
		$expression1 = $this->getMockForAbstractClass('PNArcExpression', array(array('integer', 'double', 'array')));
		$expression1->expects($this->once())
			->method('execute')
			->will($this->returnValue(array(8, 8.2, array())));

		TestReflection::setValue($this->object, 'expression', $expression1);

		$this->assertTrue($this->object->validateExpression());

		// Test a sub-set of the transition color set.
		$expression2 = $this->getMockForAbstractClass('PNArcExpression', array(array('integer', 'double', 'array')));
		$expression2->expects($this->once())
			->method('execute')
			->will($this->returnValue(array(8, 8.2)));

		TestReflection::setValue($this->object, 'expression', $expression2);

		$this->assertTrue($this->object->validateExpression());

		// Test with non matching transition color set return values types.
		$expression3 = $this->getMockForAbstractClass('PNArcExpression', array(array('integer', 'double', 'array')));
		$expression3->expects($this->once())
			->method('execute')
			->will($this->returnValue(array('test')));

		TestReflection::setValue($this->object, 'expression', $expression3);

		$this->assertFalse($this->object->validateExpression());

		// Test with non matching expression arguments.
		$expression4 = $this->getMockForAbstractClass('PNArcExpression', array(array('double', 'double', 'array')));
		TestReflection::setValue($this->object, 'expression', $expression4);
		$this->assertFalse($this->object->validateExpression());

		// From a transition to a place.
		// Create a transition.
		$colorSet = new PNColorSet(array('integer', 'double', 'array'));
		$transition = new PNTransition($colorSet);
		TestReflection::setValue($this->object, 'input', $transition);

		$colorSet = new PNColorSet(array('integer', 'double', 'array'));
		$place = new PNPlace($colorSet);
		TestReflection::setValue($this->object, 'output', $place);

		// Mock the expression.
		$expression1 = $this->getMockForAbstractClass('PNArcExpression', array(array('integer', 'double', 'array')));
		$expression1->expects($this->once())
			->method('execute')
			->will($this->returnValue(array(8, 8.2, array())));

		TestReflection::setValue($this->object, 'expression', $expression1);

		$this->assertTrue($this->object->validateExpression());
	}

	/**
	 * Check if the arc has an expression attached to it.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::hasExpression
	 * @since   1.0
	 */
	public function testHasExpression()
	{
		$this->assertFalse($this->object->hasExpression());

		$expression = $this->getMockForAbstractClass('PNArcExpression');
		TestReflection::setValue($this->object, 'expression', $expression);

		$this->assertTrue($this->object->hasExpression());
	}

	/**
	 * Set the arc expression.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::setExpression
	 * @since   1.0
	 */
	public function testSetExpression()
	{
		$ex = $this->getMockForAbstractClass('PNArcExpression');

		$this->object->setExpression($ex);

		$this->assertEquals($ex, TestReflection::getValue($this->object, 'expression'));
	}

	/**
	 * Get the arc expression.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::getExpression
	 * @since   1.0
	 */
	public function testGetExpression()
	{
		TestReflection::setValue($this->object, 'expression', true);
		$this->assertTrue($this->object->getExpression());
	}

	/**
	 * Accept the Visitor.
	 *
	 * @return  void
	 *
	 * @covers  PNArc::accept
	 * @since   1.0
	 */
	public function testAccept()
	{
		$visitor = $this->getMockForAbstractClass('PNBaseVisitor', array(), '', true, true, true, array('visitArc'));

		// Create one mocked transition.
		$transition = $this->getMock('PNTransition');
		$transition->expects($this->once())
			->method('accept')
			->with($visitor);

		// Inject it.
		TestReflection::setValue($this->object, 'output', $transition);

		$visitor->expects($this->once())
			->method('visitArc')
			->with($this->object);

		$this->object->accept($visitor);
	}
}
