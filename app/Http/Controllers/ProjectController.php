<?php
/**
crowdCuratio - Curating together virtually
Copyright (C)2022 - berlinHistory e.V.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program in the file LICENSE.

If not, see <https://www.gnu.org/licenses/>.
 */
namespace App\Http\Controllers;

use App\Models\Audiovisual;
use App\Models\Gallery;
use App\Models\Image;
use App\Models\Invitation;
use App\Models\ModelHasRole;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\Source;
use App\Models\Text;
use App\Models\User;
use App\Models\UserHasPermission;
use App\Services\CommentRetrieve;
use App\Services\LogService;
use App\Services\UserService;
use App\Traits\UploadTrait;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Spatie\Activitylog\Models\Activity;
use Mpdf\Pdf;
use Mpdf\Mpdf;


class ProjectController extends Controller
{
    use UploadTrait;

    /**
     * Instantiate a new ProjectController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:add', ['only' => ['create', 'store']]);
        $this->middleware('permission:view', ['only' => ['index']]);
        //$this->middleware('permission:preview', ['only' => ['index']]);
        //$this->middleware('permission:edit', ['only' => ['edit', 'update']]);
        //$this->middleware('permission:delete', ['only' => ['destroy']]);
        $this->middleware('permission:comment', ['only' => ['commentProject', 'getProjectComment']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //get all available projects
        $data = $this->getAllProjects();

        return view('projects.index', compact('data'));
    }

    /**
     * Return list of all active projects
     *
     * @return Collection
     */
    public function getAllProjects()
    {

        if(Auth::user()->isAdmin()) {

            return Project::query()
                ->join('users', 'users.id', '=', 'projects.user_id')
                ->select('projects.*', 'users.name as user_name')
                ->whereNull('projects.deleted_at')
                ->whereNull('users.deleted_at')
                ->get();
        } else {

            return Project::query()
                ->join('users', 'users.id', '=', 'projects.user_id')
                ->leftJoin('invitations', 'invitations.project_id', '=', 'projects.id')
                ->select('projects.*', 'users.name as user_name')
                ->distinct()
                ->Where(function($query) {
                    $query->where('invitations.guest_id',Auth::user()->id)
                            ->orWhere('projects.user_id',Auth::user()->id);
                })
                ->whereNull('projects.deleted_at')
                ->whereNull('users.deleted_at')
                ->get();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

            $request->validate(
                [
                    'name' => 'required',
                    'imprint' => 'required',
                ]
            );

            $new = Project::create($this->mapData($request));

        return redirect()->route('chapters.index', ['id' => $new->id])
            ->with('success', 'Project added successfully');
    }

    /**
     * Map data
     *
     * @param $request
     * @return array
     */
    protected function mapData($request){

        $data = [];
        $data['user_id'] = Auth::user()->id;
        $data['status'] = config('project.status.default');

        if(isset($request['name'])) $data['name'] = $request['name'];
        if(isset($request['imprint'])) $data['imprint'] = $request['imprint'];
        if(isset($request['terms'])) $data['terms'] = $request['terms'];
        if(isset($request['description'])) $data['description'] = $request['description'];

        $logo = $this->setImage($request);
        if($logo != '') $data['logo'] = $logo;

        return $data;
    }

    /**
     * Save image
     *
     * @param $request
     * @return $this
     */
    protected function setImage($request)
    {
        $name = '';
        // Check if an image should be uploaded
        if ($request->has('project_image')) {
            // Get image file
            $image = $request->file('project_image');
            // Make a image name based on user name and current timestamp
            $name = date('Ymd').'_'.time().'.'.$request->file('project_image')->extension();
            // Define folder path
            $folder = '/uploads/images/';
            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);
        }

        return $name;
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @return Response
     */
    public function edit(Request $request, Project $project)
    {
        $textLog = [];
        $comments = [];
        $isComment = false;

        if(isset($request['comment'])){
            $isComment = true;
            $comment = new CommentRetrieve();

            $comments = $comment->getComments($request['model'], $request['comment']);

        }

        if (isset($request['log']) && isset($request['model'])) {

            $textLog = $this->history($request['model'], $request['log']);
        }

        $permissions = Permission::all();
        $listRole = Role::where('id', 'not like', '1')->pluck('name', 'id');
        $users = User::whereNull('deleted_at')->get();
        $userService = new UserService();
        $listPermissions = $userService->getAllUsers($project->id);
        $allPermissions = Permission::pluck('name', 'id');
        $currentUserPermissions = $this->getCurrentUsersPermissions(Auth::user()->id);

        $data = Project::findOrFail($project->id);
        $listGrantedUsers = $this->getUsersForThisProject($project->id);

        $links = session()->has('links') ? session('links') : [];
        $currentLink = request()->path();
        array_unshift($links, $currentLink);
        session(['links' => $links]);

        return view(
            'projects.edit',
            compact(
                'project',
                'data',
                'permissions',
                'users',
                'listPermissions',
                'listGrantedUsers',
                'textLog',
                'allPermissions',
                'currentUserPermissions',
                'listRole',
                'comments',
                'isComment'
            )
        );
    }

    /**
     * Get users that are allowed to work in the current project
     *
     * @param $id
     * @return bool
     */
    protected function getUsersForThisProject($id){

        $users = User::whereNull('deleted_at')->get();

        $userList = [];
        foreach ($users as $key => $user) {
            $userList[$user->id] = ['name' => $user->name, 'lastName' => $user->last_name];
        }

        $listUsersPermissions = UserHasPermission::where('project_id', $id)->get();

        $listGrantedUsers = [];

        foreach ($listUsersPermissions as $key => $value) {
            if (!array_key_exists($value->user_id, $listGrantedUsers) && array_key_exists($value->user_id, $userList)) {
                $listGrantedUsers[$value->user_id]['name'] = $userList[$value->user_id]['name'] . ' ' . $userList[$value->user_id]['lastName'];
                $userPermission = $this->getSelectedPermissionUser($value->user_id, $id);
                $listGrantedUsers[$value->user_id]['permission'] = $userPermission;
            }
        }
;
        return $listGrantedUsers;
    }

    /**
     * @param $model
     * @param $id
     * @return array
     */
    public function history($model, $id)
    {
        $type = "App\Models\\" . $model;
        $exception = '[]';

        $activities = Activity::where('subject_id', '=', $id)
            ->where('subject_type', '=', $type)->where('description' , 'NOT LIKE', '%created%')
            ->where('properties','NOT LIKE', '%is_translate%')
            ->where('properties','NOT LIKE', '%'.$exception.'%')
            ->where('properties->language',Lang::getLocale())
            ->orderBy(
            'updated_at',
            'desc'
        )->get();

        $logs = [];

        foreach ($activities as $key => $value) {
            if (isset($value->changes)) {
                $firstName = isset($value->causer->name) ? $value->causer->name : null;
                $lastName = isset($value->causer->last_name) ? $value->causer->last_name : null;
                $logs[] = [
                    'id' => $value->id,
                    'userName' => $firstName . ' ' . $lastName,
                    'created_at' => isset($value->created_at) ? $value->created_at : null
                ];
            }
        }

        return $logs;
    }

    /**
     * Get current permission of this user
     *
     * @param $id
     * @return array
     */
    protected function getCurrentUsersPermissions($id)
    {
        return User::query()
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->where('users.id', '=', $id)
            ->pluck('permissions.name', 'permissions.id')->toArray();
    }

    /**
     * Get permissions for the selected user as array
     *
     * @param $userId
     * @param $projectId
     * @return array
     */
    protected function getSelectedPermissionUser($userId, $projectId)
    {
        return Permission::query()
            ->join('user_has_permissions', 'user_has_permissions.permission_id', '=', 'permissions.id')
            ->where('user_has_permissions.user_id', $userId)
            ->where('user_has_permissions.project_id', $projectId)
            ->pluck('permissions.name', 'permissions.id')->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function update(Request $request, Project $project)
    {
        $request->validate(
            [
                'name' => 'required',
                'imprint' => 'required'
            ]
        );

        if($request->has('project_image')){
            $this->setImage($request);
        }

        $project->update(
            [
                'name' => $request['name'],
                'imprint' => $request['imprint'],
                'terms' => $request['terms'],
                'description' => $request['description']
            ]
        );

        if (isset($request['logo']) && !is_null($request['logo'])) {
            $project->update(['logo' => $request['logo']]);
        }

        return redirect()->back()->with('success', __("message_edit_project_success"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', __("message_delete_project_success"));
    }

    /**
     * Drag and drop
     *
     * @return Application|Factory|View
     */
    public function move()
    {
        $data = $this->getAllProjects();

        return view('projects.move', compact('data'));
    }

    /**
     * create the specified resource from storage.
     *
     * @return Response
     */
    public function element()
    {
        return view('projects.element');
    }

    /**
     * Comment project
     *
     * @param Request $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function commentProject(Request $request, Project $project)
    {
        $request->validate(
            [
                'comment' => 'required',
            ]
        );

        return $project->commentAsUser($request);
    }

    /**
     * Retrieve all comment of current entry
     *
     * @param $id
     * @return JsonResponse
     */
    public function getProjectComment($id)
    {
        $comment = new CommentRetrieve();
        return $comment->getComments('App\Models\Project', $id);
    }

    /**
     * Save current entry
     *
     * @param Request $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function saveCommentProject(Request $request, Project $project)
    {
        if (isset($request['btn_submit'])) {
            if ($request['btn_submit'] == 'Edit') {
                return $project->editAsUser($request);
            } elseif ($request['btn_submit'] == 'delete') {
                return $project->deleteAsUser($request['id']);
            } else {
                return $project->replyAsUser($request);
            }
        }
    }

    /**
     * Set status project
     *
     * @param Request $request
     * @param Project $project
     * @return JsonResponse
     */
    public function setStatusProject(Request $request, Project $project)
    {
        $data = $project->status($request);
        return response()->json($data);
    }

    /**
     * Set permission for user on project
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function setPermissionForUserOnProject(Request $request)
    {
        //delete old permissions
        UserHasPermission::where('project_id', $request['project'])
            ->where('user_id', $request['user'])->delete();

        Invitation::where('project_id', $request['project'])
            ->where('guest_id', $request['user'])->delete();

        //save new permission
        if (isset($request['permissions']) && count($request['permissions']) > 0) {
            foreach ($request['permissions'] as $key => $permission) {
                UserHasPermission::firstOrCreate(
                    [
                        'project_id' => $request['project'],
                        'permission_id' => $permission,
                        'user_id' => $request['user']
                    ]
                );
            }
        }

        $user = User::findOrFail($request['user']);
        $permissions = $this->getCurrentUsersPermissions($request['user']);
        $error_code = 5;

        Invitation::firstOrCreate([
                               'user_id' => Auth::user()->id,
                               'guest_id' => $request['user'],
                               'project_id' => $request['project']],[
                               'created_at' => now()
                           ]);

        return redirect()->back()->with(['error_code' => $error_code, 'user' => $user, 'permissions' => $permissions]);
    }

    /**
     * ajax retrieve user's permission
     *
     * @param $id
     * @return JsonResponse
     */
    public function givePermissionToUser($id)
    {
        $ids = explode('_', $id);
        $data = UserHasPermission::where('user_id', $ids[0])
            ->where('project_id', $ids[1])
            ->pluck('permission_id');

        return response()->json($data);
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function getCurrentLog($id)
    {
        $log = new LogService();
        $activities = $log->textLog($id);

        return redirect()->back()->with('activities', $activities);
    }

    /**
     * @param $project
     * @param $id
     * @return Application|Factory|View
     */
    public function getDetails($project, $id)
    {
        $project = Project::findOrFail($project);

        $activities = Activity::where('id', '=', $id)->get();

        $changes = [];

        foreach ($activities as $key => $value) {
            if (isset($value->changes['old'])) {
                foreach ($value->changes['old'] as $k => $property) {
                    if (in_array($k, ['origin', 'copyright'])) {
                        $old = Source::where('id', $property)->where('type', $k)->first();
                        $new = Source::where('id', $value->changes['attributes'][$k])->where('type', $k)->first();

                        $highlight = $this->HighlightTextDifference(
                            $old->name,
                            $new->name
                        );

                        $changes[$k] = [
                            'old' => $highlight['old'],
                            'new' => $highlight['new'],
                            'oldId' => $property,
                        ];
                    } else {
                        if (in_array($k, ['url', 'image'])) {
                            $changes[$k] = [
                                'old' => $property,
                                'new' => $value->changes['attributes'][$k],
                            ];
                        } else {

                            $highlight = $this->HighlightTextDifference(
                                $property,
                                $value->changes['attributes'][$k]
                            );

                            $changes[$k] = [
                                'old' => $highlight['old'],
                                'new' => $highlight['new'],
                                'noHighlight' => $property,
                            ];
                        }
                    }
                }

                $changes['subjectId'] = $value->subject_id;
                $changes['subjectType'] = $value->subject_type;
            }
        }

        return view('logs.log', compact('changes', 'project'));
    }

    /**
     * Difference between text
     *
     * @param $old
     * @param $new
     * @return string[]
     */
    public function HighlightTextDifference($old, $new)
    {
        $from_start = is_null($old) ? strspn($new, "\0") : strspn($old ^ $new, "\0");
        $from_end = is_null($old) ? strspn(strrev($new), "\0") :strspn(strrev($old) ^ strrev($new), "\0");

        $old_end = strlen($old) - $from_end;
        $new_end = strlen($new) - $from_end;

        $start = substr($new, 0, $from_start);
        $end = substr($new, $new_end);
        $new_diff = substr($new, $from_start, $new_end - $from_start);
        $old_diff = substr($old, $from_start, $old_end - $from_start);

        $new = "$start<span style='background-color:#ccffcc'>$new_diff</span>$end";
        $old = "$start<del style='background-color:#ffcccc'>$old_diff</del>$end";
        return array("old" => $old, "new" => $new);
    }

    /**
     * Get parent text
     *
     * @param $id
     * @return Collection
     */
    public function getParentText($table, $model, $id)
    {
        switch ($table) {
            case 'entries':
                return DB::table($table)
                    ->join('chapters', 'chapters.id', '=', 'entries.chapter_id')
                    ->select('chapters.name as chapter_name', 'entries.name as entry_name')
                    ->where($table . '.id', '=', $id)
                    ->get();
            case 'images':
            case 'texts':
                return DB::table($table)
                    ->join('media_content', $table . '.id', '=', 'media_content.media_content_id')
                    ->join('entries', 'entries.id', '=', 'media_content.media_contentable_id')
                    ->join('chapters', 'chapters.id', '=', 'entries.chapter_id')
                    ->select('chapters.name as chapter_name', 'entries.name as entry_name')
                    ->where($table . '.id', '=', $id)
                    ->where('media_content.media_contentable_type', '=', $model)
                    ->get();
        }
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function resetValue(Request $request)
    {
        if (isset($request['subjectType']) && !is_null($request['subjectType'])) {
            $model = $request['subjectType']::findorFail($request['subjectId']);

            if (isset($request['nameReset'])) {
                $model->name = $request['nameReset'];
            }

            if (isset($request['subtitleReset'])) {
                $model->subtitle = $request['subtitleReset'];
            }

            if (isset($request['descriptionReset'])) {
                $model->description = $request['descriptionReset'];
            }

            if (isset($request['copyrightReset'])) {
                $model->copyright = $this->getSource($request['copyrightReset'], 'Copyright');
            }

            if (isset($request['originReset'])) {
                $model->copyright = $this->getSource($request['copyrightReset'], 'Origin');
            }

            if (isset($request['textReset'])) {
                $model->text = $request['noHighlight'];
            }

            if (isset($request['imageReset'])) {
                $model->image = $request['imageReset'];
            }

            if (isset($request['urlReset'])) {
                $model->url = $request['urlReset'];
            }

            if (isset($request['sourceReset'])) {
                $model->source = $request['sourceReset'];
            }

            if (isset($request['linkReset'])) {
                $model->link = $request['linkReset'];
            }

            $model->save();
        }

        return redirect(session('links')[2]);
    }

    /**
     * Get source of content
     *
     * @param $value
     * @param $type
     * @return mixed
     */
    protected function getSource($value, $type)
    {
        $source = Source::where('type', $type)->get();
        $id = '';
        foreach ($source as $key => $v) {
            if ($v->name == $value) {
                $id = $v->id;
                return $id;
            }
        }

        if ($id == '') {
            $id = Source::insertGetId(
                ['name' => json_encode([app()->getLocale() => $value]), 'type' => $type, 'created_at' => now()]
            );
            return $id;
        }

        return $this;
    }

    /**
     * Translate project
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function translateCurrentProject($id)
    {
        App::setlocale('de');
        $data = $this->allData($id);

        return view('translate.index', compact('data'));
    }

    /**
     * @param $id
     * @return array
     */
    public function allData($id)
    {
        $project = Project::findOrFail($id);
        $data = [];
        $isTranslated = 0;
        $total = 0;

        foreach ($project->chapters as $chapter) {
            $data[$chapter->id] = $chapter;
            if ($chapter->is_translated == 1) {
                $isTranslated++;
            }
            $total++;
            $entries = [];
            foreach ($chapter->entries as $entry) {
                $entries[$entry->id] = $entry;
                if ($entry->is_translated == 1) {
                    $isTranslated++;
                }
                $total++;
                $array = [];
                if (count($entry->mediaContent) > 0) {
                    $collection = $entry->mediaContent->toArray();
                    usort(
                        $collection,
                        function ($item1, $item2) {
                            return $item1['position'] <=> $item2['position'];
                        }
                    );

                    foreach ($collection as $item) {
                        if ($item['media_contentable_type'] == 'App\Models\Text') {
                            $text = Text::find($item['media_content_id']);
                            if ($text) {
                                $text->media_id = $item['id'];
                                $array[] = $text;
                                if ($text->is_translated == 1) {
                                    $isTranslated++;
                                }
                                $total++;

                                if ($text->originText->is_translated == 1) {
                                    $isTranslated++;
                                }
                                $total++;

                                if ($text->copyrightText->is_translated == 1) {
                                    $isTranslated++;
                                }
                                $total++;
                            }
                        } else if ($item['media_contentable_type'] == 'App\Models\Audiovisual') {
                            $audiovisual = Audiovisual::find($item['media_content_id']);
                            if ($audiovisual) {
                                $audiovisual->media_id = $item['id'];
                                $array[] = $audiovisual;
                                if ($audiovisual->is_translated == 1) {
                                    $isTranslated++;
                                }
                                $total++;

                            }
                        } else {
                            $gallery = Gallery::find($item['media_content_id']);
                            //$image = Image::find($item['media_content_id']);
                            if ($gallery) {
                                $gallery->media_id = $item['id'];
                                $gallery->image_list = $gallery->images;
                                $array[] = $gallery;

                                if ($gallery->is_translated == 1) {
                                    $isTranslated++;
                                }
                                $total++;
                            }
                        }
                    }
                }

                $entries[$entry->id]->media = $array;
            }
            $data[$chapter->id]->entry = $entries;
        }

        $percentage = 0;

        if ($isTranslated > 0) {
            $percentage = round(($isTranslated / $total) * 100, 2);
        }

        return ['data' => $data, 'percentageOfTranslation' => $percentage, 'projectId' => $id];
    }

    /**
     * User invitation
     *
     * @param $id
     * @param $projectId
     * @return Application|Factory|View
     */
    public function inviteUserForProject($id, $projectId)
    {
        $permissions = $this->getCurrentUsersPermissions($id);

        $user = User::findOrFail($id);
        $role = isset($user->role->userRole->name) ? $user->role->userRole->name : '';
        $permissionForProject = $this->getSelectedPermissionUser($id, $projectId);
        $listAllPermissions = Permission::orderBy('id', 'ASC')->pluck('name', 'id');

        return \view(
            'users.create',
            compact('user', 'permissions', 'role', 'permissionForProject', 'listAllPermissions', 'projectId')
        );
    }

    /**
     * Check whether input email exists
     * code_error 6: already exist
     * code_error 7: doesn't exist
     *
     * @param Request $request
     * @return RedirectResponse
     */
    protected function checkEmail(Request $request)
    {
        $user = User::where('email', $request->userEmail)->first();

        if ($user) {
            $role = isset($user->role->userRole->name) ? $user->role->userRole->name : '';
            $permissionForRole = [];
            if (isset($user->role->userRole->id)) {
                $permissionForRole = Role::query()
                    ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                    ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                    ->where('roles.id', $user->role->userRole->id)
                    ->pluck('permissions.name');
            }
            $listAllPermissions = Permission::orderBy('id', 'ASC')->pluck('name', 'id');
            $permissionForProject = $user->getAllPermissions()->pluck('name')->toArray();
            $permissionProject = $user->getAllPermissions()->pluck('name')->toArray();

            return Redirect()->back()->with(
                [
                    'error_code' => 6,
                    'user' => $user,
                    'role' => $role,
                    'listAllPermissions' => $listAllPermissions,
                    'permissionForProject' => $permissionForProject,
                    'permissionProject' => $permissionProject,
                    'permissionForRole' => $permissionForRole
                ]
            );
        } else {
            return Redirect()->back()->with(['error_code' => 7, 'email' => $request->userEmail]);
        }
    }

    /**
     * Get permissions for the selected user
     *
     * @param $userId
     * @param $projectId
     * @return Collection
     */
    protected function getSelectedPermissionUserPluck($userId, $projectId)
    {
        return Permission::query()
            ->join('user_has_permissions', 'user_has_permissions.permission_id', '=', 'permissions.id')
            ->where('user_has_permissions.user_id', $userId)
            ->where('user_has_permissions.project_id', $projectId)
            ->pluck('permissions.name', 'permissions.id');
    }

    /**
     * Get role of user
     *
     * @param $userId
     * @return Collection
     */
    protected function getRoleSelectedUser($userId)
    {
        return ModelHasRole::query()
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $userId)
            ->pluck('roles.name');
    }
    /**
     * Delete user from single project
     *
     * @param $userId
     * @param $projectId
     * @return RedirectResponse
     */
    protected function deleteUserFromProject($userId, $projectId){

     UserHasPermission::where('project_id',$projectId)
            ->where('user_id', $userId)->delete();

     Invitation::where('project_id', $projectId)
         ->where('guest_id', $userId)->delete();

        return redirect()->back()->with('success', __("message_edit_project_success"));
    }

    /**
     * Edit metadata
     *
     * @param $projectId
     * @return Application|Factory|View
     */
    public function editMetaData($projectId){

        $project = Project::findOrFail($projectId);
        $listGrantedUsers = $this->getUsersForThisProject($projectId);
        $listRole = Role::where('id', 'not like', '1')->pluck('name', 'id');
        $permissions = Permission::all();
        asort($listGrantedUsers);

        return \view('projects.create', compact('project', 'listGrantedUsers','listRole','permissions'));

    }

    /**
     * Preview project
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function previewProject(Request $request){
        $parameters = [];

        if (isset($request['colorAccent'])) $parameters['colorAccent'] = $request['colorAccent'];
        if (isset($request['colorChapter'])) $parameters['colorChapter'] = $request['colorChapter'];
        $parameters['backgroundSecond'] = (isset($request['backgroundSecond'])) ? "hintergrundgrau" : "hintergrundweiss";
        if (isset($request['collapse'])) $parameters['collapse'] = 1;
        if (isset($request['pdf'])) $parameters['pdf'] = 1;
        $parameters['id'] = $request['project'];
        $project = Project::findOrFail($request['project']);

        return \view('preview.index',compact('project', 'parameters'));
    }

    /**
     * Generate pdf
     *
     * @param Request $request
     */
    public function downloadPreview(Request $request){

        $parameters = [];

        if (isset($request->colorAccent)) $parameters['colorAccent'] = $request->colorAccent;
        if (isset($request->colorChapter)) $parameters['colorChapter'] = $request->colorChapter;
        $parameters['backgroundSecond'] = (isset($request->backgroundSecond)) ? "hintergrundgrau" : "hintergrundweiss";
        if (isset($request->collapse)) $parameters['collapse'] = $request->collapse;
        if (isset($request->pdf)) $parameters['pdf'] = 1;

        $project = Project::findOrFail($request->id);
        $html = View('preview.pdf',compact('project', 'parameters'))->render();

        $options = new Options();
        $options->setChroot(['/var/www/html/public/']);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();

    }


    public function projectMetadata(Request $request){

        $parameters = $request['parameters'];

        if (isset($parameters['id'])){
            $project = Project::findOrFail($parameters['id']);
            if ($request->type == 'copyright'){
                $content = $project->terms;
                $type = 'copyright';
            }else{
                $content = $project->imprint;
                $type = 'policy';
            }
        }

        return \view('preview.copyright', compact('project', 'parameters', 'content','type'));
    }
}
