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

    <div class="container mt-5">
        <div class="content-section">
            <div class="card mb-4">
                <div id="jokeCard" class="card-body">
                </div>
                <ul id="comments" class="list-group list-group-flush">
                </ul>
            </div>
            <form>
                <div class="btn-toolbar form-group" role="toolbar" aria-label="Toolbar with button groups">
                    <label for="exampleFormControlTextarea1">Rate this joke:</label>
                    <div class="btn-group ml-2" role="group" aria-label="First group">
                        <button type="button" value="1" class="rateButton btn btn-secondary">1</button>
                        <button type="button" value="2" class="rateButton btn btn-secondary">2</button>
                        <button type="button" value="3" class="rateButton btn btn-secondary">3</button>
                        <button type="button" value="4" class="rateButton btn btn-secondary">4</button>
                        <button type="button" value="5" class="rateButton btn btn-secondary">5</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username">Your name:</label>
                    <input type="text" class="form-control" id="username" placeholder="Enter your name">
                </div>
                <div class="form-group">
                    <label for="comment">Leave a comment:</label>
                    <textarea class="form-control" id="comment" rows="3"></textarea>
                </div>
                <button id="sendComment" class="btn btn-primary push-right">Post Comment</button>
            </form>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            var id = '{{$id}}';

            getJoke(id);

            $(".rateButton").click(function() {
                var rating = $(this).attr('value');
                var id = '{{$id}}';
                $.ajax({
                    type: "POST",
                    url: "/api/v1.0/rate/",
                    data: { 'id': id, 'rating': rating },
                    success: function(msg) {
                        $('.container').prepend('<div class="alert alert-success alert-dismissible fade show" role="alert">'+ msg.message +'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    },
                    dataType: 'json'
                });
            });

            $("#sendComment").click(function(e) {
                e.preventDefault();
                var comment = $('#comment').val();
                var username = $('#username').val();
                var id = '{{$id}}';
                $.ajax({
                    type: "POST",
                    url: "/api/v1.0/comment/",
                    data: { 'id': id, 'username': username, 'comment': comment },
                    success: function(msg) {
                        $('.container').prepend('<div class="alert alert-success alert-dismissible fade show" role="alert">'+ msg.message +'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        var comment = $('#comment').val('');
                        var username = $('#username').val('');
                        getJoke(id)
                    },
                    dataType: 'json'
                });
            });

            
        });

        function getJoke(id) {
            $.get('/api/v1.0/joke/' + id, function(result) {
                var joke = result.data;
                $("#jokeCard").empty().html('<small>Average rating: '+ (joke.average_rating?joke.average_rating : 'Not rated yet. Be the first to rate this joke.') + ' </small><hr>' + joke.joke);
                $("#comments").empty();
                joke.comments.forEach(function(comment) {
                    $("#comments").append('<li class="list-group-item"><strong>'+ comment.username +': </strong>'+comment.comment+'</li>');
                })
            })
        }
    </script>
  
  </body>
</html>