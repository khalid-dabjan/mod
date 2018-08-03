<?php

namespace App\Http\Controllers\Api;

use App\Model\Group;
use App\Model\Invitation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;


class GroupController extends Controller
{

    /**
     * POST /api/createGroup
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createGroup(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:254',
            'description' => 'required',
            'isPrivate' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        $data['data'] = Group::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'isPrivate' => $request->get('isPrivate'),
            'user_id' => fauth()->id()
        ]);
        return response()->json($data);
    }


    /**
     * POST /api/inviteMembers
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviteMembers(Request $request)
    {

        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'groupId' => 'required|exists:groups,id',
            'users.*' => 'exists:posts,id',
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $group = Group::find($request->get('groupId'));

        $invites = [];
        $ids = [];
        foreach ($request->get('users', []) as $id) {
            if ($group->members()->where('users.id', $id)->count() > 0) {
                continue;
            }
            if ($id == fauth()->id()) {
                continue;
            }
            $invites[$id] = ['type' => 'invite', 'inviter_id' => fauth()->id()];
            $ids[] = $id;
        }
        $group->invites()->detach($ids);
        $group->invites()->attach($invites);

        $data['data']['status'] = 'updated';
        return response()->json($data);
    }

    /**
     * POST api/groupDetails
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupDetails(Request $request)
    {

        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'groupId' => 'required|exists:groups,id',
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $data['data'] = \Maps\Group\group(Group::find($request->get('groupId')), true);
        return response()->json($data);
    }

    /**
     * POST api/getUserGroups
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserGroups(Request $request)
    {

        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);

        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $data['data'] = \Maps\Group\groups(Group::where('user_id', $request->get('userId'))->take($limit)->offset($offset)->get());

        return response()->json($data);
    }

    /**
     * POST api/joinToGroup
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinToGroup(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'groupId' => 'required|exists:groups,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $group = Group::find($request->get('groupId'));


        if ($group->members()->where('users.id', fauth()->id())->count() > 0 || fauth()->id() == $group->user_id) {
            $data['data']['errors'] = 'your already member in this group';
            return response()->json($data, 400);
        }


        $group->invites()->detach(fauth()->id());
        $group->invites()->attach([fauth()->id() => ['type' => 'invite', 'inviter_id' => fauth()->id()]]);

        $data['data']['result'] = 'joined';

        return response()->json($data);
    }


    /**
     * POST api/getJoinInvites
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJoinInvites(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);


        $invites = Invitation::with(['user', 'group'])
            ->where('user_id', fauth()->id())
            ->where('inviter_id', '<>', fauth()->id())
            ->take($limit)
            ->offset($offset)
            ->get();

        $data['data'] = \Maps\Group\invitations($invites);
        return response()->json($data);
    }

    /**
     * POST api/approveJoinInvite
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveJoinInvite(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'groupId' => 'required|exists:groups,id',
            "userId" => "required|exists:users,id",
            "approved" => "required|boolean"
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $userId = $request->get('userId');
        $groupId = $request->get('groupId');
        $invite = Invitation::where(['user_id' => $userId, 'group_id' => $groupId])->first();
        if (!$invite) {
            $data['errors'][] = 'Their not invitation for this userTheir n in this group';
            return response()->json($data, 400);
        }

        if ($request->get('approved')) {
            $invite->type = "member";
            $invite->save();
        } else {
            $invite->delete();
        }

        $data['data'] = ['status' => true];
        return response()->json($data);
    }

    /**
     * POST api/leaveGroup
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function leaveGroup(Request $request)
    {

        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'groupId' => 'required|exists:groups,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $where = [
            'group_id' => $request->get('groupId'),
            'user_id' => fauth()->id(),
            'type' => 'member'
        ];
        if (DB::table('groups_users')->where($where)->count() == 0) {
            $data['errors'][] = 'Your are not a member';
            return response()->json($data, 400);
        }
        DB::table('groups_users')->where($where)->delete();

        $data['data']['result'] = 'You left group';
        return response()->json($data);
    }

    /**
     * POST api/removeGroupMember
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function removeGroupMember(Request $request)
    {

        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'groupId' => 'required|exists:groups,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $where = [
            'group_id' => $request->get('groupId'),
            'user_id' => $request->get('userId'),
            'type' => 'member'
        ];
        if (DB::table('groups_users')->where($where)->count() == 0) {
            $data['errors'][] = 'This user are not a member';
            return response()->json($data, 400);
        }
        DB::table('groups_users')->where($where)->delete();

        $data['data']['result'] = 'group left';
        return response()->json($data);
    }

    /**
     * POST api/getGroupMembers
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function getGroupMembers(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);
        $validator = Validator::make($request->all(), [
            'groupId' => 'required|exists:groups,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $group = Group::find($request->get('groupId'));
        $data['data'] = \Maps\User\users($group->members()->offset($offset)->take($limit)->get());
        return response()->json($data);
    }


    /**
     * POST api/deleteGroup
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function deleteGroup(Request $request)
    {

        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'groupId' => 'required|exists:groups,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        $group = Group::find($request->get('groupId'));

        if ($group->user_id != fauth()->id()) {
            $data['errors'] = ["it 's not your own group, you can't delete it"];
            return response()->json($data, 400);
        }
        $group->delete();
        (DB::table('groups_users')->where(['group_id' => $group->id])->delete());
        return response()->json($data);
    }

    /**
     * POST api/homeGroups
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function homeGroups(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);

        $data['data']['mine'] = \Maps\Group\groups($ownerGroups = Group::where(['user_id' => fauth()->id()])->take($limit)->offset($offset)->get());
        $data['data']['in'] = \Maps\Group\groups($inGroups = Group::whereHas('members', function ($query) {
            $query->where('users.id', fauth()->id());
        })->take($limit)->offset($offset)->get());

        $ids = $ownerGroups->concat($inGroups)->pluck('id')->toArray();
        $data['data']['other'] = \Maps\Group\groups(Group::whereNotIn('id', $ids)->take($limit)->offset($offset)->get());
        return response()->json($data);
    }


}
