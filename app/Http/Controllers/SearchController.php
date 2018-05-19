<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;


class SearchController extends Controller
{

    public function search(Request $request)
    {

        $birthday_min = Carbon::now()->subYears($request->get('age')[0])->toDateString();
        $birthday_max = Carbon::now()->subYears($request->get('age')[1])->toDateString();

        $tags = collect($request->get('tags'))->pluck('id')->all();


        $score_min = $request->get('score')[0];
        $score_max = $request->get('score')[1];
        
        $where_birthday = '`birthday` BETWEEN :birthday_max AND :birthday_min';
        $where_score = '`score` BETWEEN :score_min AND :score_max';

        $where_exists_tags = '1=1';
        if (count($tags) > 0) {
            $where_exists_tags = 'exists (select * from `tags` inner join `tag_user` on `tags`.`id` = `tag_user`.`tag_id` where `users`.`id` = `tag_user`.`user_id` and `id` in (:tags))';
            $tag_ids = implode(', ', $tags);
        }

        $query_string = "SELECT * FROM `users` WHERE $where_exists_tags AND $where_score AND $where_birthday";

        $query = $this->db()->prepare($query_string);

        if (count($tags) > 0) {
            $query->bindParam(':tags', $tag_ids);
        }

        $query->bindParam(':birthday_min', $birthday_min);
        $query->bindParam(':birthday_max', $birthday_max);

        $query->bindParam(':score_min', $score_min);
        $query->bindParam(':score_max', $score_max);

        $query->execute();


        return response()->json([
            'success' => true,
            'results' => $query->fetchAll(\PDO::FETCH_ASSOC)
        ]);
    }
}