## Reasons to fork
The Hoa project was archived, and no upgrades or patches are neither provided nor accepted by merge requests.

HOA Packages include some code that is Deprecated for PHP 8.0 and PHP 8.1, but we needed these packages to run on modern PHP versions.

## What's changed in from?
The changes mainly affected the return data type hinting in methods declaration, access to uninitialized properties.

## How to use
We've currently forked the following packages, primarily to make hoa/ruler work with PHP 8.1:

<table>
<tr><th>Original package</th><th>Forked package</th></tr>
<tr><td>hoa/ruler</td><td>hiqdev/hoa-ruler</td></tr>
<tr><td>hoa/compiler</td><td>hiqdev/hoa-compiler</td></tr>
<tr><td>hoa/protocol</td><td>hiqdev/hoa-protocol</td></tr>
<tr><td>hoa/iterator</td><td>hiqdev/hoa-iterator</td></tr>
</table>
You can simply replace requirements in composer.json from hoa packages to the corresponding forked packages: there is no need to change something in the codebase.

If you use someone's package, that requires hoa – simply add forks to your project root composer.json: we have marked forks as a replacement, so composer will install them instead of the original packages.

## Versions
We've forked from the latest hoa package versions and bump own versions starting from 1.0.

## Testing

Before running the test suites, the development dependencies must be installed:

```sh
$ composer install
```

Then, to run all the test suites:

```sh
$ vendor/bin/hoa test:run
```

For more information, please read the [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html).

## Quick usage

We propose a quick overview of all iterators.

### The One

`Hoa\Iterator\Iterator` defines the basis of an iterator. It extends
[`Iterator`](http://php.net/class.iterator).

### External iterator

`Hoa\Iterator\Aggregate` allows a class to use an external iterator through the
`getIterator` method. It extends
[`IteratorAggregate`](http://php.net/iteratoraggregate)

### Traversable to iterator

`Hoa\Iterator\IteratorIterator` transforms anything that is
[traversable](http://php.net/traversable) into an iterator. It extends
[`IteratorIterator`](http://php.net/iteratoriterator).

### Iterator of iterators

`Hoa\Iterator\Outer` represents an iterator that iterates over iterators. It
extends [`OuterIterator`](http://php.net/outeriterator).

### Mock

`Hoa\Iterator\Mock` represents an empty iterator. It extends
[`EmptyIterator`](http://php.net/emptyiterator).

### Seekable

`Hoa\Iterator\Seekable` represents an iterator that can be seeked. It extends
[`SeekableIterator`](http://php.net/seekableiterator).

### Map

`Hoa\Iterator\Map` allows to iterate an array. It extends
[`ArrayIterator`](http://php.net/arrayiterator).

```php
$foobar = new Hoa\Iterator\Map(['f', 'o', 'o', 'b', 'a', 'r']);

foreach ($foobar as $value) {
    echo $value;
}

/**
 * Will output:
 *     foobar
 */
```

### Filters

`Hoa\Iterator\Filter` and `Hoa\Iterator\CallbackFilter` allows to filter the
content of an iterator. It extends
[`FilterIterator`](http://php.net/filteriterator) and
[`CallbackFilterIterator`](http://php.net/callbackfilteriterator).

```php
$filter = new Hoa\Iterator\CallbackFilter(
    $foobar,
    function ($value, $key, $iterator) {
        return false === in_array($value, ['a', 'e', 'i', 'o', 'u']);
    }
);

foreach ($filter as $value) {
    echo $value;
}

/**
 * Will output:
 *     fbr
 */
```

Also, `Hoa\Iterator\RegularExpression` allows to filter based on a regular
expression.

### Limit

`Hoa\Iterator\Limit` allows to iterate *n* elements of an iterator starting from
a specific offset. It extends [`LimitIterator`](http://php.net/limititerator).

```php
$limit = new Hoa\Iterator\Limit($foobar, 2, 3);

foreach ($limit as $value) {
    echo $value;
}

/**
 * Will output:
 *     oba
 */
```

### Infinity

`Hoa\Iterator\Infinite` allows to iterate over and over again the same iterator.
It extends [`InfiniteIterator`](http://php.net/infiniteiterator).

```php
$infinite = new Hoa\Iterator\Infinite($foobar);
$limit    = new Hoa\Iterator\Limit($infinite, 0, 21);

foreach ($limit as $value) {
    echo $value;
}

/**
 * Will output:
 *     foobarfoobarfoobarfoo
 */
```

Also, `Hoa\Iterator\NoRewind` is an iterator that does not rewind. It extends
[`NoRewindIterator`](http://php.net/norewinditerator).

### Repeater

`Hoa\Iterator\Repeater` allows to repeat an iterator *n* times.

```php
$repeater = new Hoa\Iterator\Repeater(
    $foobar,
    3,
    function ($i) {
        echo "\n";
    }
);

foreach ($repeater as $value) {
    echo $value;
}

/**
 * Will output:
 *     foobar
 *     foobar
 *     foobar
 */
```

### Counter

`Hoa\Iterator\Counter` is equivalent to a `for($i = $from, $i < $to, $i +=
$step)` loop.

```php
$counter = new Hoa\Iterator\Counter(0, 12, 3);

foreach ($counter as $value) {
    echo $value, ' ';
}

/**
 * Will output:
 *     0 3 6 9
 */
```

### Union of iterators

`Hoa\Iterator\Append` allows to iterate over iterators one after another. It
extends [`AppendIterator`](http://php.net/appenditerator).

```php
$counter1 = new Hoa\Iterator\Counter(0, 12, 3);
$counter2 = new Hoa\Iterator\Counter(13, 23, 2);
$append   = new Hoa\Iterator\Append();
$append->append($counter1);
$append->append($counter2);

foreach ($append as $value) {
    echo $value, ' ';
}

/**
 * Will output:
 *     0 3 6 9 13 15 17 19 21 
 */
```

### Multiple

`Hoa\Iterator\Multiple` allows to iterate over several iterator at the same
times. It extends [`MultipleIterator`](http://php.net/multipleiterator).

```php
$foobar   = new Hoa\Iterator\Map(['f', 'o', 'o', 'b', 'a', 'r']);
$baz      = new Hoa\Iterator\Map(['b', 'a', 'z']);
$multiple = new Hoa\Iterator\Multiple(
    Hoa\Iterator\Multiple::MIT_NEED_ANY
  | Hoa\Iterator\Multiple::MIT_KEYS_ASSOC
);
$multiple->attachIterator($foobar, 'one', '!');
$multiple->attachIterator($baz,    'two', '?');

foreach ($multiple as $iterators) {
    echo $iterators['one'], ' | ', $iterators['two'], "\n";
}

/**
 * Will output:
 *     f | b
 *     o | a
 *     o | z
 *     b | ?
 *     a | ?
 *     r | ?
 */
```

### Demultiplexer

`Hoa\Iterator\Demultiplexer` demuxes result from another iterator. This iterator
is somehow the opposite of the `Hoa\Iterator\Multiple` iterator.

```php
$counter  = new Hoa\Iterator\Counter(0, 10, 1);
$multiple = new Hoa\Iterator\Multiple();
$multiple->attachIterator($counter);
$multiple->attachIterator(clone $counter);
$demultiplexer = new Hoa\Iterator\Demultiplexer(
    $multiple,
    function ($current) {
        return $current[0] * $current[1];
    }
);

foreach ($demultiplexer as $value) {
    echo $value, ' ';
}

/**
 * Will output:
 *     0 1 4 9 16 25 36 49 64 81 
 */
```

### File system

`Hoa\Iterator\Directory` and `Hoa\Iterator\FileSystem` allow to iterate the file
system where files are represented by instances of `Hoa\Iterator\SplFileInfo`.
They respectively extend
[`DirectoryIterator`](http://php.net/directoryiterator),
[`FilesystemIterator`](http://php.net/filesystemiterator) and
[`SplFileInfo`](http://php.net/splfileinfo).

```php
$directory = new Hoa\Iterator\Directory(resolve('hoa://Library/Iterator'));

foreach ($directory as $value) {
    echo $value->getFilename(), "\n";
}

/**
 * Will output:
 *     .
 *     ..
 *     .State
 *     Aggregate.php
 *     Append.php
 *     CallbackFilter.php
 *     composer.json
 *     Counter.php
 *     Demultiplexer.php
 *     …
 */
```

Also, the `Hoa\Iterator\Glob` allows to iterator with the glob strategy. It
extends [`GlobIterator`](http://php.net/globiterator). Thus:

```php
$glob = new Hoa\Iterator\Glob(resolve('hoa://Library/Iterator') . '/M*.php');

foreach ($glob as $value) {
    echo $value->getFilename(), "\n";
}

/**
 * Will output:
 *     Map.php
 *     Mock.php
 *     Multiple.php
 */
```

### Look ahead

`Hoa\Iterator\Lookahead` allows to look ahead for the next element:

```php
$counter   = new Hoa\Iterator\Counter(0, 5, 1);
$lookahead = new Hoa\Iterator\Lookahead($counter);

foreach ($lookahead as $value) {
    echo $value;

    if (true === $lookahead->hasNext()) {
        echo ' (next: ', $lookahead->getNext(), ')';
    }

    echo "\n";
}

/**
 * Will output:
 *     0 (next: 1)
 *     1 (next: 2)
 *     2 (next: 3)
 *     3 (next: 4)
 *     4
 */
```

The `Hoa\Iterator\Lookbehind` also exists and allows to look behind for the
previous element.

### Buffer

`Hoa\Iterator\Buffer` allows to move forward as usual but also backward up to a
given buffer size over another iterator:

```php
$abcde  = new Hoa\Iterator\Map(['a', 'b', 'c', 'd', 'e']);
$buffer = new Hoa\Iterator\Buffer($abcde, 3);

$buffer->rewind();
echo $buffer->current(); // a

$buffer->next();
echo $buffer->current(); // b

$buffer->next();
echo $buffer->current(); // c

$buffer->previous();
echo $buffer->current(); // b

$buffer->previous();
echo $buffer->current(); // a

$buffer->next();
echo $buffer->current(); // b

/**
 * Will output:
 *     abcbab
 */
```

### Callback generator

`Hoa\Iterator\CallbackGenerator` allows to transform any callable into an
iterator. This is very useful when combined with other iterators, for instance
with `Hoa\Iterator\Limit`:

```php
$generator = new Hoa\Iterator\CallbackGenerator(function ($key) {
    return mt_rand($key, $key * 2);
});
$limit = new Hoa\Iterator\Limit($generator, 0, 10);

foreach ($limit as $value) {
    echo $value, ' ';
}

/**
 * Could output:
 *     0 2 3 4 4 7 8 10 12 18 
 */
```

### Recursive iterators

A recursive iterator is an iterator where its values is other iterators. The
most important interface is `Hoa\Iterator\Recursive\Recursive` (it extends
[`RecursiveIterator`](http://php.net/recursiveiterator)). Then we find (in
alphabetic order):

  * `Hoa\Iterator\Recursive\CallbackFilter` (it extends
    [`RecursiveCallbackFilterIterator`](http://php.net/recursivecallbackfilteriterator)),
  * `Hoa\Iterator\Recursive\Directory` (it extends
    [`RecursiveDirectoryIterator`](http://php.net/recursivedirectoryiterator)),
  * `Hoa\Iterator\Recursive\Filter` (it extends
    [`RecursiveFilterIterator`](http://php.net/recursivefilteriterator)),
  * `Hoa\Iterator\Recursive\Iterator` (it extends
    [`RecursiveIteratorIterator`](http://php.net/recursiveiteratoriterator)),
  * `Hoa\Iterator\Recursive\Map` (it extends
    [`RecursiveArrayIterator`](http://php.net/recursivearrayiterator)),
  * `Hoa\Iterator\Recursive\Mock`,
  * `Hoa\Iterator\Recursive\RegularExpression`
    (it extends [`RecursiveRegularExpression`](http://php.net/recursiveregexiterator)).

## Documentation

The
[hack book of `Hoa\Iterator`](https://central.hoa-project.net/Documentation/Library/Iterator) contains
detailed information about how to use this library and how it works.

To generate the documentation locally, execute the following commands:

```sh
$ composer require --dev hoa/devtools
$ vendor/bin/hoa devtools:documentation --open
```

More documentation can be found on the project's website:
[hoa-project.net](https://hoa-project.net/).

## Getting help

There are mainly two ways to get help:

  * On the [`#hoaproject`](https://webchat.freenode.net/?channels=#hoaproject)
    IRC channel,
  * On the forum at [users.hoa-project.net](https://users.hoa-project.net).

## Contribution

Do you want to contribute? Thanks! A detailed [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html) explains
everything you need to know.

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](https://hoa-project.net/LICENSE) for details.
