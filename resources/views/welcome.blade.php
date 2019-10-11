<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Setfive Jokes</title>
    
    
  </head>
  <body>
    <div class="container">
    <div class="content-section mt-5">
		<form>
            <div class="input-group mb-3 w-50">
                <input type="text" id="searchField" class="form-control" placeholder="Search for a joke" aria-label="Search for a joke" aria-describedby="button-addon2">
                <div class="input-group-append">
                    <button id="searchButton" class="btn btn-outline-primary" type="button" id="button-addon2">Search</button>
                </div>
            </div>
		</form>
        <div id="jumbo" class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Welcome to setfive jokes</h1>
                <p class="lead">Search for jokes here and try not to laugh!</p>
            </div>
        </div>
        <div id="result" class="list-group">
        </div>
	</div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $("#searchButton").click(findJokes);
        });

        function findJokes() {
            var term = $("#searchField").val();
            $( "#result" ).empty();
            $.get( "/api/v1.0/search/" + term, function( data ) {
            var item = '';
            if(data.result && data.result.length != 0) {
                $('#jumbo').hide();
                data.result.forEach(function(joke) {

                    item ='<a href="/view-joke/'+joke.id+'" class="list-group-item list-group-item-action"><div class="d-flex w-100 justify-content-between"><small>Average rating: '+ (joke.average_rating?joke.average_rating : 'Not rated yet. Be the first to rate this joke.') + ' </small></div><p class="mb-1">'+ joke.joke +'</p></a>';
                    
                    $( "#result" ).append( item );
                }) 
            }
            // alert( "Load was performed." );
            });
        }
    </script>
  
  </body>
</html>