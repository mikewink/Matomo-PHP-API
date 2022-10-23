<?php

declare(strict_types=1);

require(__DIR__.'/../vendor/autoload.php');
require('config.php');

use VisualAppeal\Matomo;

$matomo = new Matomo(SITE_URL, TOKEN, SITE_ID, Matomo::FORMAT_JSON);
$matomo->setLanguage('en');
$matomo->setVerifySsl(true);

$site = $matomo->getSiteInformation();

// Default time period: yesterday

$visits = $matomo->getVisits();
$visitsU = $matomo->getUniqueVisitors();
$visitsL = $matomo->getSumVisitsLengthPretty();

// Change time period to current month

$matomo->setPeriod(Matomo::PERIOD_MONTH);
$matomo->setDate(date('Y-m-d'));

$visitsMonth = $matomo->getVisits();
$visitsUMonth = $matomo->getUniqueVisitors();
$visitsLMonth = $matomo->getSumVisitsLengthPretty();

// Change time period to current year

$matomo->setPeriod(Matomo::PERIOD_YEAR);
$matomo->setDate(date('Y-m-d'));

$visitsYear = $matomo->getVisits();
$visitsUYear = '-'; //$matomo->getUniqueVisitors(); // To enable see https://matomo.org/faq/how-to/faq_113/
$visitsLYear = $matomo->getSumVisitsLengthPretty();

// Change time period to range
$matomo->setPeriod(Matomo::PERIOD_RANGE);

$matomo->setRange(
    date('Y-m-d', mktime(0, 0, 0, RANGE_START_M, RANGE_START_D, RANGE_START_Y)),
    date('Y-m-d', mktime(0, 0, 0, RANGE_END_M, RANGE_END_D, RANGE_END_Y))
);

$visitsRange = $matomo->getVisits();
$visitsURange = '-'; //$matomo->getUniqueVisitors(); // To enable see https://matomo.org/faq/how-to/faq_113/
$visitsLRange = $matomo->getSumVisitsLengthPretty();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Matomo PHP API &dash; Demo</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
</head>
<body class="bg-stone-50 h-screen lg:flex lg:justify-center lg:items-center">

<div class="prose bg-white lg:shadow-lg lg:rounded lg:m-10 p-10 lg:w-1/2">
    <h1 class="text-sky-600">Matomo PHP API</h1>

    <p>Results for the given Matomo instance in <code>config.php</code>.</p>

    <hr class="my-8">

    <h2 class="text-sky-500">General site information</h2>

    <ul>
        <li>Name: <a href="<?php echo $site->main_url ?>"><?php echo $site->name ?></a> (ID: <?php echo $site->idsite ?>)</li>
        <li>Created: <?php echo $site->ts_created ?></li>
        <li>Timezone: <?php echo $site->timezone_name ?></li>
    </ul>

    <h2 class="text-sky-500">Summary Yesterday</h2>
    <ul>
        <li>Visit count: <?php echo $visits; ?></li>
        <li>Unique visit count: <?php echo $visitsU; ?></li>
        <li>Summary of the visit lengths: <?php echo ($visitsL !== false) ? $visitsL : 0; ?></li>
    </ul>

    <h2 class="text-sky-500">Summary <?php
        echo date('F') ?></h2>
    <ul>
        <li>Visit count: <?php echo $visitsMonth; ?></li>
        <li>Unique visit count: <?php echo $visitsUMonth; ?></li>
        <li>Summary of the visit lengths: <?php echo ($visitsLMonth !== false) ? $visitsLMonth : 0; ?></li>
    </ul>

    <h2 class="text-sky-500">Summary <?php
        echo date('Y') ?></h2>
    <ul>
        <li>Visit count: <?php echo $visitsYear; ?></li>
        <li>Unique visit count: <?php echo $visitsUYear; ?></li>
        <li>Summary of the visit lengths: <?php echo ($visitsLYear !== false) ? $visitsLYear : 0; ?></li>
    </ul>

    <h2 class="text-sky-500">Summary <?php
        echo date('Y-m-d', mktime(0, 0, 0, RANGE_START_M, RANGE_START_D, RANGE_START_Y)); ?> - <?php
        echo date('Y-m-d', mktime(0, 0, 0, RANGE_END_M, RANGE_END_D, RANGE_END_Y)); ?></h2>
    <ul>
        <li>Visit count: <?php echo $visitsRange; ?></li>
        <li>Unique visit count: <?php echo $visitsURange; ?></li>
        <li>Summary of the visit lengths: <?php echo ($visitsLRange !== false) ? $visitsLRange : 0; ?></li>
    </ul>

    <hr class="my-8">

    <small>Matomo version: <?php echo $matomo->getMatomoVersion() ?? '-' ?></small>
</div>

</body>
</html>
