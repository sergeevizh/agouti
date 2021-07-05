<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class SpaceModel extends \MainModel
{
    // Все пространства сайта
    public static function getSpaces($user_id)
    {

        $q = XD::select('*')->from(['space']);
        $result = $q->leftJoin(['space_signed'])->on(['signed_space_id'], '=', ['space_id'])
                ->and(['signed_user_id'], '=', $user_id)
                ->where(['space_is_delete'], '!=', 1)->getSelect();
        
        return $result;
    } 

    // Для форм добавления и изменения
    public static function getSpaceSelect($user_id, $trust_level)
    {
        if ($trust_level == 5) {
            $sql = "SELECT * FROM space";
        } else {
            $sql = "SELECT * FROM space WHERE space_permit_users = 0 or space_user_id = ".$user_id." ORDER BY space_id DESC";
        }

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    } 

   // По id
    public static function getSpaceId($space_id)
    {
        return XD::select('*')->from(['space'])->where(['space_id'], '=', $space_id)->getSelectOne();
    }

   // Пространства участника
    public static function getSpaceUserId($user_id)
    {
        return XD::select('*')->from(['space'])->where(['space_user_id'], '=', $user_id)->getSelect();
    }

    // Количество читающих
    public static function numSpaceSubscribers($space_id)
    {
        $result = XD::select('*')->from(['space_signed'])->where(['signed_space_id'], '=', $space_id)->getSelect();
       
        return count($result);
    } 

    // Списки постов по пространству
    public static function getPosts($space_id, $user_id, $user_tl, $type, $page)
    {
        if ($user_tl != 5) {  
            if ($user_id == 0) { 
                $tl = "AND p.post_tl = 0";
            } else {
                $tl = "AND p.post_tl <= $user_tl";
            }
            $display = "AND p.post_is_delete = 0 $tl";
        } else {
            $display = ''; 
        }
         
        if ($type == 'feed') { 
            $sort = "ORDER BY p.post_top DESC, p.post_date DESC";
        } else {
            $sort = "ORDER BY p.post_answers_num DESC";
        }           
            
        $offset = ($page-1) * 25;   

       // $sql_mode = "SET sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))";
       // DB::run($sql_mode);

        $sql = "SELECT p.*,
                v.votes_post_item_id, v.votes_post_user_id,
                r.relation_post_id, r.relation_topic_id,
                t.topic_id, t.topic_slug, t.topic_title,
                u.id, u.login, u.avatar,
                
                GROUP_CONCAT(t.topic_slug, '@', t.topic_title SEPARATOR '@') AS topic_list
                
                FROM posts AS p 
                
                LEFT JOIN topic_post_relation AS r on r.relation_post_id = p.post_id 
                LEFT JOIN topic AS t on t.topic_id = r.relation_topic_id 
            
                LEFT JOIN users AS u ON u.id = p.post_user_id 
                LEFT JOIN votes_post AS v ON v.votes_post_item_id = p.post_id AND v.votes_post_user_id = $user_id
                WHERE p.post_space_id = $space_id and p.post_draft = 0
                
                $display
                GROUP BY r.relation_post_id $sort LIMIT 25 OFFSET ".$offset." ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество постов
    public static function getCount($space_id, $user_id, $user_tl, $type, $page)
    {
        if ($user_tl != 5) {  
            if ($user_id == 0) { 
                $tl = "AND post_tl = 0";
            } else {
                $tl = "AND post_tl <= $user_tl";
            }
            $display = "AND post_is_delete = 0 $tl";
        } else {
            $display = ''; 
        }
        
        if ($type == 'feed') { 
            $sort = "ORDER BY post_top DESC, post_date DESC";
        } else {
            $sort = "ORDER BY post_answers_num DESC";
        }           
            
       $sql = "SELECT * FROM posts 
                LEFT JOIN users ON id = post_user_id 
                LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = $user_id
                WHERE post_space_id = $space_id and post_draft = 0 $display $sort";

        $quantity = DB::run($sql)->rowCount(); 
       
        return ceil($quantity / 25);
    }
    
    // Информация пространства по slug
    public static function getSpaceSlug($slug)
    {
        $q = XD::select('*')->from(['space'])->leftJoin(['users'])->on(['space_user_id'], '=', ['id']);
        return $q->where(['space_slug'], '=', $slug)->getSelectOne();
    }
    
    // Все пространства на которые подписан пользователь
    public static function getSpaceUserSigned($user_id) 
    {
       $sql = "SELECT f.*, s.* FROM space_signed as f 
            LEFT JOIN space as s ON f.signed_space_id = s.space_id 
            WHERE f.signed_user_id = " . $user_id;

       return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);  
    }
    
    // Подписан пользователь на пространство или нет
    public static function getMyFocus($space_id, $user_id) 
    {
        $result = XD::select('*')->from(['space_signed'])->where(['signed_space_id'], '=', $space_id)->and(['signed_user_id'], '=', $user_id)->getSelect();

        if ($result) {
            return true;
        } 
        return false;
    }
    
    // Подписка / отписка от пространства
    public static function focus($space_id, $user_id)
    {
        $result  = self::getMyFocus($space_id, $user_id);
          
        if ($result === true) {
           XD::deleteFrom(['space_signed'])->where(['signed_space_id'], '=', $space_id)->and(['signed_user_id'], '=', $user_id)->run(); 
        } else {
            XD::insertInto(['space_signed'], '(', ['signed_space_id'], ',', ['signed_user_id'], ')')->values( '(', XD::setList([$space_id, $user_id]), ')' )->run(); 
        }
        
        return true;
    }
    
    // Изменение пространства
    public static function edit($data)
    {
        XD::update(['space'])->set(['space_slug'], '=', $data['space_slug'], ',', 
                ['space_name'], '=', $data['space_name'], ',', 
                ['space_description'], '=', $data['space_description'], ',', 
                ['space_color'], '=', $data['space_color'], ',', 
                ['space_text'], '=', $data['space_text'], ',',
                ['space_short_text'], '=', $data['space_short_text'], ',',
                ['space_permit_users'], '=', $data['space_permit_users'], ',',
                ['space_feed'], '=', $data['space_feed'], ',',            
                ['space_tl'], '=', $data['space_tl'])->where(['space_id'], '=', $data['space_id'])->run();
        
        return true;
    }
    
    // Изменение фото / обложки
    public static function setImg($space_id, $img)
    {
        return XD::update(['space'])->set(['space_img'], '=', $img)->where(['space_id'], '=', $space_id)->run();
    }

    public static function setCover($space_id, $cover)
    {
        return XD::update(['space'])->set(['space_cover_art'], '=', $cover)->where(['space_id'], '=', $space_id)->run();
    }
 
    // Удалим обложку для пространства
    public static function CoverRemove($space_id)
    {
        return XD::update(['space'])->set(['space_cover_art'], '=', 'space_cover_no.jpeg')
                ->where(['space_id'], '=', $space_id)->run();
    }

    // Удалено пространство или нет
    public static function isTheSpaceDeleted($space_id) 
    {
        $result = XD::select('*')->from(['space'])->where(['space_id'], '=', $space_id)->getSelectOne();
        
        return $result['space_is_delete'];
    }
    
    // Удаление, восстановление пространства
    public static  function SpaceDelete($space_id)
    {
        if (self::isTheSpaceDeleted($space_id) == 1) {
            XD::update(['space'])->set(['space_is_delete'], '=', 0)->where(['space_id'], '=', $space_id)->run();
        } else {
            XD::update(['space'])->set(['space_is_delete'], '=', 1)->where(['space_id'], '=', $space_id)->run();
        }
        return true;
    } 
    
    // Добавляем пространства
    public static function AddSpace($data) 
    {
        XD::insertInto(['space'], '(', 
            ['space_name'], ',', 
            ['space_slug'], ',', 
            ['space_description'], ',', 
            ['space_color'], ',',  
            ['space_img'], ',',
            ['space_text'], ',',  
            ['space_short_text'], ',', 
            ['space_date'], ',',
            ['space_category_id'], ',',
            ['space_user_id'], ',', 
            ['space_type'], ',', 
            ['space_permit_users'], ',', 
            ['space_feed'], ',', 
            ['space_tl'], ',',
            ['space_is_delete'], ')')->values( '(', 
        
        XD::setList([
            $data['space_name'], 
            $data['space_slug'], 
            $data['space_description'], 
            $data['space_color'],
            $data['space_img'],            
            $data['space_text'], 
            $data['space_short_text'], 
            $data['space_date'], 
            $data['space_category_id'], 
            $data['space_user_id'], 
            $data['space_type'], 
            $data['space_permit_users'],
            $data['space_feed'],
            $data['space_tl'],
            $data['space_is_delete']]), ')' )->run();
        
        // id добавленного пространства
        $space_id = XD::select()->last_insert_id('()')->getSelectValue();

        // Подписываем на созданное пространство   
        XD::insertInto(['space_signed'], '(', ['signed_space_id'], ',', ['signed_user_id'], ')')->values( '(', XD::setList([$space_id, $data['space_user_id']]), ')' )->run(); 

        return true; 
    }
}
