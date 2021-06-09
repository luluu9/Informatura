<?php
require_once('VirusTotalApiV2.php');

/* Initialize the VirusTotalApi class. */
$api = new VirusTotalAPIV2(getenv('virustotal_key'));

/* Upload and scan a local file. */
$result = $api->scanFile('D:/INFORMATURA/strona/v2/web/php/footer.php');
$scanId = $api->getScanID($result); /* Can be used to check for the report later on. */
$api->displayResult($result);


/* Get a file report. */
$report = $api->getFileReport('f2733c04b052e3af9f5e99b9b30b4d4470fadf1581977e654e4747b63d88d21a');
$api->displayResult($report);
print($api->getSubmissionDate($report) . '<br>');
print($api->getReportPermalink($report, TRUE) . '<br>');

// /* Scan an URL. */
// $result = $api->scanURL('URL-to-scan');
// $scanId = $api->getScanID($result); /* Can be used to check for the report later on. */
// $api->displayResult($result);

// /* Get an URL report. */
// $report = $api->getURLReport('URL-to-check-for-a-report');
// $api->displayResult($report);
// print($api->getTotalNumberOfChecks($report) . '<br>');
// print($api->getNumberHits($report) . '<br>');
// print($api->getReportPermalink($report, FALSE) . '<br>');

// /* Comment on a file. */
// $report = $api->makeComment('Hash-of-a-file-to-comment-on', 'Your-comment');
// $api->displayResult($report);

// /* Comment on an URL. */
// $report = $api->makeComment('URL-to-comment-on', 'Your-comment');
// $api->displayResult($report);
?>