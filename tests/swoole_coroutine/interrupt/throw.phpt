--TEST--
swoole_coroutine: throw exception to the other coroutine
--SKIPIF--
<?php require __DIR__ . '/../../include/skipif.inc'; ?>
--FILE--
<?php
require __DIR__ . '/../../include/bootstrap.php';

$sleep = go(function () {
    co::sleep(999);
    echo "never here\n";
});

$socket_io = go(function () {
    $socket = new  Co\Socket(AF_INET, SOCK_DGRAM, 0);
    $ret = $socket->recvfrom($peer, -1);
    var_dump($ret);
});

go(function () use ($sleep, $socket_io) {
    co::throw($sleep);
    co::throw($socket_io);
});

?>
--EXPECTF--
Warning: Uncaught Swoole\Coroutine\Exception: the coroutine was interrupted by an exception from cid#3. in %s/tests/swoole_coroutine/interrupted/throw.php:5
Stack trace:
#0 %s/tests/swoole_coroutine/interrupted/throw.php(5): Swoole\Coroutine::sleep(999)
#1 {main}
  thrown in %s/tests/swoole_coroutine/interrupted/throw.php on line 5

Warning: Uncaught Swoole\Coroutine\Exception: the coroutine was interrupted by an exception from cid#3. in %s/tests/swoole_coroutine/interrupted/throw.php:11
Stack trace:
#0 %s/tests/swoole_coroutine/interrupted/throw.php(11): Swoole\Coroutine\Socket->recvfrom(NULL, -1)
#1 {main}
  thrown in %s/tests/swoole_coroutine/interrupted/throw.php on line 11