<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Joke;
use App\Rating;
use App\Comment;
use Validator;


class JokeController extends Controller
{
	public function load() {

	  try {
		$guzzle = new \GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);
		$loadNextPage = true;
		$limit = 30; // The external API has limit max of 30. We can't load all jokes in one shot.
		$page = 1;

		while($loadNextPage) {
		  $res = $guzzle->get('https://icanhazdadjoke.com/search?term=hey&limit=' . $limit . '&page=' . $page);
		  $data = json_decode($res->getBody()->getContents());

		  if ($data->current_page < $data->next_page) {
			$page = $data->next_page;
		  } else {
			$loadNextPage = false;
		  }
		  

		  $jokesData = $data->results;
		  // dd($jokesData);
		  $jokeObjects = [];

		  foreach ($jokesData as $jokeData ) {
			$newJoke['id'] = $jokeData->id;
			$newJoke['joke'] = addslashes($jokeData->joke);
			Joke::firstOrCreate($newJoke);
		  }
		}
	  
		return ['result' => 'Jokes imported to database!' ];
	  } catch (\Exception $e ) {
		return ['error' => 'An error occurred while importing the jokes.' ];
	  }
	}

	public function search($term) {
	  $jokes = Joke::where('id', 'like', '%'.$term.'%')
				  ->orWhere('joke', 'like', '%'.$term.'%')
				  ->with('comments')
				  ->get();

	  if ($jokes) {
		return (["result" => $jokes]);
	  }
	  return (["result" => null ]);
	}

	public function view($id) {
	  $joke = Joke::with('comments')->find($id);

	  if (!$joke) {
		return ["data" => null, "statusCode" => 404, "message" => "Joke not found!"];
	  }

	  return ["data" => $joke, "statusCode" => 200, "message" => "Success"];
	}

	public function rate(Request $request) {

	  $validator = Validator::make($request->all(), [
		'id' => 'required',
		'rating' => 'required|integer|between:1,5'
	  ]);

	  if ($validator->fails())
	  {
		  return $this->error(["message" => $validator->getMessageBag()->toArray(), "code" => 400]);
	  }

	  $joke = Joke::find($request->input('id'));

	  if (!$joke) {
		return ["data" => null, "statusCode" => 404, "message" => "Joke not found!"];
	  }

	  $rating = new Rating(['rating' => $request->input('rating')]);

	  if (!$joke->ratings()->save($rating)) {
		return ["data" => null, "statusCode" => 400, "message" => "Error while rating a joke!"];
	  }

	  return ["data" => null, "statusCode" => 200, "message" => "Joke succefully rated!"];
	}

	public function comment(Request $request) {
		$validator = Validator::make($request->all(), [
			'id' => 'required',
			'username' => 'required',
			'comment' => 'required'
		]);

		if ($validator->fails())
		{
			return $this->error(["message" => $validator->getMessageBag()->toArray(), "code" => 400]);
		}

		$joke = Joke::find($request->input('id'));

		if (!$joke) {
		return ["data" => null, "statusCode" => 404, "message" => "Joke not found!"];
		}

		$comment = new Comment(['username' => $request->input('username'), 'comment' => $request->input('comment')]);

		if (!$joke->comments()->save($comment)) {
			return ["data" => null, "statusCode" => 400, "message" => "Error while posting your comment!"];
		}

		return ["data" => null, "statusCode" => 200, "message" => "Comment posted!"];
	}
}