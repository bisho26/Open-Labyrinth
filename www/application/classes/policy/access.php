<?php
class Policy_Access extends Policy {

    private $learnerRules = array(
        array('controller' => 'authoredLabyrinth', 'action' => 'index', 'isFullController' => true),
        array('controller' => 'collectionManager', 'action' => 'editCollection'),
        array('controller' => 'collectionManager', 'action' => 'addCollection'),
        array('controller' => 'labyrinthManager', 'action' => 'index', 'isFullController' => true),
        array('controller' => 'exportImportManager', 'action' => 'index', 'isFullController' => true),
        array('controller' => 'remoteServiceManager', 'action' => 'index', 'isFullController' => true),
        array('controller' => 'userManager', 'action' => 'index'),
        array('controller' => 'userManager', 'action' => 'addUser'),
        array('controller' => 'userManager', 'action' => 'saveNewUser'),
        array('controller' => 'userManager', 'action' => 'deleteUser'),
        array('controller' => 'userManager', 'action' => 'addGroup'),
        array('controller' => 'userManager', 'action' => 'saveNewGroup'),
        array('controller' => 'userManager', 'action' => 'editGroup'),
        array('controller' => 'userManager', 'action' => 'deleteGroup'),
        array('controller' => 'userManager', 'action' => 'addMemberToGroup'),
        array('controller' => 'userManager', 'action' => 'updateGroup'),
        array('controller' => 'userManager', 'action' => 'removeMember'),
        array('controller' => 'webinarManager', 'action' => 'add'),
        array('controller' => 'webinarManager', 'action' => 'edit'),
        array('controller' => 'webinarManager', 'action' => 'statistic'),
        array('controller' => 'webinarManager', 'action' => 'delete'),
        array('controller' => 'webinarManager', 'action' => 'save'),
        array('controller' => 'webinarManager', 'action' => 'changeStep'),
        array('controller' => 'dForumManager', 'action' => 'addForum'),
        array('controller' => 'dForumManager', 'action' => 'editForum'),
        array('controller' => 'dForumManager', 'action' => 'updateForum'),
        array('controller' => 'dForumManager', 'action' => 'deleteForum'),
        array('controller' => 'dForumManager', 'action' => 'saveNewForum')
    );

    private $authorRules = array(
        array('controller' => 'remoteServiceManager', 'action' => 'index', 'isFullController' => true),
        array('controller' => 'userManager', 'action' => 'index'),
        array('controller' => 'userManager', 'action' => 'addUser'),
        array('controller' => 'userManager', 'action' => 'saveNewUser'),
        array('controller' => 'userManager', 'action' => 'deleteUser'),
        array('controller' => 'userManager', 'action' => 'addGroup'),
        array('controller' => 'userManager', 'action' => 'saveNewGroup'),
        array('controller' => 'userManager', 'action' => 'editGroup'),
        array('controller' => 'userManager', 'action' => 'deleteGroup'),
        array('controller' => 'userManager', 'action' => 'addMemberToGroup'),
        array('controller' => 'userManager', 'action' => 'updateGroup'),
        array('controller' => 'userManager', 'action' => 'removeMember')
    );

    private $reviewerRules = array(
        array('controller' => 'collectionManager', 'action' => 'editCollection'),
        array('controller' => 'collectionManager', 'action' => 'addCollection')
    );

    private $mapActions = array(
        array('controller' => 'labyrinthManager', 'action' => 'global'),
        array('controller' => 'labyrinthManager', 'action' => 'info'),
        array('controller' => 'labyrinthManager', 'action' => 'showDevNotes'),
        array('controller' => 'visualManager', 'action' => 'index'),
        array('controller' => 'nodeManager', 'action' => 'index'),
        array('controller' => 'nodeManager', 'action' => 'grid'),
        array('controller' => 'linkManager', 'action' => 'index'),
        array('controller' => 'nodeManager', 'action' => 'sections'),
        array('controller' => 'chatManager', 'action' => 'index'),
        array('controller' => 'questionManager', 'action' => 'index'),
        array('controller' => 'avatarManager', 'action' => 'index'),
        array('controller' => 'counterManager', 'action' => 'index'),
        array('controller' => 'counterManager', 'action' => 'grid'),
        array('controller' => 'visualdisplaymanager', 'action' => 'index'),
        array('controller' => 'counterManager', 'action' => 'rules'),
        array('controller' => 'elementManager', 'action' => 'index'),
        array('controller' => 'clusterManager', 'action' => 'index'),
        array('controller' => 'feedbackManager', 'action' => 'index'),
        array('controller' => 'skinManager', 'action' => 'index'),
        array('controller' => 'fileManager', 'action' => 'index'),
        array('controller' => 'mapUserManager', 'action' => 'index'),
        array('controller' => 'reportManager', 'action' => 'index')
    );

    private $forumActions = array(
        array('controller' => 'dforumManager', 'action' => 'viewForum'),
        array('controller' => 'dforumManager', 'action' => 'editForum'),
        array('controller' => 'dforumManager', 'action' => 'deleteForum')
    );

    private $topicActions = array(
        array('controller' => 'dtopicManager', 'action' => 'editTopic'),
        array('controller' => 'dtopicManager', 'action' => 'deleteTopic')
    );

    private $scenariosActions = array(
        array('controller' => 'webinarManager', 'action' => 'edit'),
        array('controller' => 'webinarManager', 'action' => 'delete'),
        array('controller' => 'webinarManager', 'action' => 'statistics')
    );

    public function execute (Model_Leap_User $user, array $array = NULL)
    {
        $controller = Arr::get($array, 'controller');
        $action     = Arr::get($array, 'action');
        $id         = Arr::get($array, 'id');
        $id2        = Arr::get($array, 'id2');
        $userType   = Auth::instance()->get_user()->type->name;
        $userId     = Auth::instance()->get_user()->id;

        if ($userType == 'superuser') return false;

        // ----- user role ----- //
        switch ($userType)
        {
            case 'learner':
                $rules = $this->learnerRules;
                break;
            case 'author':
            case 'Director':
                $rules = $this->authorRules;
                break;
            case 'reviewer':
                $rules = $this->reviewerRules;
                break;
            default:
                return false;
        }

        foreach ($rules as $rule)
        {
            if ((Arr::get($rule, 'isFullController', false) AND strtolower($rule['controller']) == $controller) OR
                (strtolower($rule['controller']) == $controller AND strtolower($rule['action']) == $action)) return true;
        }
        // ----- user role ----- //

        // ----- user allowedScenarios ----- //
        $allowedScenarios = DB_ORM::model('webinar')->getAllowedWebinars($userId);
        foreach($this->scenariosActions as $rule)
        {
            if (strtolower($rule['controller']) == $controller AND strtolower($rule['action']) == $action AND ! in_array($id, $allowedScenarios)) return true;
        }
        // ----- end user allowedScenarios ----- //

        // ----- user allowedMaps ----- //
        $allowedMap = DB_ORM::model('map')->getAllowedMap($userId);
        if ($userType == 'author')
        {
            $collectionsMaps = DB_ORM::model('map_collectionmap')->getAllColMapsIds();
            $allowedMap = array_merge($allowedMap, $collectionsMaps);
            $allowedMap = array_unique($allowedMap);
        }

        foreach ($this->mapActions as $rule)
        {
            if (strtolower($rule['controller']) == $controller AND strtolower($rule['action']) == $action AND ! in_array($id, $allowedMap)) return true;
        }
        // ----- end user allowedMaps ----- //

        // ----- user allowedTopics ----- //
        $allowedTopics = DB_ORM::model('dtopic')->getAllowedTopics($userId);
        foreach ($this->topicActions as $rule)
        {
            if (strtolower($rule['controller']) == $controller AND strtolower($rule['action']) == $action AND ! in_array($id2,$allowedTopics)) return true;
        }
        // ----- end user allowedTopics ----- //

        // ----- user allowedForums ----- //
        $openForums     = DB_ORM::model('dforum')->getAllOpenForums();
        $privateForums  = DB_ORM::model('dforum')->getAllPrivateForums();
        $forums         = array_merge($openForums, $privateForums);
        $allowedForums  = array();

        foreach ($forums as $forum)
        {
            $allowedForums[] = $forum['id'];
        }

        foreach ($this->forumActions as $rule)
        {
            if (strtolower($rule['controller']) == $controller AND strtolower($rule['action']) == $action AND ! in_array($id,$allowedForums)) return true;
        }
        // ----- end user allowedForums ----- //
        return false;
    }
}