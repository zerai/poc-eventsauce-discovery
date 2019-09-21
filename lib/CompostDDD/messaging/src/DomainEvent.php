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

namespace CompostDDD\Messaging;

/**
 * This is the base class for domain events.
 */
abstract class DomainEvent extends DomainMessage
{
    public function messageType(): string
    {
        return self::TYPE_EVENT;
    }
}
