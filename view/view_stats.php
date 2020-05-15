<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Hack overFlow</title>

    <base href="<?= $web_root ?>" />
    
    <!-- Bootstrap core CSS + fontawesome -->    
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/myStyle.css" rel="stylesheet">
    <link href="css/fontawesome/fontawesome-free-5.12.0-web/css/all.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>
    <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script>
        let chartBalise;
        let chart;
        let number;
        let time;
        let usersName;
        let userName;
        let sumActions;
        let historic;
        let dataHistoric;

        $(function(){
          chartBalise = $("#chart");
          number = $("#select1").children("option:selected").val();
          time = $("#select2").children("option:selected").val();
          historic = $("#historic");
          createChartData();
          configActions();
        });

        function configActions(){
          $("#select1").change(function(){
            number = $(this).children("option:selected").val();
            time = $("#select2").children("option:selected").val();
            createChartData();
            removeHistoricTable();
          });
          $("#select2").change(function(){
            number = $("#select1").children("option:selected").val();
            time = $(this).children("option:selected").val();
            createChartData();
            removeHistoricTable();
          });
        }

        function createChart(){
          chart = new Chart(chartBalise, {
            type:'bar',
            data: {
                labels: usersName,
                datasets:[{
                  data: sumActions,
                  backgroundColor:'rgba(251, 255, 0, 0.5)',
                  hoverBorderWidth:2,
                  hoverBorderColor:"rgb(130, 130, 130)",
                }]
            },
            options:{
              events: ['mousemove', 'click'], 
              onClick: (evt, item) => {
                userName = chart.data.labels[item[0]._index];
                displayDetailsActivity();
              },
              scales: {
                yAxes: [{
                  ticks: {min: 0
                  }
                }]
              },
              title:{
                display:true,
                text:'Most active members',
                fontSize:12
              },
              legend: {
                display: false
              }
            } 
          });
        }

        function createChartData(){
          $.get("user/get_stats_service/"+number+"/"+time+"/", function(data){
            data = JSON.parse(data.replace(/\r?\n|\r/g, ''));
            usersName = [];
            sumActions = [];
            for(i = 0; i < Object.keys(data).length; ++i){
              usersName.push(data[i].userName);
              sumActions.push(data[i].sumActions);
            }
            if(chart == undefined){
              createChart();
            }else{
              chart.data.labels = usersName;
              chart.data.datasets[0].data = sumActions;
              chart.update();
            }
          });
        }

        function displayDetailsActivity(){
         $.get("user/get_details_activity_service/"+number+"/"+time+"/"+userName+"/", function(data){
          data = JSON.parse(data.replace(/\r?\n|\r/g, ''));
          dataHistoric = data;
          buildHistoricTable();
         });
        }
        
        function buildHistoricTable(){
          html = "<h4>Detail activity for "+dataHistoric[0].user+"</h4>"+
                 "<table class=\"table table-striped\">"+
                 "<thead class=\"thead-dark\">"+
                 "<tr>"+
                 "<th scope=\"col\">Moment</th>"+
                 "<th scope=\"col\">Type</th>"+
                 "<th scope=\"col\">Question</th>"+
                 "</tr>"+
                 "</thead>"+
                 "<tbody>";
                 for(let question of dataHistoric){
                 html += "<tr>"+
                         "<td>"+question.timestamp+"</td>"+
                         "<td>create/edit "+question.type+"</td>"+
                         "<td>"+(question.title.length > 90 ? (question.title.substring(0, 90)+"...") : question.title)+"</td>"+
                         "</tr>";
                 }
          html +="</tbody>"+
                 "</table>";
          historic.html(html);       
        }

        function removeHistoricTable(){
          historic.html("");
        }
    </script>
    
  </head>
  <body>
    <?php
      $active = 'stats';
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <div style='text-align: center;'>
        <h4 style="display: inline;">Period : Last </h4>
        <select id="select1">
          <?php for($i = 1; $i <= 99; ++$i): ?>
            <option value=<?= $i ?>><?= $i?></option>
          <?php endfor ?>
        </select>
        <select id="select2">
          <option value="day">Day(s)</option>
          <option value="week">Week(s)</option>
          <option value="month">Month(s)</option>
          <option value="year">Year(s)</option>
        </select>
      </div>
      <br>

      <canvas id="chart"></canvas>
      <br>      

      <div id="historic">
      </div>

    </main>
  </body>
</html>
