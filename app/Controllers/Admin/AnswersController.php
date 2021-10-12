<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\AnswerModel;
use Content, Base;

class AnswersController extends MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 100;
        $pagesCount = AnswerModel::getAnswersAllCount($sheet);
        $answers    = AnswerModel::getAnswersAll($page, $limit, $uid, $sheet);

        $result = array();
        foreach ($answers  as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }

        $meta = meta($m = [], $sheet == 'ban' ? lang('deleted answers') : lang('answers-n'));
        $data = [
            'sheet'         => $sheet == 'all' ? 'answers-n' : 'answers-ban',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'answers'       => $result,
        ];

        return view('/admin/answer/answers', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
