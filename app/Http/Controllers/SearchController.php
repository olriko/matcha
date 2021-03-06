<?php

namespace App\Http\Controllers;


use Carbon\Carbon;

use Illuminate\Http\Request;


class SearchController extends Controller
{

    private $select = [
        'users.id',
        'birthday',
        'description',
        'first_name',
        'last_name',
        'score',
        'username',
        'i.name as image'
    ];

    private function blocked($id)
    {
        $query = $this->db()->prepare("SELECT user_id FROM blocks WHERE blocked_by = :id");

        $query->bindParam(':id', $id);
        $query->execute();
        return collect($query->fetchAll(2))->pluck('user_id')->all();
    }

    private function suggestion($user)
    {
        $HETERO = 'heterosexual';
        $HOMO = 'homosexual';
        $BI = 'bisexual';

        $gender = $user['gender'];
        $sexual_orientation = $user['sexual_orientation'];

        if ($gender !== 'other') {
            $opposite = $gender === 'female' ? 'male' : 'female';

            if ($sexual_orientation === $HETERO) {
                return "AND gender = '$opposite' AND sexual_orientation IN ( '$HETERO', '$BI' )";
            } elseif ($sexual_orientation === $HOMO) {
                return "AND gender = '$gender' AND sexual_orientation IN ( '$HOMO', '$BI' )";
            }
            return "AND (gender = '$gender' AND sexual_orientation <> '$HETERO') OR (gender = '$opposite' AND  sexual_orientation <> '$HOMO') OR (gender = 'other')";
        }


        return '';
    }


    public function search(Request $request)
    {
        $user = $request->get('user');

        $suggestion = '';
        $limit = '';
        $where_exists_tags = '';
        $blocked = '1=1';


        $distance_val = $request->get('distance') ?? 0;

        $birthday_min = Carbon::now()->subYears($request->get('age')[0] ?? '18')->toDateString();
        $birthday_max = Carbon::now()->subYears($request->get('age')[1] ?? '100')->toDateString();

        $tags = collect($request->get('tags'))->pluck('id')->all();
        $score_min = $request->get('score')[0] ?? $user['score'] - 800;
        $score_max = $request->get('score')[1] ?? $user['score'] + 800;

        $where_birthday = 'AND `birthday` BETWEEN :birthday_max AND :birthday_min';
        $where_score = 'AND `score` BETWEEN :score_min AND :score_max';

        $image = 'LEFT JOIN images as i ON i.user_id = users.id AND i.main = 1';

        if ($request->get('localization')) {
            $lat_o = $request->get('localization')['geometry']['location']['lat'];
            $lng_o = $request->get('localization')['geometry']['location']['lng'];
        } else {
            $lat_o = $user['lat'];
            $lng_o = $user['lng'];
        }

        $distance = "1.6 * 3956 * 2 * ASIN(SQRT( POWER(SIN((:orig_lat_sin - abs(users.lat)) * pi()/180 / 2),2) + COS(:orig_lat_cos * pi()/180 ) * COS(abs(users.lat) *  pi()/180) * POWER(SIN((:orig_lng - users.lng) *  pi()/180 / 2), 2) )) as distance";

        $blockedList = implode(', ', $this->blocked($user['id']));
        if ($blockedList) {
            $blocked = "users.id NOT IN ( $blockedList )";
        }

        $distance_max = $distance_val > 0 ? "having distance < :distance_val" : '';

        $select_sql = implode(',', $this->select);

        $userid = $user['id'];


        if ($request->get('suggestion') && $request->get('suggestion') == true) {
            $suggestion = $this->suggestion($user);

            if (!count($tags)) {

                $tags_q = $this->db()->prepare("SELECT tags.id as id FROM `tags` INNER JOIN tag_user ON tags.id = tag_user.tag_id WHERE tag_user.user_id = :id");
                $tags_q->bindParam(':id', $userid);
                $tags_q->execute();

                $tags = $tags_q->fetchAll(2);
                if (count($tags)) {
                    $tags = $tags[0];
                }
            }

            $limit = "LIMIT 10";
        }

        if (count($tags) > 0) {
            $where_exists_tags = 'AND exists (select * from `tags` inner join `tag_user` on `tags`.`id` = `tag_user`.`tag_id` where `users`.`id` = `tag_user`.`user_id` and `id` in (:tags))';
            $tag_ids = implode(', ', $tags);
        }

        $query_string = "SELECT $select_sql, $distance FROM `users` $image WHERE $blocked $suggestion $where_exists_tags $where_score $where_birthday $distance_max AND users.id != $userid ORDER BY distance ASC $limit";

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
            'results' => $query->fetchAll(\PDO::FETCH_ASSOC),
        ]);
    }
}