<?php

declare(strict_types=1);

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Iterator;

use Iterator;

/**
 * Class \Hoa\Iterator\Lookahead.
 *
 * Look ahead iterator.
 */
class Lookahead extends IteratorIterator implements Outer
{
    /**
     * Current iterator.
     */
    protected $_iterator = null;

    /**
     * Current key.
     */
    protected $_key      = 0;

    /**
     * Current value.
     */
    protected $_current  = null;

    /**
     * Whether the current element is valid or not.
     */
    protected $_valid    = false;



    /**
     * Construct.
     */
    public function __construct(iterable $iterator)
    {
        $this->_iterator = $iterator;

        return;
    }

    /**
     * Get inner iterator.
     */
    public function getInnerIterator(): ?Iterator
    {
        return $this->_iterator;
    }

    /**
     * Return the current element.
     */
    public function current(): mixed
    {
        return $this->_current;
    }

    /**
     * Return the key of the current element.
     */
    public function key(): mixed
    {
        return $this->_key;
    }

    /**
     * Move forward to next element.
     */
    public function next(): void
    {
        $innerIterator = $this->getInnerIterator();
        $this->_valid  = $innerIterator->valid();

        if (false === $this->_valid) {
            return;
        }

        $this->_key     = $innerIterator->key();
        $this->_current = $innerIterator->current();

        $innerIterator->next();
    }

    /**
     * Rewind the iterator to the first element.
     */
    public function rewind(): void
    {
        $this->getInnerIterator()->rewind();
        $this->next();
    }

    /**
     * Check if current position is valid.
     */
    public function valid(): bool
    {
        return $this->_valid;
    }

    /**
     * Check whether there is a next element.
     */
    public function hasNext(): bool
    {
        return $this->getInnerIterator()->valid();
    }

    /**
     * Get next value.
     */
    public function getNext()
    {
        return $this->getInnerIterator()->current();
    }

    /**
     * Get next key.
     */
    public function getNextKey()
    {
        return $this->getInnerIterator()->key();
    }
}
