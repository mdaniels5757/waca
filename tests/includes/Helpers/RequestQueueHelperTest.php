<?php
/******************************************************************************
 * Wikipedia Account Creation Assistance tool                                 *
 *                                                                            *
 * All code in this file is released into the public domain by the ACC        *
 * Development Team. Please see team.json for a list of contributors.         *
 ******************************************************************************/

namespace includes\Helpers;

use PHPUnit\Framework\TestCase;
use Waca\DataObjects\RequestQueue;
use Waca\Helpers\RequestQueueHelper;

class RequestQueueHelperTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testConfigureDefaults(
        bool $oldEnabled,
        bool $newEnabled,
        bool $oldDefault,
        bool $newDefault,
        bool $oldDefaultAntispoof,
        bool $newDefaultAntispoof,
        bool $oldDefaultTitleBlacklist,
        bool $newDefaultTitleBlacklist,
        bool $expectedEnabled,
        bool $expectedDefault,
        bool $expectedAntispoof,
        bool $expectedTitleBlacklist
    ) {
        // arrange
        $queue = new RequestQueue();
        $queue->setEnabled($oldEnabled);
        $queue->setDefault($oldDefault);
        $queue->setDefaultAntispoof($oldDefaultAntispoof);
        $queue->setDefaultTitleBlacklist($oldDefaultTitleBlacklist);

        $helper = new RequestQueueHelper();

        // act
        $helper->configureDefaults($queue, $newEnabled, $newDefault, $newDefaultAntispoof, $newDefaultTitleBlacklist, false);

        // assert
        $this->assertEquals($expectedEnabled, $queue->isEnabled());
        $this->assertEquals($expectedDefault, $queue->isDefault());
        $this->assertEquals($expectedAntispoof, $queue->isDefaultAntispoof());
        $this->assertEquals($expectedTitleBlacklist, $queue->isDefaultTitleBlacklist());
    }

    public function dataProvider() {
        // excel is thy friend.
        // this covers every single possible value for these flags.
        // column headers match the method parameters for the testConfigureDefault() method.
        // briefly, first eight columns are old->new for the four parameters.
        // last four columns are the end result expected value.
        return [
            [false,false,false,false,false,false,false,false,false,false,false,false],
            [false,false,false,false,false,false,false,true ,false,false,false,false],
            [false,false,false,false,false,false,true ,false,false,false,false,false],
            [false,false,false,false,false,false,true ,true ,false,false,false,false],
            [false,false,false,false,false,true ,false,false,false,false,false,false],
            [false,false,false,false,false,true ,false,true ,false,false,false,false],
            [false,false,false,false,false,true ,true ,false,false,false,false,false],
            [false,false,false,false,false,true ,true ,true ,false,false,false,false],
            [false,false,false,false,true ,false,false,false,false,false,false,false],
            [false,false,false,false,true ,false,false,true ,false,false,false,false],
            [false,false,false,false,true ,false,true ,false,false,false,false,false],
            [false,false,false,false,true ,false,true ,true ,false,false,false,false],
            [false,false,false,false,true ,true ,false,false,false,false,false,false],
            [false,false,false,false,true ,true ,false,true ,false,false,false,false],
            [false,false,false,false,true ,true ,true ,false,false,false,false,false],
            [false,false,false,false,true ,true ,true ,true ,false,false,false,false],
            [false,false,false,true ,false,false,false,false,false,false,false,false],
            [false,false,false,true ,false,false,false,true ,false,false,false,false],
            [false,false,false,true ,false,false,true ,false,false,false,false,false],
            [false,false,false,true ,false,false,true ,true ,false,false,false,false],
            [false,false,false,true ,false,true ,false,false,false,false,false,false],
            [false,false,false,true ,false,true ,false,true ,false,false,false,false],
            [false,false,false,true ,false,true ,true ,false,false,false,false,false],
            [false,false,false,true ,false,true ,true ,true ,false,false,false,false],
            [false,false,false,true ,true ,false,false,false,false,false,false,false],
            [false,false,false,true ,true ,false,false,true ,false,false,false,false],
            [false,false,false,true ,true ,false,true ,false,false,false,false,false],
            [false,false,false,true ,true ,false,true ,true ,false,false,false,false],
            [false,false,false,true ,true ,true ,false,false,false,false,false,false],
            [false,false,false,true ,true ,true ,false,true ,false,false,false,false],
            [false,false,false,true ,true ,true ,true ,false,false,false,false,false],
            [false,false,false,true ,true ,true ,true ,true ,false,false,false,false],
            [false,false,true ,false,false,false,false,false,false,false,false,false],
            [false,false,true ,false,false,false,false,true ,false,false,false,false],
            [false,false,true ,false,false,false,true ,false,false,false,false,false],
            [false,false,true ,false,false,false,true ,true ,false,false,false,false],
            [false,false,true ,false,false,true ,false,false,false,false,false,false],
            [false,false,true ,false,false,true ,false,true ,false,false,false,false],
            [false,false,true ,false,false,true ,true ,false,false,false,false,false],
            [false,false,true ,false,false,true ,true ,true ,false,false,false,false],
            [false,false,true ,false,true ,false,false,false,false,false,false,false],
            [false,false,true ,false,true ,false,false,true ,false,false,false,false],
            [false,false,true ,false,true ,false,true ,false,false,false,false,false],
            [false,false,true ,false,true ,false,true ,true ,false,false,false,false],
            [false,false,true ,false,true ,true ,false,false,false,false,false,false],
            [false,false,true ,false,true ,true ,false,true ,false,false,false,false],
            [false,false,true ,false,true ,true ,true ,false,false,false,false,false],
            [false,false,true ,false,true ,true ,true ,true ,false,false,false,false],
            [false,false,true ,true ,false,false,false,false,false,false,false,false],
            [false,false,true ,true ,false,false,false,true ,false,false,false,false],
            [false,false,true ,true ,false,false,true ,false,false,false,false,false],
            [false,false,true ,true ,false,false,true ,true ,false,false,false,false],
            [false,false,true ,true ,false,true ,false,false,false,false,false,false],
            [false,false,true ,true ,false,true ,false,true ,false,false,false,false],
            [false,false,true ,true ,false,true ,true ,false,false,false,false,false],
            [false,false,true ,true ,false,true ,true ,true ,false,false,false,false],
            [false,false,true ,true ,true ,false,false,false,false,false,false,false],
            [false,false,true ,true ,true ,false,false,true ,false,false,false,false],
            [false,false,true ,true ,true ,false,true ,false,false,false,false,false],
            [false,false,true ,true ,true ,false,true ,true ,false,false,false,false],
            [false,false,true ,true ,true ,true ,false,false,false,false,false,false],
            [false,false,true ,true ,true ,true ,false,true ,false,false,false,false],
            [false,false,true ,true ,true ,true ,true ,false,false,false,false,false],
            [false,false,true ,true ,true ,true ,true ,true ,false,false,false,false],
            [false,true ,false,false,false,false,false,false,true ,false,false,false],
            [false,true ,false,false,false,false,false,true ,true ,false,false,true ],
            [false,true ,false,false,false,false,true ,false,true ,false,false,true ],
            [false,true ,false,false,false,false,true ,true ,true ,false,false,true ],
            [false,true ,false,false,false,true ,false,false,true ,false,true ,false],
            [false,true ,false,false,false,true ,false,true ,true ,false,true ,true ],
            [false,true ,false,false,false,true ,true ,false,true ,false,true ,true ],
            [false,true ,false,false,false,true ,true ,true ,true ,false,true ,true ],
            [false,true ,false,false,true ,false,false,false,true ,false,true ,false],
            [false,true ,false,false,true ,false,false,true ,true ,false,true ,true ],
            [false,true ,false,false,true ,false,true ,false,true ,false,true ,true ],
            [false,true ,false,false,true ,false,true ,true ,true ,false,true ,true ],
            [false,true ,false,false,true ,true ,false,false,true ,false,true ,false],
            [false,true ,false,false,true ,true ,false,true ,true ,false,true ,true ],
            [false,true ,false,false,true ,true ,true ,false,true ,false,true ,true ],
            [false,true ,false,false,true ,true ,true ,true ,true ,false,true ,true ],
            [false,true ,false,true ,false,false,false,false,true ,true ,false,false],
            [false,true ,false,true ,false,false,false,true ,true ,true ,false,true ],
            [false,true ,false,true ,false,false,true ,false,true ,true ,false,true ],
            [false,true ,false,true ,false,false,true ,true ,true ,true ,false,true ],
            [false,true ,false,true ,false,true ,false,false,true ,true ,true ,false],
            [false,true ,false,true ,false,true ,false,true ,true ,true ,true ,true ],
            [false,true ,false,true ,false,true ,true ,false,true ,true ,true ,true ],
            [false,true ,false,true ,false,true ,true ,true ,true ,true ,true ,true ],
            [false,true ,false,true ,true ,false,false,false,true ,true ,true ,false],
            [false,true ,false,true ,true ,false,false,true ,true ,true ,true ,true ],
            [false,true ,false,true ,true ,false,true ,false,true ,true ,true ,true ],
            [false,true ,false,true ,true ,false,true ,true ,true ,true ,true ,true ],
            [false,true ,false,true ,true ,true ,false,false,true ,true ,true ,false],
            [false,true ,false,true ,true ,true ,false,true ,true ,true ,true ,true ],
            [false,true ,false,true ,true ,true ,true ,false,true ,true ,true ,true ],
            [false,true ,false,true ,true ,true ,true ,true ,true ,true ,true ,true ],
            [false,true ,true ,false,false,false,false,false,true ,true ,false,false],
            [false,true ,true ,false,false,false,false,true ,true ,true ,false,true ],
            [false,true ,true ,false,false,false,true ,false,true ,true ,false,true ],
            [false,true ,true ,false,false,false,true ,true ,true ,true ,false,true ],
            [false,true ,true ,false,false,true ,false,false,true ,true ,true ,false],
            [false,true ,true ,false,false,true ,false,true ,true ,true ,true ,true ],
            [false,true ,true ,false,false,true ,true ,false,true ,true ,true ,true ],
            [false,true ,true ,false,false,true ,true ,true ,true ,true ,true ,true ],
            [false,true ,true ,false,true ,false,false,false,true ,true ,true ,false],
            [false,true ,true ,false,true ,false,false,true ,true ,true ,true ,true ],
            [false,true ,true ,false,true ,false,true ,false,true ,true ,true ,true ],
            [false,true ,true ,false,true ,false,true ,true ,true ,true ,true ,true ],
            [false,true ,true ,false,true ,true ,false,false,true ,true ,true ,false],
            [false,true ,true ,false,true ,true ,false,true ,true ,true ,true ,true ],
            [false,true ,true ,false,true ,true ,true ,false,true ,true ,true ,true ],
            [false,true ,true ,false,true ,true ,true ,true ,true ,true ,true ,true ],
            [false,true ,true ,true ,false,false,false,false,true ,true ,false,false],
            [false,true ,true ,true ,false,false,false,true ,true ,true ,false,true ],
            [false,true ,true ,true ,false,false,true ,false,true ,true ,false,true ],
            [false,true ,true ,true ,false,false,true ,true ,true ,true ,false,true ],
            [false,true ,true ,true ,false,true ,false,false,true ,true ,true ,false],
            [false,true ,true ,true ,false,true ,false,true ,true ,true ,true ,true ],
            [false,true ,true ,true ,false,true ,true ,false,true ,true ,true ,true ],
            [false,true ,true ,true ,false,true ,true ,true ,true ,true ,true ,true ],
            [false,true ,true ,true ,true ,false,false,false,true ,true ,true ,false],
            [false,true ,true ,true ,true ,false,false,true ,true ,true ,true ,true ],
            [false,true ,true ,true ,true ,false,true ,false,true ,true ,true ,true ],
            [false,true ,true ,true ,true ,false,true ,true ,true ,true ,true ,true ],
            [false,true ,true ,true ,true ,true ,false,false,true ,true ,true ,false],
            [false,true ,true ,true ,true ,true ,false,true ,true ,true ,true ,true ],
            [false,true ,true ,true ,true ,true ,true ,false,true ,true ,true ,true ],
            [false,true ,true ,true ,true ,true ,true ,true ,true ,true ,true ,true ],
            [true ,false,false,false,false,false,false,false,false,false,false,false],
            [true ,false,false,false,false,false,false,true ,false,false,false,false],
            [true ,false,false,false,false,false,true ,false,true ,false,false,true ],
            [true ,false,false,false,false,false,true ,true ,true ,false,false,true ],
            [true ,false,false,false,false,true ,false,false,false,false,false,false],
            [true ,false,false,false,false,true ,false,true ,false,false,false,false],
            [true ,false,false,false,false,true ,true ,false,true ,false,true ,true ],
            [true ,false,false,false,false,true ,true ,true ,true ,false,true ,true ],
            [true ,false,false,false,true ,false,false,false,true ,false,true ,false],
            [true ,false,false,false,true ,false,false,true ,true ,false,true ,true ],
            [true ,false,false,false,true ,false,true ,false,true ,false,true ,true ],
            [true ,false,false,false,true ,false,true ,true ,true ,false,true ,true ],
            [true ,false,false,false,true ,true ,false,false,true ,false,true ,false],
            [true ,false,false,false,true ,true ,false,true ,true ,false,true ,true ],
            [true ,false,false,false,true ,true ,true ,false,true ,false,true ,true ],
            [true ,false,false,false,true ,true ,true ,true ,true ,false,true ,true ],
            [true ,false,false,true ,false,false,false,false,false,false,false,false],
            [true ,false,false,true ,false,false,false,true ,false,false,false,false],
            [true ,false,false,true ,false,false,true ,false,true ,true ,false,true ],
            [true ,false,false,true ,false,false,true ,true ,true ,true ,false,true ],
            [true ,false,false,true ,false,true ,false,false,false,false,false,false],
            [true ,false,false,true ,false,true ,false,true ,false,false,false,false],
            [true ,false,false,true ,false,true ,true ,false,true ,true ,true ,true ],
            [true ,false,false,true ,false,true ,true ,true ,true ,true ,true ,true ],
            [true ,false,false,true ,true ,false,false,false,true ,true ,true ,false],
            [true ,false,false,true ,true ,false,false,true ,true ,true ,true ,true ],
            [true ,false,false,true ,true ,false,true ,false,true ,true ,true ,true ],
            [true ,false,false,true ,true ,false,true ,true ,true ,true ,true ,true ],
            [true ,false,false,true ,true ,true ,false,false,true ,true ,true ,false],
            [true ,false,false,true ,true ,true ,false,true ,true ,true ,true ,true ],
            [true ,false,false,true ,true ,true ,true ,false,true ,true ,true ,true ],
            [true ,false,false,true ,true ,true ,true ,true ,true ,true ,true ,true ],
            [true ,false,true ,false,false,false,false,false,true ,true ,false,false],
            [true ,false,true ,false,false,false,false,true ,true ,true ,false,true ],
            [true ,false,true ,false,false,false,true ,false,true ,true ,false,true ],
            [true ,false,true ,false,false,false,true ,true ,true ,true ,false,true ],
            [true ,false,true ,false,false,true ,false,false,true ,true ,true ,false],
            [true ,false,true ,false,false,true ,false,true ,true ,true ,true ,true ],
            [true ,false,true ,false,false,true ,true ,false,true ,true ,true ,true ],
            [true ,false,true ,false,false,true ,true ,true ,true ,true ,true ,true ],
            [true ,false,true ,false,true ,false,false,false,true ,true ,true ,false],
            [true ,false,true ,false,true ,false,false,true ,true ,true ,true ,true ],
            [true ,false,true ,false,true ,false,true ,false,true ,true ,true ,true ],
            [true ,false,true ,false,true ,false,true ,true ,true ,true ,true ,true ],
            [true ,false,true ,false,true ,true ,false,false,true ,true ,true ,false],
            [true ,false,true ,false,true ,true ,false,true ,true ,true ,true ,true ],
            [true ,false,true ,false,true ,true ,true ,false,true ,true ,true ,true ],
            [true ,false,true ,false,true ,true ,true ,true ,true ,true ,true ,true ],
            [true ,false,true ,true ,false,false,false,false,true ,true ,false,false],
            [true ,false,true ,true ,false,false,false,true ,true ,true ,false,true ],
            [true ,false,true ,true ,false,false,true ,false,true ,true ,false,true ],
            [true ,false,true ,true ,false,false,true ,true ,true ,true ,false,true ],
            [true ,false,true ,true ,false,true ,false,false,true ,true ,true ,false],
            [true ,false,true ,true ,false,true ,false,true ,true ,true ,true ,true ],
            [true ,false,true ,true ,false,true ,true ,false,true ,true ,true ,true ],
            [true ,false,true ,true ,false,true ,true ,true ,true ,true ,true ,true ],
            [true ,false,true ,true ,true ,false,false,false,true ,true ,true ,false],
            [true ,false,true ,true ,true ,false,false,true ,true ,true ,true ,true ],
            [true ,false,true ,true ,true ,false,true ,false,true ,true ,true ,true ],
            [true ,false,true ,true ,true ,false,true ,true ,true ,true ,true ,true ],
            [true ,false,true ,true ,true ,true ,false,false,true ,true ,true ,false],
            [true ,false,true ,true ,true ,true ,false,true ,true ,true ,true ,true ],
            [true ,false,true ,true ,true ,true ,true ,false,true ,true ,true ,true ],
            [true ,false,true ,true ,true ,true ,true ,true ,true ,true ,true ,true ],
            [true ,true ,false,false,false,false,false,false,true ,false,false,false],
            [true ,true ,false,false,false,false,false,true ,true ,false,false,true ],
            [true ,true ,false,false,false,false,true ,false,true ,false,false,true ],
            [true ,true ,false,false,false,false,true ,true ,true ,false,false,true ],
            [true ,true ,false,false,false,true ,false,false,true ,false,true ,false],
            [true ,true ,false,false,false,true ,false,true ,true ,false,true ,true ],
            [true ,true ,false,false,false,true ,true ,false,true ,false,true ,true ],
            [true ,true ,false,false,false,true ,true ,true ,true ,false,true ,true ],
            [true ,true ,false,false,true ,false,false,false,true ,false,true ,false],
            [true ,true ,false,false,true ,false,false,true ,true ,false,true ,true ],
            [true ,true ,false,false,true ,false,true ,false,true ,false,true ,true ],
            [true ,true ,false,false,true ,false,true ,true ,true ,false,true ,true ],
            [true ,true ,false,false,true ,true ,false,false,true ,false,true ,false],
            [true ,true ,false,false,true ,true ,false,true ,true ,false,true ,true ],
            [true ,true ,false,false,true ,true ,true ,false,true ,false,true ,true ],
            [true ,true ,false,false,true ,true ,true ,true ,true ,false,true ,true ],
            [true ,true ,false,true ,false,false,false,false,true ,true ,false,false],
            [true ,true ,false,true ,false,false,false,true ,true ,true ,false,true ],
            [true ,true ,false,true ,false,false,true ,false,true ,true ,false,true ],
            [true ,true ,false,true ,false,false,true ,true ,true ,true ,false,true ],
            [true ,true ,false,true ,false,true ,false,false,true ,true ,true ,false],
            [true ,true ,false,true ,false,true ,false,true ,true ,true ,true ,true ],
            [true ,true ,false,true ,false,true ,true ,false,true ,true ,true ,true ],
            [true ,true ,false,true ,false,true ,true ,true ,true ,true ,true ,true ],
            [true ,true ,false,true ,true ,false,false,false,true ,true ,true ,false],
            [true ,true ,false,true ,true ,false,false,true ,true ,true ,true ,true ],
            [true ,true ,false,true ,true ,false,true ,false,true ,true ,true ,true ],
            [true ,true ,false,true ,true ,false,true ,true ,true ,true ,true ,true ],
            [true ,true ,false,true ,true ,true ,false,false,true ,true ,true ,false],
            [true ,true ,false,true ,true ,true ,false,true ,true ,true ,true ,true ],
            [true ,true ,false,true ,true ,true ,true ,false,true ,true ,true ,true ],
            [true ,true ,false,true ,true ,true ,true ,true ,true ,true ,true ,true ],
            [true ,true ,true ,false,false,false,false,false,true ,true ,false,false],
            [true ,true ,true ,false,false,false,false,true ,true ,true ,false,true ],
            [true ,true ,true ,false,false,false,true ,false,true ,true ,false,true ],
            [true ,true ,true ,false,false,false,true ,true ,true ,true ,false,true ],
            [true ,true ,true ,false,false,true ,false,false,true ,true ,true ,false],
            [true ,true ,true ,false,false,true ,false,true ,true ,true ,true ,true ],
            [true ,true ,true ,false,false,true ,true ,false,true ,true ,true ,true ],
            [true ,true ,true ,false,false,true ,true ,true ,true ,true ,true ,true ],
            [true ,true ,true ,false,true ,false,false,false,true ,true ,true ,false],
            [true ,true ,true ,false,true ,false,false,true ,true ,true ,true ,true ],
            [true ,true ,true ,false,true ,false,true ,false,true ,true ,true ,true ],
            [true ,true ,true ,false,true ,false,true ,true ,true ,true ,true ,true ],
            [true ,true ,true ,false,true ,true ,false,false,true ,true ,true ,false],
            [true ,true ,true ,false,true ,true ,false,true ,true ,true ,true ,true ],
            [true ,true ,true ,false,true ,true ,true ,false,true ,true ,true ,true ],
            [true ,true ,true ,false,true ,true ,true ,true ,true ,true ,true ,true ],
            [true ,true ,true ,true ,false,false,false,false,true ,true ,false,false],
            [true ,true ,true ,true ,false,false,false,true ,true ,true ,false,true ],
            [true ,true ,true ,true ,false,false,true ,false,true ,true ,false,true ],
            [true ,true ,true ,true ,false,false,true ,true ,true ,true ,false,true ],
            [true ,true ,true ,true ,false,true ,false,false,true ,true ,true ,false],
            [true ,true ,true ,true ,false,true ,false,true ,true ,true ,true ,true ],
            [true ,true ,true ,true ,false,true ,true ,false,true ,true ,true ,true ],
            [true ,true ,true ,true ,false,true ,true ,true ,true ,true ,true ,true ],
            [true ,true ,true ,true ,true ,false,false,false,true ,true ,true ,false],
            [true ,true ,true ,true ,true ,false,false,true ,true ,true ,true ,true ],
            [true ,true ,true ,true ,true ,false,true ,false,true ,true ,true ,true ],
            [true ,true ,true ,true ,true ,false,true ,true ,true ,true ,true ,true ],
            [true ,true ,true ,true ,true ,true ,false,false,true ,true ,true ,false],
            [true ,true ,true ,true ,true ,true ,false,true ,true ,true ,true ,true ],
            [true ,true ,true ,true ,true ,true ,true ,false,true ,true ,true ,true ],
            [true ,true ,true ,true ,true ,true ,true ,true ,true ,true ,true ,true ],
        ];
    }

}