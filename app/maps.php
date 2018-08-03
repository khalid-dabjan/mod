<?php

/**
 * Maps for User objects
 */

namespace Maps\User {

    use App\Model\Set;
    use App\User;
    use Illuminate\Support\Facades\DB;

    /**
     * Login Object Maps
     * @param $user
     * @return \stdClass
     */
    function login($user)
    {
        $std = new \stdClass();
        $std->userId = $user->id;
        $std->email = $user->email;
        $std->name = $user->first_name . ' ' . $user->last_name;
        $std->avater = $user->photo ? uploads_url($user->photo->path) : null;
        $std->user_type = $user->role_id == 2 ? "RETAILER" : "USER";
        return $std;
    }

    /**
     * @param $users
     * @return array
     */
    function users($users)
    {
        if ((!is_array($users)) && !($users instanceof \Illuminate\Support\Collection)) {
            $users = [$users];
        }

        $newArray = [];
        foreach ($users as $user) {
            $newUser = new \stdClass();
            $newUser->id = $user->id;
            $newUser->fname = $user->first_name;
            $newUser->lname = $user->last_name;
            $newUser->username = $user->username;
            $newUser->user_type = $user->role_id == 2 ? "RETAILER" : "USER";
            $newUser->about = $user->about;
            $newUser->currency = $user->currency;
            $newUser->email = $user->email;
            $newUser->following_counter = $user->following()->count();
            $newUser->follower_counter = $user->follower()->count();
            $newUser->is_followed = $user->follower()->where('following_id', fauth()->id())->count() ? true : false;
            $newUser->is_blocked = DB::table('users_blocked')->where(['user_id' => fauth()->id(), 'blocked_id' => $user->id])->count() ? true : false;
            $newUser->sets_count = Set::where('user_id', $user->id)->count();
            $newUser->profession = $user->profession;
            $newUser->photo = null;
            if ($user->photo) {
                $newUser->photo = new \stdClass();
                $newUser->photo->table_id = $user->photo->id;
                $newUser->photo->photo_name = thumbnail($user->photo->path, 'small');
            }
            $newArray[] = $newUser;
        }
        return $newArray;
    }

    /**
     * @param $user
     * @return \stdClass
     */
    function user($user)
    {
        $newUser = new \stdClass();
        $newUser->id = $user->id;
        $newUser->fname = $user->first_name;
        $newUser->lname = $user->last_name;
        $newUser->username = $user->username;
        $newUser->about = $user->about;
        $newUser->user_type = $user->role_id == 2 ? "RETAILER" : "USER";
        $newUser->following_counter = $user->following()->count();
        $newUser->follower_counter = $user->follower()->count();
        $newUser->is_followed = $user->follower()->where('following_id', fauth()->id())->count() ? true : false;
        $newUser->is_blocked = DB::table('users_blocked')->where(['user_id' => fauth()->id(), 'blocked_id' => $user->id])->count() ? true : false;
        $newUser->currency = $user->currency;
        $newUser->email = $user->email;
        $newUser->sets_count = Set::where('user_id', $user->id)->count();
        $newUser->profession = $user->profession;
        $newUser->photo = null;
        if ($user->photo) {
            $newUser->photo = new \stdClass();
            $newUser->photo->table_id = $user->photo->id;
            $newUser->photo->photo_name = thumbnail($user->photo->path, 'small');
        }
        return $newUser;
    }

}

namespace Maps\Category {

    /**
     * @param $categories
     * @return \stdClass
     */
    function categories($categories)
    {
        $newCategories = new \stdClass();

        foreach ($categories as $category) {
            $object = $newCategories->{trim($category->name)} = new \stdClass();

            $object->id = $category->id;
            $object->title = $category->name;
            $object->image = $category->image ? $category->image->path : null;
            $object->subcategories = [];
            foreach ($category->categories as $subCategory) {
                $subObject = new \stdClass();
                $subObject->id = $subCategory->id;
                $subObject->title = $subCategory->name;
                $subObject->image = $subCategory->image ? $subCategory->image->path : null;
                $object->subcategories[] = $subObject;
            }
        }
        return $newCategories;
    }
}


namespace Maps\Item {

    use Dot\Colors\Models\Color;

    /**
     * @param $items
     * @return array
     */
    function items($items)
    {

        $newItems = [];
        foreach ($items as $item) {
            $newItem = new \stdClass();
            $newItem->id = $item->id;
            $newItem->title_en = $item->title;
            $newItem->price = $item->price;
            $newItem->currency = $item->currency;
            $newItem->text_en = $item->content;
            $newItem->url_en = $item->url;
            $newItem->user_id = $item->user_id;
            $newItem->user_url = $item->user->website ? $item->user->website : null;
            $newItem->brand = $item->brand ? $item->brand->title : "";
            $newItem->likes = $item->likes()->count();
            $newItem->is_liked = $item->likes()->where('id', fauth()->id())->count() ? true : false;
            $newItem->user_currency = $item->user->currency ? $item->user->currency : "";
            $newItem->color_id = $item->color_id;
            $newItem->color = ($color = Color::find($item->color_id)) ? $color->value : null;
            $newItem->photos = [];
            if ($item->image) {
                $photo = new \stdClass();
                $photo->table_id = $item->image->id;
                $photo->photo_name = uploads_url($item->image->path);
                $newItem->photos[] = $photo;
            }
            $newItems[] = $newItem;
        }
        return $newItems;
    }

    /**
     * @param $item
     * @return mixed
     */
    function itemDetails($item, $similarItems)
    {
        $newItem = new \stdClass();
        $newItem->id = $item->id;
        $newItem->title_en = $item->title;
        $newItem->price = $item->price;
        $newItem->currency = $item->currency;
        $newItem->text_en = $item->content;
        $newItem->url_en = $item->url;
        $newItem->user_id = $item->user_id;
        $newItem->brand = $item->brand ? $item->brand->title : "";
        $newItem->likes = $item->likes()->count();
        $newItem->user_url = $item->user->website ? $item->user->website : null;
        $newItem->is_liked = $item->likes()->where('id', fauth()->id())->count() ? true : false;
        $newItem->user_currency = $item->user->currency ? $item->user->currency : "";
        $newItem->color_id = $item->color_id;
        $newItem->color = ($color = Color::find($item->color_id)) ? $color->value : null;
        $newItem->photos = [];
        $newItem->similar = \Maps\Item\items($similarItems);
        if ($item->image) {
            $photo = new \stdClass();
            $photo->table_id = $item->image->id;
            $photo->photo_name = uploads_url($item->image->path);
            $newItem->photos[] = $photo;
        }
        $newItem->categories = $item->categories()->orderBy('parent', 'ASC')->get();
        return $newItem;
    }
}

namespace Maps\Set {

    /**
     * @param $set
     * @param bool $withItem
     * @return \stdClass
     */
    function setDetails($set, $withItem = false)
    {
        $newSet = new \stdClass();
        $newSet->id = $set->id;
        $newSet->user_id = $set->user_id;
        $newSet->title_en = $set->title;
        $newSet->text_en = $set->excerpt;
        $newSet->likes = $newSet->likes_counter = $set->likes()->count();
        $newSet->created = $set->created_at->diffForHumans();
        $newSet->contest_id = null;
        $newSet->is_liked = $set->likes()->where('id', fauth()->id())->count() ? true : false;
        $newSet->comments_counter = $set->comments()->count();
        $newSet->group = [];
        $newSet->background = $set->background;

        $newSet->photo = null;
        if ($set->image) {
            $photo = new \stdClass();
            $photo->table_id = $set->image->id;
            $photo->photo_name = uploads_url($set->image->path);
            $newSet->photo = $photo;
        }
        $newSet->user = \Maps\User\user($set->user);
        if ($withItem) {
            $newSet->items = \Maps\Item\items($set->items, $set->id);
            $newSet->contest = null;
        }
        return $newSet;
    }

    /**
     * @param $comments
     * @return array
     */
    function comments($comments)
    {
        $newComments = [];
        foreach ($comments as $comment) {
            $newComment = new \stdClass();
            $newComment->id = $comment->id;
            $newComment->text_en = $comment->comment;
            $newComment->from_id = $comment->user_id;
            $newComment->procitano = 0;
            $newComment->created = $comment->created_at->diffForHumans();
            $newComment->user = \Maps\User\user($comment->user);

            $newComments[] = $newComment;
        }
        return $newComments;
    }

    /**
     * @param $items
     * @param $set_id
     * @return array
     */
    function items($items, $set_id)
    {
        $newItems = [];
        foreach ($items as $item) {
            $newItem = new \stdClass();
            $newItem->id = $item->id;
            $newItem->title_en = $item->title;
            $newItem->price = $item->price;
            $newItem->currency = $item->currency;
            $newItem->is_liked = $item->likes()->where('id', fauth()->id())->count() ? true : false;
            $newItem->user_currency = $item->user->currency ? $item->user->currency : "";
            $newItem->photos = [];
            $newItem->user_id = $item->user_id;
            $newItem->user_url = $item->user->website ? $item->user->website : null;
            $newItem->brand = $item->brand ? $item->brand->title : "";
            if ($item->image) {
                $photo = new \stdClass();
                $photo->table_id = $item->image->id;
                $photo->photo_name = uploads_url($item->image->path);
                $newItem->photos[] = $photo;
            }
            $newItem->pivot = new \stdClass();
            $newItem->pivot->set_id = $set_id;
            $newItem->pivot->items_id = $item->id;
            $newItems[] = $newItem;
        }
        return $newItems;
    }

    /**
     * @param $sets
     * @return array
     */
    function sets($sets)
    {
        $newSets = [];
        foreach ($sets as $set) {
            $newSets[] = setDetails($set);
        }
        return $newSets;
    }
}


namespace Maps\Collection {


    /**
     * @param $items
     * @return array
     */
    function items($items)
    {
        $newItems = [];
        foreach ($items as $item) {
            $newItem = new \stdClass();
            $newItem->id = $item->id;
            $newItem->title_en = $item->title;
            $newItem->price = $item->price;
            $newItem->currency = $item->currency;
            $newItem->text_en = $item->content;
            $newItem->user_url = $item->user->website ? $item->user->website : null;
            $newItem->url_en = $item->url;
            $newItem->brand = $item->brand ? $item->brand->title : "";
            $newItem->is_liked = $item->likes()->where('id', fauth()->id())->count() ? true : false;
            $newItem->user_currency = $item->user->currency ? $item->user->currency : "";
            $newItem->photo = [];
            $newItem->user_id = $item->user_id;

            if ($item->image) {
                $photo = new \stdClass();
                $photo->table_id = $item->image->id;
                $photo->photo_name = uploads_url($item->image->path);
                $newItem->photo[] = $photo;
            }
            $newItems[] = $newItem;
        }
        return $newItems;
    }

    /**
     * @param $collection
     * @return \stdClass
     */
    function collection($collection)
    {
        $newCollection = new \stdClass();
        $newCollection->id = $collection->id;
        $newCollection->user_id = $collection->user_id;
        $newCollection->title_en = $collection->title;
        $newCollection->text_en = $collection->excerpt;
        $newCollection->image = uploads_url($collection->image->path);
        $newCollection->is_liked = $collection->likes()->where('id', fauth()->id())->count() ? true : false;
        $newCollection->likes = $collection->likes()->where('id', fauth()->id())->count();
        $newCollection->created = $collection->created_at->diffForHumans();
        $newCollection->items = \Maps\Item\items($collection->items);
        $newCollection->user = \Maps\User\user($collection->user);
        $newCollection->sets = \Maps\Set\sets($collection->sets);
        return $newCollection;
    }

    /**
     * @param $collections
     * @return array
     */
    function collections($collections)
    {

        $newArray = [];
        foreach ($collections as $collection) {
            $newArray[] = \Maps\Collection\collection($collection);
        }
        return $newArray;
    }

}

namespace Maps\Contest {


    /**
     * @param $contest
     * @return \stdClass
     */
    function contest($contest)
    {
        $newContest = new \stdClass();
        $newContest->id = $contest->id;
        $newContest->title_en = $contest->title;
        $newContest->created = $contest->published_at->diffForHumans();
        $newContest->expires = $contest->expired_at->toDateTimeString();
        $newContest->text_en = $contest->title;
        $newContest->text2_en = $contest->content;
        $newContest->time_diff = $contest->expired_at->diffForHumans();
        $newContest->is_liked = $contest->likes()->where('id', fauth()->id())->count() ? true : false;
        $newContest->likes = $contest->likes()->count();
        $newContest->is_photo_submitted = $contest->items()->where('user_id', fauth()->id())->count() ? true : false;
        $newContest->photo = null;
        if ($contest->image) {
            $photo = new \stdClass();
            $photo->table_id = $contest->image->id;
            $photo->photo_name = uploads_url($contest->image->path);
            $newContest->photo = $photo;
        }
        $newContest->winners = \Maps\Contest\winners($contest);
        return $newContest;
    }

    /**
     * @param $contests
     * @return mixed
     */
    function contests($contests)
    {
        $newCollections = [];

        foreach ($contests as $contest) {
            $newCollections[] = \Maps\Contest\contest($contest);
        }
        return $newCollections;
    }

    /**
     * @param $winner
     * @param $contest
     * @return \stdClass
     */
    function winner($winner, $contest)
    {
        $newItem = new \stdClass();
        $newItem->id = $winner->id;
        $newItem->user_id = $winner->user_id;
        $newItem->contest_id = $winner->contest_id;
        $newItem->contest_title = $contest->title;
        $newItem->win_place = 1;
        $newItem->join_on = $winner->created_at->toDayDateTimeString();
        $newItem->photo_id = $winner->image_id;
        $newItem->likes = $winner->likes()->count();
        $newItem->date_created = $winner->created_at->toDateTimeString();
        $newItem->is_liked = $winner->likes()->where('id', fauth()->id())->count() ? true : false;

        $newItem->photo = null;
        if ($winner->image) {
            $photo = new \stdClass();
            $photo->table_id = $winner->image->id;
            $photo->photo_name = uploads_url($winner->image->path);
            $newItem->photo = $photo;
        }
        $newItem->user = \Maps\User\user($winner->user);
        return $newItem;
    }

    /**
     * Winners
     * @param $contest
     * @return array
     */
    function winners($contest)
    {
        $newWinners = [];
        foreach ($contest->winners as $winner) {
            $newWinners[] = \Maps\Contest\winner($winner, $contest);
        }
        return $newWinners;
    }

    /**
     * Contest items
     * @param $contestItems
     * @return array
     */
    function items($contestItems)
    {
        $newCollections = [];
        foreach ($contestItems as $item) {
            $newItem = new \stdClass();
            $newItem->id = $item->id;
            $newItem->contest_id = $item->contest_id;
            $newItem->photo_id = $item->image_id;
            $newItem->likes = $item->likes()->count();
            $newItem->date_created = $item->created_at->toDateTimeString();
            $newItem->date_likes = $item->created_at->toDateTimeString();
            $newItem->brand = $item->brand ? $item->brand->title : "";
            $newItem->user_url = isset($item->user) && $item->user->website ? $item->user->website : null;
            $newItem->user_currency = isset($item->user) && $item->user->currency ? $item->user->currency : "";
            $newItem->is_liked = $item->likes()->where('id', fauth()->id())->count() ? true : false;
            $newItem->photo = null;
            $newItem->user_id = $item->user_id;
            if ($item->image) {
                $photo = new \stdClass();
                $photo->table_id = $item->image->id;
                $photo->photo_name = uploads_url($item->image->path);
                $newItem->photo = $photo;
            }
            $newItem->user = \Maps\User\user($item->user);
            $newCollections[] = $newItem;
        }
        return $newCollections;
    }
}

namespace Maps\Group {

    /**
     * @param $group
     * @param bool $details
     * @return \stdClass
     */
    function group($group, $details = false)
    {
        $newGroup = new \stdClass();

        $newGroup->id = $group->id;
        $newGroup->name = $group->name;
        $newGroup->description = $group->description;
        $newGroup->is_private = $group->is_private;
        $newGroup->admin_id = $group->user_id;
        $newGroup->members_counter = $group->members()->count() + 1;

        $newGroup->is_invited = $group->invites()->where('user_id', fauth()->id())->count() ? true : false;
        $newGroup->is_member = $group->members()->where('user_id', fauth()->id())->count() ? true : false;

        $newGroup->image = "https://modasti.uniative.com/uploads/2018/08/1635001808440559630.png";

        $newGroup->wins_counter = 0;
        $newGroup->request_counter = $group->invites()->count();
        $newGroup->photo_counter = 0;
        $newGroup->contests_counter = 0;

        if ($details) {
            $newGroup->owner = \Maps\User\users($group->user);
            $newGroup->photo = [];
        }

        return $newGroup;
    }

    /**
     * @param $groups
     * @return array
     */
    function groups($groups)
    {
        $newGroups = [];
        foreach ($groups as $group) {
            $newGroups[] = \Maps\Group\group($group, false);
        }
        return $newGroups;
    }

    /**
     * @param $invitations
     * @return array
     */
    function invitations($invitations)
    {
        $newInvites = [];
        foreach ($invitations as $invite) {
            $newInvite = new \stdClass();

            $newInvite->invited_id = $invite->id;
            $newInvite->group_id = $invite->group_id;

            $newInvite->user = $invite->user?\Maps\User\users($invite->user):new \stdClass();
            $newInvite->group = \Maps\Group\group($invite->group);

            $newInvites[] = $newInvite;
        }
        return $newInvites;
    }

}