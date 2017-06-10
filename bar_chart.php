<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>anna's chudo bar chart</title>
    <link rel="stylesheet" type="text/css" href="bar_chart.css">

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<?php

    $wordCounts = [];
    $punctCounts = [];

    $text = $_POST["input"];
    $text2 = $text;
    $my_text = $text;

    $text2 = preg_replace("/[a-zA-Z]/", "", $text2);
    $all_punctuation = explode(' ', $text2);

    foreach ($all_punctuation as $key =>$each_punct){
        if(@$each_punct == ''){
            unset($all_punctuation[$key]);
        }
    }

    foreach ($all_punctuation as $each_punct){
        if(key_exists($each_punct, $punctCounts)){
            $punctCounts[$each_punct]++;
        } else{
            $punctCounts[$each_punct] = 1;
        }
    }

    arsort($punctCounts);


    $text = str_replace(',', '', $text);
    $text = str_replace('.', '', $text);
    $text = str_replace(':', '', $text);

    $all_words = explode(' ', $text);

    foreach ($all_words as $key => $each_word) {
        $all_words[$key] = strtolower($each_word);
    }

    foreach ($all_words as $each_word) {
        if (key_exists($each_word, $wordCounts)) {
            $wordCounts[$each_word]++;
        } else {
            $wordCounts[$each_word] = 1;
        }
    }
    arsort($wordCounts);
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center heading">
                <h3>Word and Punctuation Calculator</h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">

                <h2>insert your text here.</h2>

                <form action="bar_chart.php" method="POST">
                    <textarea placeholder="type here..." name="input" style="width:400px; height:200px;"><?php echo $my_text ?></textarea><br>
                    <input type='submit'  style="margin: 5px">
                </form>

            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div id="top_x_div" style="height: 400px;"></div>
            </div>
            <div class="col-md-6">
                <div id="piechart" style="height: 400px;"></div>
            </div>

        </div>
    </div>


    <script type="text/javascript" id="word_diagram">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
            var data = new google.visualization.arrayToDataTable([

                ['Word', 'Count'],

                <?php
                foreach ($wordCounts as $each_word => $count) {
                    echo "['" . $each_word . "', " . $count . "],";
                }
                ?>
            ]);

            var options = {
                title: 'The Words',
                width: 900,
                legend: { position: 'none' },
                chart: { title: 'Word Frequency in Text',
                },
                bars: 'horizontal',
                axes: {
                    x: {
                        0: { side: 'top', label: 'frequency'}
                    }
                },
                bar: { groupWidth: "90%" }
            };

            var chart = new google.charts.Bar(document.getElementById('top_x_div'));
            chart.draw(data, options);
        };
    </script>

    <script type="text/javascript" id="punctuation_diagram">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([

                ['Punctuation', 'Count'],

                <?php
                foreach ($punctCounts as $each_punct => $count) {
                    echo "['" . $each_punct . "', " . $count . "],";
                }
                ?>
            ]);

            var options = {
                title: 'The Punctuation'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>

</body>
</html>
