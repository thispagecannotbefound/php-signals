php-signals
===========

PHP Signals & Slots library inspired by [Robert Penner's AS3 Signals](https://github.com/robertpenner/as3-signals).


Usage
-----

Basic Signal example:

	$signal = new Signal();

	$signal->add(function($value) {
		echo 'Received value: ' . $value;
	});

	$signal->dispatch('Hello World!');

	// Output: "Received value: Hello World!"
