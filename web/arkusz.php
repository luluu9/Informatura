<!DOCTYPE html>
<?php 
include("php/functions.php");
$sheet_id =  _GET('arkusz');
$sheet = new Sheet($sheet_id);
$title = "Arkusz {$sheet->year}, matura {$sheet->description}";
$desc = "Rozwiązanie arkusza z roku {$sheet->year} (matura {$sheet->description}) z wytłumaczeniem każdego zadania.";
?>

<html lang="pl-PL">

<head>
    <?php include('php/meta.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/solutions_page.css" media="all">
</head>

<body>
    <?php include('php/header.php') ?>
    <main>
        <div id="big-title" class="center">
            <h1>
                <?php 
                echo $title;
                ?>
            </h1>
        </div>
        <div id="content" class="main-width">
             <?php
                foreach($sheet->get_problems() as $problem) {
                    print_problem($problem);
                }
            ?>
		<div id="disqus_thread"></div>
		<script>

		var disqus_config = function () {
		this.page.url = <?php echo "informatura.pl/arkusz/" . $sheet_id ?>;   // Replace PAGE_URL with your page's canonical URL variable
		this.page.identifier = <?php echo $sheet_id ?>; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
		};

		(function() { // DON'T EDIT BELOW THIS LINE
		var d = document, s = d.createElement('script');
		s.src = 'https://informatura.disqus.com/embed.js';
		s.setAttribute('data-timestamp', +new Date());
		(d.head || d.body).appendChild(s);
		})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
						
        </div>
    </main>
    <?php include('php/footer.php')?>
	            
</body>
</html>