<?php
/**
 * @package     Tests.Unit
 * @subpackage  Engine
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Test class for PNEngine.
 *
 * @package     Tests.Unit
 * @subpackage  Engine
 * @since       1.0
 */
class PNEngineTest extends TestCase
{
	/**
	 * @var    PNEngine  A PNEngine instance.
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

		$this->object = new PNEngine;

		// Inject a mocked state.
		$state = $this->getMockForAbstractClass('PNEngineState', array($this->object));
		TestReflection::setValue($this->object, 'state', $state);
	}

	/**
	 * Constructor.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$engine = new PNEngine;

		$this->assertInstanceOf('PNEngineStateStarted', TestReflection::getValue($engine, 'startedState'));
		$this->assertInstanceOf('PNEngineStateStopped', TestReflection::getValue($engine, 'stoppedState'));
		$this->assertInstanceOf('PNEngineStatePaused', TestReflection::getValue($engine, 'pausedState'));
		$this->assertInstanceOf('PNEngineStateResumed', TestReflection::getValue($engine, 'resumedState'));
		$this->assertInstanceOf('PNEngineStateEnded', TestReflection::getValue($engine, 'endedState'));
		$this->assertNull(TestReflection::getValue($engine, 'net'));

		$this->assertInstanceOf('PNEngineStateStopped', TestReflection::getValue($engine, 'state'));

		// Test the PetriNet is set correctly.
		$net = new PNPetrinet('test');
		$engine2 = new PNEngine($net);

		$this->assertInstanceOf('PNPetrinet', TestReflection::getValue($engine2, 'net'));
	}

	/**
	 * Get an instance or create it.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::getInstance
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		$this->assertInstanceOf('PNEngine', PNEngine::getInstance());

		$engine = PNEngine::getInstance(1, new PNPetrinet('test'));
		$this->assertInstanceOf('PNPetrinet', TestReflection::getValue($engine, 'net'));
	}

	/**
	 * Start the execution.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::start
	 * @since   1.0
	 */
	public function testStart()
	{
		// Set a Petri Net for execution.
		TestReflection::setValue($this->object, 'net', new PNPetrinet('test'));

		$state = TestReflection::getValue($this->object, 'state');

		$state->expects($this->once())
			->method('start');

		$state->expects($this->once())
			->method('run');

		$this->object->start();
	}

	/**
	 * Test the start method exception.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::start
	 * @expectedException  RuntimeException
	 * @since   1.0
	 */
	public function testStartException()
	{
		// No Petri net set.
		$this->object->start();
	}

	/**
	 * Stop the execution.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::stop
	 * @since   1.0
	 */
	public function testStop()
	{
		$state = TestReflection::getValue($this->object, 'state');

		$state->expects($this->once())
			->method('stop');

		$this->object->stop();
	}

	/**
	 * Pause the execution.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::pause
	 * @since   1.0
	 */
	public function testPause()
	{
		$state = TestReflection::getValue($this->object, 'state');

		$state->expects($this->once())
			->method('pause');

		$this->object->pause();
	}

	/**
	 * End the execution.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::end
	 * @since   1.0
	 */
	public function testEnd()
	{
		$state = TestReflection::getValue($this->object, 'state');

		$state->expects($this->once())
			->method('end');

		$this->object->end();
	}

	/**
	 * Resume the execution.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::resume
	 * @since   1.0
	 */
	public function testResume()
	{
		// Set a Petri Net for execution.
		TestReflection::setValue($this->object, 'net', new PNPetrinet('test'));

		$state = TestReflection::getValue($this->object, 'state');

		$state->expects($this->once())
			->method('resume');

		$state->expects($this->once())
			->method('run');

		$this->object->resume();
	}

	/**
	 * Test the resume method exception.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::start
	 * @expectedException  RuntimeException
	 * @since   1.0
	 */
	public function testResumeException()
	{
		// No Petri net set.
		$this->object->start();
	}

	/**
	 * Main execution method.
	 * After each execution step, the state eventually modifies the engine state
	 * and pass back the execution to the Engine.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::run
	 * @since   1.0
	 */
	public function testRun()
	{
		$state = TestReflection::getValue($this->object, 'state');

		$state->expects($this->once())
			->method('run');

		$this->object->run();
	}

	/**
	 * Refresh the enabled Transitions.
	 *
	 * @return  array  An array of enabled Transitions.
	 *
	 * @covers  PNEngine::refresh
	 * @since   1.0
	 */
	public function testRefresh()
	{
		// Mock the Petri net.
		$mockedNet = $this->getMock('PNPetrinet', array('getTransitions'), array('test'));

		// Create 3 mocked transitions. 2 of them enabled.
		$transition1 = $this->getMock('PNTransition');
		$transition1->expects($this->once())
			->method('isEnabled')
			->will($this->returnValue(true));

		$transition2 = $this->getMock('PNTransition');
		$transition2->expects($this->once())
			->method('isEnabled')
			->will($this->returnValue(false));

		$transition3 = $this->getMock('PNTransition');
		$transition3->expects($this->once())
			->method('isEnabled')
			->will($this->returnValue(true));

		$mockedNet->expects($this->once())
			->method('getTransitions')
			->will($this->returnValue(array($transition1, $transition2, $transition3)));

		// Inject the Petri net.
		TestReflection::setValue($this->object, 'net', $mockedNet);

		$enabledTransitions = $this->object->refresh();

		// Check the $transition2 is not present because not enabled.
		$this->assertEquals($enabledTransitions[0], $transition1);
		$this->assertEquals($enabledTransitions[1], $transition3);
	}

	/**
	 * Set a Petri Net for execution.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::setNet
	 * @since   1.0
	 */
	public function testSetNet()
	{
		$net = new PNPetrinet('test');
		$this->object->setNet($net);
		$this->assertEquals($net, TestReflection::getValue($this->object, 'net'));
	}

	/**
	 * Tests the error thrown by the PNEngine::setNet method.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::setNet
	 *
	 * @since   1.0
	 *
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetNetError()
	{
		$this->object->setNet(new stdClass);
	}

	/**
	 * Get the executing Petri Net.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::getNet
	 * @since   1.0
	 */
	public function testGetNet()
	{
		$this->assertNull($this->object->getNet());

		TestReflection::setValue($this->object, 'net', true);

		$this->assertTrue($this->object->getNet());
	}

	/**
	 * Check if the engine has a Petri Net set for execution.
	 *
	 * @return  boolean  True if a Petri Net is set, false otherwise.
	 *
	 * @covers  PNEngine::hasNet
	 * @since   1.0
	 */
	public function testHasNet()
	{
		$this->assertFalse($this->object->hasNet());

		TestReflection::setValue($this->object, 'net', new PNPetrinet('test'));

		$this->assertTrue($this->object->hasNet());
	}

	/**
	 * Set the State of this Engine.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::setState
	 * @since   1.0
	 */
	public function testSetState()
	{
		$state = $this->getMockForAbstractClass('PNEngineState', array($this->object));

		$this->object->setState($state);

		$this->assertEquals($state, TestReflection::getValue($this->object, 'state'));
	}

	/**
	 * Tests the error thrown by the PNEngine::setState method.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::setState
	 *
	 * @since   1.0
	 *
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetStateError()
	{
		$this->object->setState(new stdClass);
	}

	/**
	 * Get the State of this Engine.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::getState
	 * @since   1.0
	 */
	public function testGetState()
	{
		TestReflection::setValue($this->object, 'state', true);

		$this->assertTrue($this->object->getState());
	}

	/**
	 * Check if the Engine is started.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::isStarted
	 * @since   1.0
	 */
	public function testIsStarted()
	{
		$this->assertFalse($this->object->isStarted());

		$started = new PNEngineStateStarted($this->object);
		TestReflection::setValue($this->object, 'state', $started);

		$this->assertTrue($this->object->isStarted());
	}

	/**
	 * Check if the engine is resumed.
	 * Resumed means re-started after a pause and running.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::isResumed
	 * @since   1.0
	 */
	public function testIsResumed()
	{
		$this->assertFalse($this->object->isResumed());

		$resumed = new PNEngineStateResumed($this->object);
		TestReflection::setValue($this->object, 'state', $resumed);

		$this->assertTrue($this->object->isResumed());
	}

	/**
	 * Check if the engine execution is ended.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::hasEnded
	 * @since   1.0
	 */
	public function testHasEnded()
	{
		$this->assertFalse($this->object->hasEnded());

		$ended = new PNEngineStateEnded($this->object);
		TestReflection::setValue($this->object, 'state', $ended);

		$this->assertTrue($this->object->hasEnded());
	}

	/**
	 * Check if the engine is paused.
	 * Paused is a state where there are no currently enabled transitions.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::isPaused
	 * @since   1.0
	 */
	public function testIsPaused()
	{
		$this->assertFalse($this->object->isPaused());

		$paused = new PNEngineStatePaused($this->object);
		TestReflection::setValue($this->object, 'state', $paused);

		$this->assertTrue($this->object->isPaused());
	}

	/**
	 * Check if the engine is stopped.
	 * Stopped means that he can only re-start from the start node.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::isStopped
	 * @since   1.0
	 */
	public function testIsStopped()
	{
		$this->assertFalse($this->object->isStopped());

		$stopped = new PNEngineStateStopped($this->object);
		TestReflection::setValue($this->object, 'state', $stopped);

		$this->assertTrue($this->object->isStopped());
	}

	/**
	 * Get the Started State instance.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::getStartedState
	 * @since   1.0
	 */
	public function testGetStartedState()
	{
		$this->assertInstanceOf('PNEngineStateStarted', $this->object->getStartedState());

		TestReflection::setValue($this->object, 'startedState', true);
		$this->assertTrue($this->object->getStartedState());
	}

	/**
	 * Get the Stopped State instance.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::getStoppedState
	 * @since   1.0
	 */
	public function testGetStoppedState()
	{
		$this->assertInstanceOf('PNEngineStateStopped', $this->object->getStoppedState());

		TestReflection::setValue($this->object, 'stoppedState', true);
		$this->assertTrue($this->object->getStoppedState());
	}

	/**
	 * Get the Paused State instance.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::getPausedState
	 * @since   1.0
	 */
	public function testGetPausedState()
	{
		$this->assertInstanceOf('PNEngineStatePaused', $this->object->getPausedState());

		TestReflection::setValue($this->object, 'pausedState', true);
		$this->assertTrue($this->object->getPausedState());
	}

	/**
	 * Get the Resumed State instance.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::getResumedState
	 * @since   1.0
	 */
	public function testGetResumedState()
	{
		$this->assertInstanceOf('PNEngineStateResumed', $this->object->getResumedState());

		TestReflection::setValue($this->object, 'resumedState', true);
		$this->assertTrue($this->object->getResumedState());
	}

	/**
	 * Get the Ended State instance.
	 *
	 * @return  void
	 *
	 * @covers  PNEngine::getEndedState
	 * @since   1.0
	 */
	public function testGetEndedState()
	{
		$this->assertInstanceOf('PNEngineStateEnded', $this->object->getEndedState());

		TestReflection::setValue($this->object, 'endedState', true);
		$this->assertTrue($this->object->getEndedState());
	}
}
