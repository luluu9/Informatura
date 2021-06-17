<?php
require_once('VirusTotalApiV2.php');

/* Initialize the VirusTotalApi class. */
$api = new VirusTotalAPIV2(getenv('virustotal_key'));

/* Upload and scan a local file. */
$result = $api->scanFile('D:/INFORMATURA/strona/v2/web/php/footer.php');
$scanId = $api->getScanID($result); /* Can be used to check for the report later on. */
$api->displayResult($result);


/* Get a file report. */
$report = $api->getFileReport('1fea9de93f11c464cee923d5d5eae48a07c06426fe429d692b92bc3e91964e02-1623956603');
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