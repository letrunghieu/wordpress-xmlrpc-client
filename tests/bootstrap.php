<?php

if (!file_exists(__DIR__ . "/../vendor/autoload.php"))
{
	die(
			"\n[ERROR] You need to run composer before running the test suite.\n" .
			"To do so run the following commands:\n" .
			"    curl -s http://getcomposer.org/installer | php\n" .
			"    php composer.phar install\n\n"
	);
}

require_once __DIR__ . '/../vendor/autoload.php';

$useCustomInfo	 = false;
$testConfig		 = \Symfony\Component\Yaml\Yaml::parse('tests/xmlrpc.yml');
if ($testConfig['endpoint'] && $testConfig['admin_login'] && $testConfig['admin_password'] && $testConfig['guest_login'] && $testConfig['guest_password'])
{
	$useCustomInfo = true;
}
if (!$useCustomInfo)
{
	\VCR\VCR::configure()->enableLibraryHooks(array('stream_wrapper', 'curl'));
}
else
{
	VCR\VCR::configure()->enableLibraryHooks(array());
}
