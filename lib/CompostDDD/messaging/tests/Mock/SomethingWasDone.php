<?php
/**
 * This file is part of the prooph/common.
 * (c) 2014-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CompostDDD\Tests\Messaging\Mock;

use CompostDDD\Messaging\DomainEvent;
use CompostDDD\Messaging\PayloadConstructable;
use CompostDDD\Messaging\PayloadTrait;

final class SomethingWasDone extends DomainEvent implements PayloadConstructable
{
    use PayloadTrait;
}
