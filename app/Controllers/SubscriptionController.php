<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{SubscriptionModel, PostModel, FacetModel};

class SubscriptionController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid = UserData::getUid();
    }

    public function index()
    {
        $content_id = Request::getPostInt('content_id');
        $type       = Request::get('type');

        $allowed = ['post', 'topic'];
        if (!in_array($type, $allowed)) return false;

        if ($content_id <= 0) return false;

        if ($type == 'post') {
            $content = PostModel::getPost($content_id, 'id', $this->uid);
        } else {
            $content =  FacetModel::getFacet($content_id, 'id');
            // Запретим владельцу отписываться от созданного фасета
            // Prevent the owner from unsubscribing from the created facet 
            if ($content['facet_user_id'] == $this->uid['user_id']) return false;
        }

        SubscriptionModel::focus($content_id, $this->uid['user_id'], $type);

        return true;
    }
}
