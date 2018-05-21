<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;


class SearchController extends Controller
{

    private $select = [
        'birthday',
        'description',
        'first_name',
        'last_name',
        'score',
        'username'
    ];

    public function search(Request $request)
    {
        $user = $request->get('user');

        $distance_val = $request->get('distance');

        $birthday_min = Carbon::now()->subYears($request->get('age')[0])->toDateString();
        $birthday_max = Carbon::now()->subYears($request->get('age')[1])->toDateString();

        $tags = collect($request->get('tags'))->pluck('id')->all();

        $score_min = $request->get('score')[0];
        $score_max = $request->get('score')[1];

        $where_birthday = '`birthday` BETWEEN :birthday_max AND :birthday_min';
        $where_score = '`score` BETWEEN :score_min AND :score_max';

        if ($request->get('localization')) {
            $lat_o = $request->get('localization')['geometry']['location']['lat'];
            $lng_o = $request->get('localization')['geometry']['location']['lng'];
        } else {
            $lat_o = $user['lat'];
            $lng_o = $user['lng'];
        }

        $where_exists_tags = '1=1';
        if (count($tags) > 0) {
            $where_exists_tags = 'exists (select * from `tags` inner join `tag_user` on `tags`.`id` = `tag_user`.`tag_id` where `users`.`id` = `tag_user`.`user_id` and `id` in (:tags))';
            $tag_ids = implode(', ', $tags);
        }

        $distance = "3956 * 2 * ASIN(SQRT( POWER(SIN((:orig_lat_sin - abs(users.lat)) * pi()/180 / 2),2) + COS(:orig_lat_cos * pi()/180 ) * COS(abs(users.lat) *  pi()/180) * POWER(SIN((:orig_lng - users.lng) *  pi()/180 / 2), 2) )) as distance";

        $distance_max = $distance_val > 0 ? "having distance < :distance_val" : '';

        $select_sql = implode(',', $this->select);

        $query_string = "SELECT $select_sql , $distance FROM `users` WHERE $where_exists_tags AND $where_score AND $where_birthday $distance_max ORDER BY distance ASC ";

        $query = $this->db()->prepare($query_string);

        if (count($tags) > 0) {
            $query->bindParam(':tags', $tag_ids);
        }

        $query->bindParam(':birthday_min', $birthday_min);
        $query->bindParam(':birthday_max', $birthday_max);

        $query->bindParam(':score_min', $score_min);
        $query->bindParam(':score_max', $score_max);

        $query->bindParam(':orig_lat_cos', $lat_o);
        $query->bindParam(':orig_lat_sin', $lat_o);

        $query->bindParam(':orig_lng', $lng_o);

        if ($distance_val > 0) {
            $query->bindParam(':distance_val', $distance_val);
        }

        $query->execute();

        return response()->json([
            'success' => true,
            'results' => $query->fetchAll(\PDO::FETCH_ASSOC)
        ]);
    }

    public function suggestion(Request $request)
    {

    }
}